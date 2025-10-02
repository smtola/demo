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
        $tranId = $request->query('tran_id'); // from ?tran_id=...
        $sale = Sale::where('reference', $tranId)->first();
       
        if (!$sale) {
            return view('payment.success')->with('message', 'Sale not found or payment not completed.');
        }

        return view('payment.success', compact('sale'));
    }
 
    // Backend notification (server-to-server)
    public function notify(Request $request)
    {
        $data = $request->all();
        Log::info('Payment notify payload received', [
            'payload' => $data,
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
        ]);

        $transactionId = $data['tran_id'] ?? $data['transaction_id'] ?? null;
        $status = (string) ($data['status'] ?? $data['result'] ?? '');

        if (!$transactionId) {
            Log::warning('Payment notify missing transaction id', ['payload' => $data]);
            return response()->json(['message' => 'Missing transaction id'], 422);
        }

        $isSuccess = $status === '0' || $status === '00' || strtolower($status) === 'success';

        Sale::where('reference', $transactionId)->update([
            'status' => $isSuccess ? 'paid' : 'failed',
        ]);

        return response()->json(['message' => 'OK']);
    }
}
