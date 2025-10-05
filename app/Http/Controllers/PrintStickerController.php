<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrintStickerController extends Controller
{
    public function printSticker(Request $request)
    {
        $saleId = $request->get('sale_id');
        $customerName = $request->get('customer_name');
        $senderNumber = $request->get('sender_number');
        $customerPhone = $request->get('customer_phone');
        $location = $request->get('location');
        $products = $request->get('products');
        $totalAmount = $request->get('total_amount');

        $sale = Sale::with(['customer', 'user', 'items.product'])->find($saleId);

        return view('print.sticker', compact(
            'sale',
            'customerName',
            'senderNumber',
            'customerPhone',
            'location',
            'products',
            'totalAmount'
        ));
    }
}
