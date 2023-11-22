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
            <table class="table table-striped table-hover table-bordered table-hover" id="customerTbl"
                style="height: 455px;">
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
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="delete">
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
            <div class="clearfix" id="pagination-links">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // Ajax Variable
        var search_ajax;
        $(document).ready(function() {
            // Execute Live Searcing when search box input has character entered or change
            $('#searchCustomer').on('input change', function(e) {
                e.preventDefault()
                // current keyword value
                var searchTxt = $(this).val()
                // Get Url Parameters
                var urlParams = new URLSearchParams(location.search);
                // New Parameters aray
                var newParams = []
                urlParams.forEach((v, k) => {
                    if (k == 'q') {
                        // update keyword value
                        v = searchTxt
                    }
                    // Add parameter to the new parameter
                    if (searchTxt != "" && k == 'q')
                        newParams.push(`${k}=${encodeURIComponent(v)}`);
                })
                // Update Location URL without reloading the page
                if (newParams.length > 0) {
                    // structuring the new URL
                    var newLink = `{{ URL::to('/customer') }}?` + (newParams.join('&'));
                    // Update the location URL
                    history.pushState({}, "", newLink)
                } else {
                    if (searchTxt != "") {
                        // Update location URL
                        history.pushState({}, "", `{{ URL::to('/customer') }}?q=${encodeURIComponent(searchTxt)}`)
                    } else {
                        // Update location URL
                        history.pushState({}, "", `{{ URL::to('/customer') }}`)
                    }

                }

                if (search_ajax != undefined && search_ajax != null) {
                    // Abort Previous Search Ajax Process if exists
                    search_ajax.abort();
                }
                // Start Search Ajax Process
                search_ajax = $.ajax({
                    url: `{{ route('customer.search') }}?q=${searchTxt}`,
                    dataType: 'json',
                    error: err => {
                        console.log(err)
                        if (err.statusText != 'abort')
                            alert('An error occurred');
                    },
                    success: function(resp) {
                        if (!!resp.customers) {
                            // Data Table Body Element
                            var tblBody = $('#customerTbl tbody')
                            // Page Links Wrapper Element
                            var paginationLink = $('#pagination-links')
                            // remove current data on the table
                            tblBody.html('')
                            // remove current pagination links
                            paginationLink.html('')
                            if (!!resp.customers.data) {
                                // Loop the searched data
                                Object.values(resp.customers.data).map(customer => {
                                    // creating new table row
                                    var tr = $('<tr>')
                                    // creting the new columns and data of the row
                                    tr.append(
                                        `<td class="text-center">${customer.id}</td>`
                                    )
                                    tr.append(`<td>${customer.name}</td>`)
                                    tr.append(`<td>${customer.address}</td>`)
                                    tr.append(`<td>${customer.city}</td>`)
                                    tr.append(`<td>${customer.pincode}</td>`)
                                    tr.append(`<td>${customer.country}</td>`)
                                    tr.append(
                                        `<td>
                                            <form action="{{ route('customer.destroy', $customer->id) }}" method="delete">
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
                                        </td>`
                                    )
                                    // inserting the created data row the table
                                    tblBody.append(tr)
                                })

                                if (Object.keys(resp.customers.data).length <= 0) {
                                    // Display Message if no data found that is match to the keyword
                                    var tr = $('<tr>')
                                    tr.append(
                                        `<td class="text-center" colspan="7">No search keyword match found.</td>`
                                    )
                                    tblBody.append(tr)
                                }
                            }
                            // Update Pagination link
                            if (resp.customers.pagination_links)
                                paginationLink.html(resp.customers.pagination_links)
                            console.log('datanya', resp.customers);
                        }
                    }
                })
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
