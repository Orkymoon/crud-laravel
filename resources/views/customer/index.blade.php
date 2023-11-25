@extends('layouts.main')

@section('title', 'Read')

@section('content')
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2>Customer <b>Details</b></h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="search-box">
                            <i class="material-icons">&#xE8B6;</i>
                            <input type="text" class="form-control" placeholder="search" id="searchCustomer" name="search"
                                value="{{ Session::get('last_search') }}">
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
                        <th scope="col">id</th>
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
                            <td class="text-center">{{ ++$i }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->city }}</td>
                            <td>{{ $customer->pincode }}</td>
                            <td>{{ $customer->country }}</td>
                            <td>
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="post">
                                    <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                            class="material-icons">&#xE417;</i></a>
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="edit" title="Edit"
                                        data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                                    @csrf
                                    @method('delete')
                                    <a href="#" class="delete show-alert-delete-box" title="Delete"
                                        data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix" id="pagination-links">
                {{ $customers->onEachSide(2)->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
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
                        history.pushState({}, "",
                            `{{ URL::to('/customer') }}?q=${encodeURIComponent(searchTxt)}`)
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
                                Object.values(resp.customers.data).map((customer, index) => {
                                    // creating new table row
                                    var tr = $('<tr>')
                                    var deleteCustomerUrl =
                                        `{{ URL::to('/customer') }}/${customer.id}`;
                                    var updateCustomerUrl =
                                        `{{ URL::to('/customer') }}/${customer.id}/edit`;
                                    // creting the new columns and data of the row
                                    tr.append(
                                        `<td class="text-center">${index + 1}</td>`
                                    )
                                    tr.append(`<td>${customer.name}</td>`)
                                    tr.append(`<td>${customer.id}</td>`)
                                    tr.append(`<td>${customer.address}</td>`)
                                    tr.append(`<td>${customer.city}</td>`)
                                    tr.append(`<td>${customer.pincode}</td>`)
                                    tr.append(`<td>${customer.country}</td>`)
                                    tr.append(
                                        `<td>
                                            <form action="${deleteCustomerUrl}" method="post">
                                                <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                        class="material-icons">&#xE417;</i></a>
                                                <a href="${updateCustomerUrl}" class="edit" title="Edit" data-toggle="tooltip"><i
                                                        class="material-icons">&#xE254;</i></a>
                                                @csrf
                                                @method('delete')
                                                <a href="#" class="delete show-alert-delete-box" title="Delete"
                                                        data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
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
                                        `<td class="text-center" colspan="8">No search keyword match found.</td>`
                                    )
                                    tblBody.append(tr)
                                }
                            }
                            // Update Pagination link
                            if (!!resp.customers.pagination_links)
                                paginationLink.html(resp.customers.pagination_links)
                        }
                    }
                })
            })
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.show-alert-delete-box', function(event) {
                var form = $(this).closest("form");

                event.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your data has been deleted.",
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else if (result.isDismissed) {
                        swal.fire({
                            title: "Cancelled",
                            text: "Your imaginary data is safe :)",
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
    <script>
        // Temukan elemen dengan kelas "delete" dan tambahkan event listener
        document.querySelectorAll('.show-input-delete-box').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // const id = this.getAttribute('data-id'); // Dapatkan ID dari atribut data-id

                Swal.fire({
                    title: 'Input ID',
                    // text: 'You won't be able to revert this!',
                    // icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Next",
                    input: 'number',
                    // inputLabel: 'Input ID', // Label untuk input
                    inputPlaceholder: 'Input ID', // Placeholder untuk input
                    inputValidator: (value) => {
                        if (!value) {
                            return 'ID cannot be empty!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const inputId = result.value;
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your data has been deleted.",
                                    icon: "success"
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        // Mengambil nilai dari input di SweetAlert2
                                        // Lakukan sesuatu dengan nilai inputId, misalnya, hapus data dengan ID tersebut
                                        // const form = document.createElement('form');
                                        // form.setAttribute('action',
                                        //     `/customer/${inputId}`);
                                        // form.setAttribute('method', 'POST');
                                        // form.innerHTML = `
                                    // @csrf
                                    // @method('DELETE') `;
                                        // document.body.appendChild(form);
                                        // form.submit();
                                        fetch(`/customer/${inputId}`)
                                            .then(response => {
                                                if (response.ok) {
                                                    // Jika respons OK, ID ada di database, maka lanjutkan dengan penghapusan
                                                    const form = document
                                                        .createElement('form');
                                                    form.setAttribute('action',
                                                        `/customer/${inputId}`
                                                        );
                                                    form.setAttribute('method',
                                                        'POST');
                                                    form.innerHTML = `
                                                    @csrf
                                                    @method('DELETE')
                                                         `;
                                                    document.body.appendChild(
                                                        form);
                                                    form.submit();
                                                } else {
                                                    // Jika respons tidak OK, tampilkan pesan error menggunakan SweetAlert2
                                                    Swal.fire('Error!',
                                                        'ID not found in the database.',
                                                        'error');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                            });

                                    }
                                });
                            } else if (result.isDismissed) {
                                swal.fire({
                                    title: "Cancelled",
                                    text: "Your imaginary data is safe :)",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
