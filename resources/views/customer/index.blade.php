@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="table-responsive">
        <div class="table-wrapper">
            @if ($message = Session::get('success'))
                <p>{{ $message }}</p>
            @endif

            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2>Customer <b>Details</b></h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="search-box">
                            <i class="material-icons">&#xE8B6;</i>
                            <input type="text" class="form-control" placeholder="search" id="searchCustomer"
                                name="search">
                            <span id="customerList"></span>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover table-bordered table-hover" style="height: 455px;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name <i class="fa fa-sort"></i></th>
                        <th scope="col">Address</th>
                        <th scope="col">City <i class="fa fa-sort"></i></th>
                        <th scope="col">Pin Code</th>
                        <th scope="col">Country <i class="fa fa-sort"></i></th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->city }}</td>
                            <td>{{ $customer->pincode }}</td>
                            <td>{{ $customer->country }}</td>
                            <td>
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="post">
                                    <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                            class="material-icons">&#xE417;</i></a>
                                    <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                            class="material-icons">&#xE254;</i></a>
                                    @csrf
                                    @method('delete')
                                    <a href="#" class="delete" title="Delete" data-toggle="tooltip"
                                        onclick="this.closest('form').submit();return false;"><i
                                            class="material-icons">&#xE872;</i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
