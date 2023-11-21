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
        $query = $request->get('query');
        // $customers = customer::latest()->paginate(5);
        $customers = customer::where('name', 'LIKE', '%' . $query . '%')->paginate(5);


        return view('customer.index', compact('customers'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            if ($query != '') {
                $data = customer::where('name', 'like', '%' . $query . '%')
                    ->orWhere('address', 'like', '%' . $query . '%')
                    ->orWhere('city', 'like', '%' . $query . '%')
                    ->orWhere('pincode', 'like', '%' . $query . '%')
                    ->orWhere('country', 'like', '%' . $query . '%')
                    ->orderBy('id', 'desc')
                    ->limit(5)->get();
            } else {
                $customers = customer::latest()->paginate(5);
            }
            $total_row = $data->count();
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .=
                        '
                    <tr>
                    <td>' . $row->name . '</td>
                    <td>' . $row->address . '</td>
                    <td>' . $row->city . '</td>
                    <td>' . $row->pincode . '</td>
                    <td>' . $row->country . '</td>
                    </tr>
                    ';
                }
            } else {
                $output .=
                    '
                ';
            }
            $data = array(
                'table_data'  => $output,
                'total_data'  => $total_row
            );
            echo json_encode($data);
        }
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
        return 'aku adalah orang bangsatt';
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(customer $customer)
    {
        $customer->delete();
        return redirect('customer')
            ->withInput(['modal' => 'myModal'])
            ->with('success', 'Customer deleted successfully');
    }
}
