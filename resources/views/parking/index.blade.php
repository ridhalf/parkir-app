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
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-4">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tambah Kendaraan</h5>
                    <!-- General Form Elements -->
                    <div class="row mb-3">
                        <label for="no-police" class="col-sm-4 col-form-label">No Polisi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="no-police">
                            <small><span class="text-danger" id="error-no-police"></span></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Kategori</label>
                        <div class="col-sm-8 col-xs-8">
                            <select class="form-select" aria-label="Default select example" id="category">
                            </select>
                            <small><span class="text-danger" id="error-category"></span></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-8 col-form-label"></label>
                        <div class="col-sm-4">
                            <button type="submit" id="add-parking" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Parkir Hari Ini</h5>
                    <!-- Table with stripped rows -->
                    <table class="table datatable" id="parking-table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Parkir</th>
                                <th scope="col">No Polisi</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Jam Masuk</th>
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
                        status: 'IN'
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
                        name: 'no_police',
                        data: 'no_police'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'check_in',
                        data: 'check_in'
                    },

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

            $(document).ready(function() {
                $('#category').select2();
                $.ajax({
                    url: "{{ route('get-all-categories') }}",
                    type: 'GET',
                    success: function(response) {

                        let option = '<option selected value="">Category</option>';
                        for (let i = 0; i < response.result.length; i++) {
                            console.log(response.result[i]);
                            option +=
                                `<option value="${response.result[i].id}">${response.result[i].name}</option>`;
                        }
                        $('#category').append(option);
                    }
                })
            })
            $('body').on('click', '#add-parking', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('parking.store') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        no_police: $('#no-police').val(),
                        category: $('#category').val()
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.errors) {
                            if (response.errors.no_police) {
                                $('#no-police').addClass('is-invalid');
                                $('#error-no-police').text(response.errors.no_police)
                            }
                            if (response.errors.category) {
                                $('#category').addClass('is-invalid');
                                $('#error-category').text(response.errors.category);
                            }
                        } else {
                            resetForm()
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

            function resetForm() {
                $('#no-police').removeClass('is-invalid');
                $('#no-police').val('');
                $('#error-no-police').text('')
                $('#category').removeClass('is-invalid');
                $('#category').val('');
                $('#error-category').text('');
            }

        })
    </script>
@endpush
