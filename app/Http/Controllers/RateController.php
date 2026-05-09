<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConversionHistory;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function index()
    {
        $history = [];
        if (Auth::check()) {
            $history = ConversionHistory::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }
        return view('rates', compact('history'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|max:5',
            'to_currency' => 'required|string|max:5',
            'amount' => 'required|numeric',
            'result' => 'required|numeric',
            'rate' => 'required|numeric',
        ]);

        if (Auth::check()) {
            ConversionHistory::create([
                'user_id' => Auth::id(),
                'from_currency' => $request->from_currency,
                'to_currency' => $request->to_currency,
                'amount' => $request->amount,
                'result' => $request->result,
                'rate' => $request->rate,
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu lịch sử.']);
    }
}
