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
        if (empty($kw)) {
            // Display All data with pagination if no keyword to search
            $customers = customer::paginate(5);
        } else {
            // Display Filtered data with pagination if keyword exists
            $customers = customer::where('name', 'like', "%{$kw}%")
                ->paginate(5)
                ->appends(['q' => "{$kw}"])
                ->withPath('/')
                ->withQueryString();
        }

        // converting array to laravel collection
        $customersCollection = collect($customers);
        // merging queried data with pagination links HTML
        $customersCollection = $customersCollection->merge(['pagination_links' => (string) $customers->links()]);
        // returning the response data as JSON string
        return collect(["customers" => $customersCollection->all()])->toJson();
    }
}
