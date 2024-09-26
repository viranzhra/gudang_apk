@extends('layouts.navigation')

@section('content')
    <style>
        .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .icon-edit,
        .icon-delete,
        .icon-detail {
            color: #ffffff;
            font-size: 18px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 5px;
        }

        .icon-detail {
            background-color: #112337;
        }

        .icon-edit {
            background-color: #01578d;
        }

        .icon-delete {
            background-color: #910a0a;
        }

        .btn-action:hover .icon-edit,
        .btn-action:hover .icon-delete {
            opacity: 0.8;
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Type Requirement</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
            <!-- tombol Add -->
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                data-bs-target="#tambahData" style="width: 75px; height: 35px;">
                <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                Add
            </a>

            <!-- tombol hapus pilihan -->
            <button id="deleteSelected" class="btn btn-danger d-none"
                style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                Delete Selected
            </button>
        </div>

        <table class="table table-bordered table-striped table-hover" id="keperluan-table" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>No</th>
                    <th>Type Requirement</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- modal tambah data -->
    <div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataLabel">Add Type Requirement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('keperluan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="tambah-type" class="form-label">Type Requirement</label>
                            <input type="text" id="tambah-type" name="type" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal edit data -->
    <div class="modal fade" id="editData" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataLabel">Edit Type Requirement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit-type" class="form-label">Type Requirement</label>
                            <input type="text" id="edit-type" name="type" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        // proses klik tombol edit
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var url = '{{ config('app.api_url') }}/keperluan/' + id; // sesuaikan URL agar sesuai dengan API

            // Get supplier data dari server
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    // mengisi kolom formulir
                    $('#editForm').attr('action', url);
                    $('#edit-type').val(data.type);

                    // show modal
                    $('#editData').modal('show');
                },
                error: function() {
                    alert('Error fetching supplier data.');
                }
            });
        });

        // Script untuk inisialisasi DataTable
        $(document).ready(function() {
            var table = $('#keperluan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ config('app.api_url') }}/keperluan',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    }
                },
                columns: [{
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="select-item" value="${data}">`;
                        }
                    },
                    {
                        data: null,
                        sortable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'requirement_type' // Ganti 'type' dengan nama kolom yang benar
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
            <div class="d-flex">
                <button data-id="${data}" class="btn-edit btn-action" aria-label="Edit">
                    <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                </button>
                <button data-id="${data}" class="btn-action" aria-label="Delete">
                    <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                </button>
            </div>
            `;
                        }
                    }
                ],
                order: [
                    [2, 'asc'] // Mengurutkan berdasarkan kolom ke-2 (type)
                ]
            });
        });
    </script>

    <script>
        // proses tombol Hapus
        $(document).on('click', '.btn-action[aria-label="Delete"]', function() {
            var id = $(this).data('id');
            var url = '{{ config('app.api_url') }}/keperluan/' + id;

            if (confirm('Are you sure you want to delete this data?')) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data successfully deleted.');
                            $('#keperluan-table').DataTable().ajax.reload();
                        } else {
                            alert('Failed to delete data.');
                        }
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
            }
        });
    </script>
@endsection
