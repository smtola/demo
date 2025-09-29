<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

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
        dd($data);
        if (isset($data['status']) && $data['status'] == '0') { // ✅ 0 = success
            Sale::where('reference', $data['tran_id'])->update([
                'status' => 'paid'
            ]);
        } else {
            Sale::where('reference', $data['tran_id'])->update([
                'status' => 'failed'
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
