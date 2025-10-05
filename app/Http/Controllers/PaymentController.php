<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Frontend redirect after payment
    public function success(Request $request)
    {
        $tranId = $request->query('tran_id'); // ?tran_id=...
        $sale = Sale::where('reference', $tranId)->first();
    
        if (!$sale) {
            return view('payment.success')->with('message', 'Sale not found or payment not completed.');
        }
    
        // Optional: pass status from PayWay if available
        $statusCode = $request->query('status_code') ?? '00'; // fallback to "00" if missing
    
        return view('payment.success', compact('sale', 'statusCode'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'tran_id' => 'required|string',
            'status_code' => 'required|string',
        ]);

        $tranId = $request->tran_id;
        $statusCode = $request->status_code;

        $sale = Sale::where('reference', $tranId)->first();
        if (!$sale) {
            return response()->json(['error' => 'Sale not found'], 404);
        }

        $isPaid = ($statusCode === '00' || strtolower($statusCode) === 'success');
        $sale->status = $isPaid ? 'paid' : 'failed';
        $sale->save();

        return response()->json(['message' => 'Status updated', 'status' => $sale->status]);
    }
}
