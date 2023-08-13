@extends('index')
@push('breadcrumbs')
    <h1>Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Parkir</a></li>
        </ol>
    </nav>
@endpush
@push('style')
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Checkout Kendaraan</h5>
                    <!-- General Form Elements -->
                    <div class="row mb-3">
                        <label for="parking_code" class="col-sm-4 col-form-label">Kode</label>
                        <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon3">PKR</span>
                                <input type="text" class="form-control" id="parking_code"
                                    aria-describedby="basic-addon3">
                            </div>
                            <small><span class="text-danger" id="error-parking-code"></span></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-8 col-form-label"></label>
                        <div class="col-sm-4">
                            <button type="submit" id="btn-checkout" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Parkir Hari Ini</h5>
                    <!-- Table with stripped rows -->
                    <table class="table datatable" id="parking-table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Parkir</th>
                                <th scope="col">Tanggal Masuk</th>
                                <th scope="col">Jam Masuk</th>
                                <th scope="col">Tanggal Keluar</th>
                                <th scope="col">Jam Keluar</th>
                                <th scope="col">Durasi</th>
                                <th scope="col">Total Bayar</th>
                                <th scope="col">No Polisi</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        "use strict"
        $(function() {
            let table = $('#parking-table').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                pageLength: 10,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('parking.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        status: 'OUT'
                    }

                },
                columns: [{
                        name: 'no',
                        data: 'no'
                    },
                    {
                        name: 'parking_code',
                        data: 'parking_code'
                    },
                    {
                        name: 'date_in',
                        data: 'date_in'
                    },
                    {
                        name: 'check_in',
                        data: 'check_in'
                    },
                    {
                        name: 'date_out',
                        data: 'date_out'
                    },
                    {
                        name: 'check_out',
                        data: 'check_out'
                    },
                    {
                        name: 'duration',
                        data: 'duration',
                        render: function(data) {
                            return `${data} Jam`
                        }

                    },
                    {
                        name: 'total_payment',
                        data: 'total_payment',
                        render: $.fn.dataTable.render.number(',', '.', 2, 'Rp. '),
                    },
                    {
                        name: 'no_police',
                        data: 'no_police'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'status',
                        data: 'status',
                        render: function(data, type) {
                            return `<span class="badge bg-primary">${data}</span>`
                        }
                    },
                ],
                columnDefs: [{
                    'sortable': false,
                    'searchable': false,
                    'targets': [0, -1]
                }, {
                    'sortable': false,
                    'searchable': true,
                    'targets': [-2]
                }],
                order: [
                    [1, 'asc']
                ]
            })
            table.on('draw.dt', function() {
                let info = table.page.info();
                table.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + info.start;
                });
            });
            $('body').on('click', '#btn-checkout', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('parking.update') }}',
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        parking_code: $('#parking_code').val()
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.errors) {
                            $('#error-parking-code').text(response.errors.parking_code)
                        } else {
                            $("#parking-table").DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Tersimpan!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'Tutup'
                            })
                        }
                    }
                })
            })
        })
    </script>
@endpush
