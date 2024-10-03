@extends('layouts.navigation')

@section('content')
    <style>
        /* Styling untuk tombol dan ikon */
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
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                data-bs-target="#tambahData" style="width: 75px; height: 35px;">
                <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                Add
            </a>

            <button id="deleteSelected" class="btn btn-danger d-none"
                style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                Delete Selected
            </button>
        </div>

        <table class="table table-bordered table-striped table-hover" id="KeperluanTable" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 20px"><input type="checkbox" id="select-all"></th>
                    <th style="width: 25px;">No</th>
                    <th>Type Requirement</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataLabel">Add Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="post" action="{{ route('keperluan.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Type Requirement</label>
                            <input type="text" id="nama" name="nama" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editData" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataLabel">Edit Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Type Requirement</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: black">
                    Apakah Anda yakin ingin menghapus Jenis Keperluan <span id="typeName"></span> ini?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="background-color: #910a0a">Delete</button>
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

    <!-- Script untuk inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            const table = $('#KeperluanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'https://doaibutiri.my.id/gudang/api/keperluan',
                    headers: {
                        'Authorization': 'Bearer ' +
                            '{{ session('token') }}' // Make sure the token is set correctly
                    },
                    dataSrc: 'data' // Ensure this matches your API response structure
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
                        data: 'nama'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex">
                                <button class="btn-edit btn-action" data-id="${data}" data-nama="${row.nama}" data-bs-toggle="modal" data-bs-target="#editData" aria-label="Edit">
                                    <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                                </button>
                                <button class="btn-action delete-btn" data-id="${data}" data-nama="${row.nama}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                                </button>
                            </div>
                        `;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // Handle select-all checkbox
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // Handle individual checkboxes
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Function to toggle the delete button based on selection
            function toggleDeleteButton() {
                const selectedCount = $('.select-item:checked').length;
                $('#deleteSelected').toggleClass('d-none', selectedCount === 0);
            }

            // Handle delete button click
            $('#deleteSelected').on('click', function() {
                const selectedIds = $('.select-item:checked').map(function() {
                    return $(this).val();
                }).get();
                // Send delete request for selected IDs
                // You can implement a delete API request here as needed
            });

            // Handle add form submission
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    data: formData,
                    success: function(response) {
                        $('#tambahData').modal('hide');
                        table.ajax.reload();
                        alert('Data added successfully!');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error adding data: ' + (xhr.responseJSON?.message || xhr
                            .responseText));
                    }
                });
            });

            // Handle edit form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    data: formData,
                    success: function(response) {
                        $('#editData').modal('hide');
                        table.ajax.reload();
                        alert('Data updated successfully!');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error updating data: ' + (xhr.responseJSON?.message || xhr
                            .responseText));
                    }
                });
            });

            // Handle delete button click to open modal
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('nama');
                $('#deleteForm').attr('action',
                `{{ url('keperluan') }}/${id}`); // Set action for delete form
                $('#typeName').text(name); // Show the name in the modal
            });

            // Handle delete form submission
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                const actionUrl = $(this).attr('action');
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                        alert('Data deleted successfully!');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error deleting data: ' + (xhr.responseJSON?.message || xhr
                            .responseText));
                    }
                });
            });

            // Handle edit button click to populate modal with data
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const name = $(this).data('nama');
                $('#editForm').attr('action', `{{ url('keperluan') }}/${id}`); // Set action for edit form
                $('#edit-nama').val(name); // Populate the edit input with the current name
            });
        });
    </script>
@endsection
