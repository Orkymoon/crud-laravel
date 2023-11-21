@extends('layouts.main')

@section('title', 'Create')

@section('content')
    <form action='{{ url('customer') }}' method='post'>
        @csrf
        <div class="my-3 p-3 bg-body rounded">
            <div class="mb-3 row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name='name' id="name">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="address" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name='address' id="address">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="city" class="col-sm-2 col-form-label">City</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name='city' id="city">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="pincode" class="col-sm-2 col-form-label">Pin Code</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name='pincode' id="pincode">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="country" class="col-sm-2 col-form-label">Country</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name='country' id="country">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">Save</button></div>
            </div>
        </div>
    </form>
@endsection
