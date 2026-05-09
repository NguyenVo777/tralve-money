<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalysisController extends Controller
{
    public function analyze(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:5120',
            ]);

            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->getPathname()));
            $mimeType = $image->getMimeType();

            $apiKey = config('services.gemini.key');
            
            if (!$apiKey) {
                // Fallback for demo if no key is set, but with a warning
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng cấu hình GEMINI_API_KEY trong file .env để sử dụng tính năng này.'
                ]);
            }

            $prompt = "Bạn là một chuyên gia giám định tiền tệ chuyên nghiệp. 
            Hãy phân tích hình ảnh tờ tiền này và trả về kết quả dưới dạng JSON thuần túy (không có markdown, không có text bao quanh) với các trường sau:
            - score: con số từ 0-100 thể hiện độ nguyên vẹn.
            - status_label: 'TỐT', 'CẦN XEM XÉT' hoặc 'HƯ HỎNG NẶNG'
            - detection: mô tả ngắn gọn về tình trạng rách, mờ, hay cháy xém phát hiện được.
            - exchange_rate: khả năng thu đổi (ví dụ: 'Thu đổi 100%', 'Phí 5%', 'Không thể thu đổi').
            - advice: lời khuyên chuyên gia cho người dùng.
            Chỉ trả về JSON.";

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json'
                ]
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối với AI: ' . $response->body()
                ]);
            }

            $result = $response->json();
            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            // Clean up potentially returned markdown
            $jsonString = trim(str_replace(['```json', '```'], '', $content));
            $analysis = json_decode($jsonString, true);

            if (!$analysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể phân tích kết quả từ AI.'
                ]);
            }

            return response()->json([
                'success' => true,
                'analysis' => $analysis
            ]);

        } catch (\Exception $e) {
            Log::error('AI Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình xử lý: ' . $e->getMessage()
            ]);
        }
    }
}
