<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('query');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Invoice::select('customer_name as name', 'phone as mobile', 'address')
            ->where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->groupBy('customer_name', 'phone', 'address')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
}
