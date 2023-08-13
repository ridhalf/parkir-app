@extends('index')
@push('breadcrumbs')
    <h1>Categories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Categories</a></li>
        </ol>
    </nav>
@endpush
@push('style')
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/datatables.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary btn-sm" id="category-add"><span> <i
                                class="bi bi-plus-lg"></i></span>Tambah Data</button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <!-- Table with stripped rows -->
                    <table class="table datatable" id="categories-table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Tarif/Jam</th>
                                <th scope="col">Aksi</th>
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
    <!-- Basic Modal -->

    <div class="modal fade" id="category-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <input type="hidden" id="id_category">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" id="name" name="name" class="form-control">
                            <small><span id="error-name" class="text-danger"></span></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="charge" class="col-sm-2 col-form-label">Charge</label>
                        <div class="col-sm-10">
                            <input type="number" id="charge" name="charge" class="form-control">
                            <small><span id="error-charge" class="text-danger"></span></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="category-close" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" id="category-save" class="btn btn-primary ">Save changes</button>
                </div>
            </div>
        </div>
    </div><!-- End Basic Modal-->
@endsection
@push('script')
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        "use strict"
        $(function() {
            let table = $("#categories-table").DataTable({
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
                    url: "{{ route('category.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        class: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        class: 'text-center'
                    },
                    {
                        data: 'charge',
                        render: $.fn.dataTable.render.number(',', '.', 2, 'Rp. '),
                        class: 'text-center'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        class: 'text-center'
                    }
                ],

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
        })
        $('body').on('click', '.category-edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $.ajax({
                url: "/category/" + id + "/edit",
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#category-modal").modal('show');
                    $("#name").val(response.result.name)
                    $("#charge").val(response.result.charge)
                    $("#id_category").val(id)
                }
            })
        })
        $("#category-save").click(function() {
            const id = $('#id_category').val()
            console.log(id);
            if (id) {
                save(id);
            } else {
                save()
            }
            $('#category-modal').modal('toggle');
        })
        $('body').on('click', '#category-add', function(e) {
            e.preventDefault();
            $("#id_category").val('')
            $('#category-modal').modal('show');
        })
        $('#category-close').click(function() {
            resetForm()
            // $('#category-modal').modal('toggle');
        })

        function resetForm() {
            $("#id_category").val('')
            $('#name').removeClass('is-invalid');
            $('#name').val('');
            $('#charge').removeClass('is-invalid');
            $('#charge').val('');
            $('#error-name').text('');
            $('#error-charge').text('');
            $('#category-modal').on('hidden.bs.modal');

        }

        function save(id = '') {
            let url;
            let type;
            if (id === '') {
                url = "{{ route('category.store') }}";
                type = "POST";
            } else {
                url = "/category/" + id;
                type = "PUT";
            }
            $.ajax({
                url: url,
                type: type,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    name: $("#name").val(),
                    charge: $("#charge").val()
                },
                success: function(response) {
                    console.log(response);
                    if (response.errors) {
                        $('#name').removeClass('is-invalid')
                        $('#charge').removeClass('is-invalid')
                        $('#error-name').text('')
                        if (response.errors.name) {
                            $('#name').addClass('is-invalid')
                            $('#error-name').text(response.errors.name)
                        }
                        if (response.errors.charge) {
                            $('#charge').addClass('is-invalid')
                            $('#error-charge').text(response.errors.charge)
                        }
                    } else {
                        resetForm()
                        $("#categories-table").DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Tersimpan!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Tutup'
                        })
                    }
                }
            })
        }
    </script>
@endpush
