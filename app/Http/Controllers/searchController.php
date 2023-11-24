<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;

class searchController extends Controller
{
    public function customer(Request $request)
    {
        // search keyword
        $kw = $request->q;
        // if (empty($kw)) {
        //     // Display All data with pagination if no keyword to search
        //     $customers = customer::paginate(5);
        // } else {
        // Display Filtered data with pagination if keyword exists
        $customers = customer::where('name', 'like', "%{$kw}%")
            ->orWhere('address', 'like', "%{$kw}%")
            ->orWhere('city', 'like', "%{$kw}%")
            ->orWhere('pincode', 'like', "%{$kw}%")
            ->orWhere('country', 'like', "%{$kw}%")
            ->paginate(10)
            ->appends(['q' => "{$kw}"])
            ->withPath('/customer')
            ->withQueryString();
        // }

        // converting array to laravel collection
        $customersCollection = collect($customers);
        // merging queried data with pagination links HTML
        $customersCollection = $customersCollection->merge(['pagination_links' => (string) $customers->onEachSide(2)->links()]);
        // returning the response data as JSON string
        return collect(["customers" => $customersCollection->all()])->toJson();
    }
}
