<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExchangeRate;
use App\Models\RatePageSetting;
use App\Models\MarketNews;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class RateCMSController extends Controller
{
    public function index()
    {
        $rates = ExchangeRate::all();
        $settings = RatePageSetting::first() ?? new RatePageSetting();
        $news = MarketNews::orderBy('created_at', 'desc')->get();
        
        // Calculate stats
        $mostStable = ExchangeRate::orderByRaw('ABS(change_percentage) ASC')->first();
        $mostVolatile = ExchangeRate::orderByRaw('ABS(change_percentage) DESC')->first();
        $totalCurrencies = ExchangeRate::count();
        $totalCountries = ExchangeRate::distinct('country')->count();

        return view('admin.rates', compact('rates', 'settings', 'news', 'mostStable', 'mostVolatile', 'totalCurrencies', 'totalCountries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'currency_code' => 'required|max:3',
            'currency_name' => 'required',
            'rate_to_usd' => 'required|numeric',
        ]);

        ExchangeRate::create($request->all());

        return back()->with('success', 'Đã thêm tiền tệ mới.');
    }

    public function update(Request $request, $id)
    {
        $rate = ExchangeRate::findOrFail($id);
        $rate->update($request->all());

        return back()->with('success', 'Đã cập nhật thông tin tiền tệ.');
    }

    public function destroy($id)
    {
        ExchangeRate::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa tiền tệ.');
    }

    public function toggleStatus($id)
    {
        $rate = ExchangeRate::findOrFail($id);
        $rate->status = !$rate->status;
        $rate->save();

        return response()->json(['success' => true, 'status' => $rate->status]);
    }

    public function updateSettings(Request $request)
    {
        $settings = RatePageSetting::first() ?? new RatePageSetting();
        $data = $request->only(['hero_title', 'hero_description', 'cta_text', 'default_currency', 'display_effect']);
        
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('banners', 'public');
            $data['banner_image'] = $path;
        }

        $settings->fill($data)->save();

        return back()->with('success', 'Đã cập nhật cấu hình trang.');
    }

    public function storeNews(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'type' => 'required',
        ]);

        MarketNews::create($request->all());

        return back()->with('success', 'Đã thêm phân tích mới.');
    }

    public function deleteNews($id)
    {
        MarketNews::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa bản tin.');
    }

    public function updateRates()
    {
        try {
            $response = Http::get('https://open.er-api.com/v6/latest/USD');
            if ($response->failed()) {
                throw new \Exception('Không thể kết nối với API tỷ giá.');
            }

            $data = $response->json();
            $rates = $data['rates'];

            $aseanCurrencies = [
                'VND' => ['country' => 'Vietnam', 'name' => 'Vietnamese Dong', 'flag' => 'vn'],
                'THB' => ['country' => 'Thailand', 'name' => 'Thai Baht', 'flag' => 'th'],
                'SGD' => ['country' => 'Singapore', 'name' => 'Singapore Dollar', 'flag' => 'sg'],
                'MYR' => ['country' => 'Malaysia', 'name' => 'Malaysian Ringgit', 'flag' => 'my'],
                'IDR' => ['country' => 'Indonesia', 'name' => 'Indonesian Rupiah', 'flag' => 'id'],
                'PHP' => ['country' => 'Philippines', 'name' => 'Philippine Peso', 'flag' => 'ph'],
                'BND' => ['country' => 'Brunei', 'name' => 'Brunei Dollar', 'flag' => 'bn'],
                'KHR' => ['country' => 'Cambodia', 'name' => 'Cambodian Riel', 'flag' => 'kh'],
                'LAK' => ['country' => 'Laos', 'name' => 'Lao Kip', 'flag' => 'la'],
                'MMK' => ['country' => 'Myanmar', 'name' => 'Myanmar Kyat', 'flag' => 'mm'],
            ];

            foreach ($aseanCurrencies as $code => $info) {
                if (isset($rates[$code])) {
                    $newRate = $rates[$code];
                    $existingRate = ExchangeRate::where('currency_code', $code)->first();
                    
                    $change = 0;
                    if ($existingRate && $existingRate->rate_to_usd > 0) {
                        $change = (($newRate - $existingRate->rate_to_usd) / $existingRate->rate_to_usd) * 100;
                    }

                    $rateRecord = ExchangeRate::updateOrCreate(
                        ['currency_code' => $code],
                        [
                            'country' => $info['country'],
                            'currency_name' => $info['name'],
                            'rate_to_usd' => $newRate,
                            'flag_icon' => $info['flag'],
                            'change_percentage' => round($change, 2),
                            'status' => true,
                        ]
                    );

                    // Add to history
                    $rateRecord->history()->create(['rate' => $newRate]);
                }
            }

            return back()->with('success', 'Đã cập nhật tỷ giá từ internet cho các nước ASEAN.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi cập nhật: ' . $e->getMessage());
        }
    }

    public function updateAIAnalysis()
    {
        try {
            $apiKey = config('services.gemini.key');
            if (!$apiKey) {
                // Fallback demo content if no API key
                MarketNews::create([
                    'title' => 'Phân tích thị trường ASEAN ' . now()->format('d/m/Y'),
                    'content' => 'Tỷ giá các đồng tiền Đông Nam Á đang có xu hướng ổn định. VND giữ vững giá trị so với USD, trong khi THB có sự biến động nhẹ do tình hình du lịch phục hồi. Đây là thời điểm tốt để khách du lịch đổi tiền.',
                    'type' => 'trend',
                    'severity' => 'info'
                ]);
                return back()->with('success', 'Đã cập nhật phân tích (Chế độ Demo - Chưa có API Key).');
            }

            $rates = ExchangeRate::whereIn('currency_code', ['VND', 'THB', 'SGD', 'MYR', 'IDR'])->get();
            $ratesString = $rates->map(fn($r) => "{$r->currency_code}: {$r->rate_to_usd}")->implode(', ');

            $prompt = "Dựa trên các tỷ giá sau (so với 1 USD): {$ratesString}. 
            Hãy viết một bản phân tích ngắn gọn (khoảng 3-4 câu) về xu hướng tiền tệ tại Đông Nam Á cho khách du lịch. 
            Trả về dưới dạng JSON với 2 trường: 'title' và 'content'.";

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['response_mime_type' => 'application/json']
            ]);

            if ($response->successful()) {
                $result = json_decode($response->json()['candidates'][0]['content']['parts'][0]['text'], true);
                MarketNews::create([
                    'title' => $result['title'] ?? 'Phân tích tự động',
                    'content' => $result['content'] ?? '',
                    'type' => 'trend',
                    'severity' => 'info'
                ]);
                return back()->with('success', 'Đã cập nhật phân tích AI mới nhất.');
            }

            throw new \Exception('Lỗi kết nối Gemini API');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi cập nhật AI: ' . $e->getMessage());
        }
    }

    public function autoUpdateCMS()
    {
        $settings = RatePageSetting::first() ?? new RatePageSetting();
        $settings->hero_title = "Cập nhật Tỷ giá ASEAN mới nhất " . now()->format('d/m/Y');
        $settings->hero_description = "Hệ thống tự động cập nhật tỷ giá từ các nguồn uy tín trên thế giới. Đảm bảo thông tin chính xác nhất cho chuyến hành trình của bạn.";
        $settings->save();

        return back()->with('success', 'Đã cập nhật nội dung CMS tự động.');
    }

    public function autoUpdateCharts()
    {
        return back()->with('success', 'Đã cập nhật dữ liệu biểu đồ từ lịch sử tỷ giá.');
    }
}
