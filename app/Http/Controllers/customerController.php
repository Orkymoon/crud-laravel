<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // search keyword
        $kw = $request->q;
        session(['last_search' => $kw]);

        if (empty($kw)) {
            // Display All data with pagination if no keyword to search
            $customers = customer::paginate(10);
        } else {
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
        }
        // render page view
        return view('customer.index', ['customers' => $customers, 'kw' => $kw])->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'country' => $request->country

        ];
        customer::create($data);

        toastr()->success('Data has been saved successfully!');
        return redirect()->to('customer');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = customer::where('id', $id)->first();
        return view('customer.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     'nama' => 'required',
        //     'address' => 'required',
        // ], [
        //     'nama.required' => 'Nama wajib diisi',
        //     'address.required' => 'Jurusan wajib diisi',
        // ]);
        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'country' => $request->country
        ];
        customer::where('id', $id)->update($data);

        // dd($data);
        toastr()->success('Data has been successfully updated');
        return redirect()->to('customer');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, customer $customer)
    {
        $customer->delete();

        toastr()->success('Data has been successfully deleted');
        return redirect()->back();
    }
}
