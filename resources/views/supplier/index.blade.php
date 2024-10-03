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

        #notification {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            padding: 15px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            text-align: center;
            justify-content: flex-start;
            /* Tetap di sebelah kiri */
            align-items: center;
            text-align: left;
            /* Teks tetap rata kiri */
            /* Hidden by default */
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            height: 80px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            height: 80px;
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>
        <h4 class="mt-3" style="color: #8a8a8a;">Supplier Management</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
            <!-- tombol Add -->
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#tambahData" style="width: 75px; height: 35px;">
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

        <table class="table table-bordered table-striped table-hover" id="supplier-table" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>No</th>
                    <th>Supplier</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Description</th>
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
                    <h5 class="modal-title" id="tambahDataLabel">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('supplier.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="tambah-nama" class="form-label">Supplier</label>
                            <input type="text" id="tambah-nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="tambah-alamat" class="form-label">Address</label>
                            <input type="text" id="tambah-alamat" name="alamat" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="tambah-telepon" class="form-label">Phone</label>
                            <input type="text" id="tambah-telepon" name="telepon" class="form-control">
                            <small id="error-tambahtelepon" class="text-danger" style="display: none;"></small>
                            <!-- error message -->
                        </div>
                        <div class="mb-3">
                            <label for="tambah-keterangan" class="form-label">Description</label>
                            <input type="text" id="tambah-keterangan" name="keterangan" class="form-control">
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
                    <h5 class="modal-title" id="editDataLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Supplier</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit-alamat" class="form-label">Address</label>
                            <input type="text" id="edit-alamat" name="alamat" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit-telepon" class="form-label">Phone</label>
                            <input type="text" id="edit-telepon" name="telepon" class="form-control">
                            <small id="error-edittelepon" class="text-danger" style="display: none;"></small>
                            <!-- error message -->
                        </div>
                        <div class="mb-3">
                            <label for="edit-keterangan" class="form-label">Description</label>
                            <input type="text" id="edit-keterangan" name="keterangan" class="form-control">
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
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: black">
                    Are you sure you want to delete <span id="supplierName"></span>?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn"
                            style="background-color: #910a0a; color: white;">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan dipilih -->
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel">Confirm deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the selected data?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #910a0a; color: white;"
                        id="confirmDeleteButton">Delete</button>
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

    {{-- konfirmasi hapus --}}
    <script>
        $(document).ready(function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let deleteUrl;
            let supplierId;

            // Clean phone number function
            function cleanPhoneNumber(phoneField) {
                var phoneValue = phoneField.val().trim();
                phoneValue = phoneValue.replace(/\D/g, '');
                phoneField.val(phoneValue);
            }

            // Validate phone number
            function validatePhoneNumber(phoneField, errorField) {
                var phoneValue = phoneField.val().trim();

                // Check for non-numeric characters
                if (/[^0-9]/.test(phoneValue)) {
                    errorField.text('Phone number cannot contain spaces, parentheses, or plus signs.').show();
                    return false;
                } else {
                    errorField.text('').hide();
                }

                // Clean the phone number
                phoneValue = phoneValue.replace(/\D/g, '');
                phoneField.val(phoneValue);
                return true;
            }

            // Delete button event listener for individual delete
            $(document).on('click', '.btn-delete', function() {
                supplierId = $(this).data('id');
                const supplierName = $(this).closest('tr').find('td:nth-child(3)').text();
                $('#supplierName').text(supplierName);
                deleteUrl = `{{ config('app.api_url') }}/suppliers/${supplierId}`;
                $('#deleteForm').attr('action', deleteUrl);
                deleteModal.show();
            });

            // Confirm delete form submission for individual delete
            $('#deleteForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}',
                        'Content-Type': 'application/json'
                    },
                    success: function(data) {
                        if (data.success) {
                            showNotification('success', 'Supplier berhasil dihapus!');
                            deleteModal.hide();
                            $('#supplier-table').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Gagal menghapus supplier.');
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menghapus supplier.';
                        showNotification('error', message);
                    }
                });
            });

            // Delete selected suppliers
            $('#deleteSelected').on('click', function() {
                let selectedIds = [];
                $('.select-item:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    showNotification('error', 'Pilih setidaknya satu supplier untuk dihapus.');
                    return;
                }

                // Show confirmation modal
                $('#deleteSelectedModal').modal('show');

                // Confirm delete selected suppliers
                $('#confirmDeleteSelected').off('click').on('click', function() {
                    $.ajax({
                        url: `{{ config('app.api_url') }}/suppliers/delete`, // Adjust the endpoint as necessary
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + '{{ session('token') }}',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            ids: selectedIds
                        }),
                        success: function(data) {
                            if (data.success) {
                                showNotification('success',
                                    'Supplier terpilih berhasil dihapus!');
                                $('#deleteSelectedModal').modal('hide');
                                $('#supplier-table').DataTable().ajax.reload();
                            } else {
                                showNotification('error',
                                    'Gagal menghapus supplier terpilih.');
                            }
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menghapus supplier terpilih.';
                            showNotification('error', message);
                        }
                    });
                });
            });

            // Edit button event listener
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                var url = `{{ config('app.api_url') }}/suppliers/${id}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#editForm').attr('action', url);
                        $('#edit-nama').val(data.nama);
                        $('#edit-alamat').val(data.alamat);
                        $('#edit-telepon').val(data.telepon);
                        $('#edit-keterangan').val(data.keterangan);
                        $('#editData').modal('show');
                    },
                    error: function() {
                        showNotification('error', 'Error fetching supplier data.');
                    }
                });
            });

            // Edit form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var phoneField = $('#edit-telepon');
                var errorField = $('#error-edittelepon');

                if (!validatePhoneNumber(phoneField, errorField)) {
                    return false;
                }

                $.ajax({
                    url: form.attr('action'),
                    type: 'PUT',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', 'Supplier berhasil diperbarui!');
                            $('#editData').modal('hide');
                            $('#supplier-table').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Gagal memperbarui supplier.');
                        }
                    },
                    error: function() {
                        showNotification('error',
                            'Terjadi kesalahan saat memperbarui supplier.');
                    }
                });
            });

            // Add supplier form submission
            $('form[action="{{ route('supplier.store') }}"]').on('submit', function(e) {
                e.preventDefault();
                var phoneField = $('#tambah-telepon');
                var errorField = $('#error-tambahtelepon');

                // Validate the phone number
                if (!validatePhoneNumber(phoneField, errorField)) {
                    return false; // Stop submission if validation fails
                }

                let formData = $(this).serialize();
                let $submitButton = $(this).find('button[type="submit"]');

                // Disable the submit button to prevent multiple clicks
                $submitButton.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i>');

                // Sending the data via AJAX
                $.post($(this).attr('action'), formData)
                    .done(function(response) {
                        console.log(response); // Debugging line
                        // Check for success in the response
                        if (response && response.success) {
                            showNotification('success', response.message);
                            $('#tambahData').modal('hide');
                            $('#supplier-table').DataTable().ajax.reload(); // Reload the DataTable
                        } else {
                            // Handle unexpected response structure
                            showNotification('error', response?.message ||
                                'Gagal menambahkan supplier.');
                        }
                    })
                    .fail(function(xhr) {
                        // Handle AJAX errors
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menambahkan supplier.';
                        showNotification('error', message);
                    })
                    .always(function() {
                        // Re-enable the submit button
                        $submitButton.prop('disabled', false).html('Save');
                    });
            });

            // Reset add form on modal close
            $('#tambahData').on('hidden.bs.modal', function() {
                $('form[action="{{ route('supplier.store') }}"]')[0].reset();
                $('#error-tambahtelepon').hide();
            });

            // DataTable initialization
            var table = $('#supplier-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ config('app.api_url') }}/suppliers',
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
                        data: 'nama'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<div class="d-flex"><button data-id="${data}" class="btn-edit btn-action" aria-label="Edit"><iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon></button><button data-id="${data}" class="btn-delete btn-action" aria-label="Delete"><iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon></button></div>`;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // select-all checkbox
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // individual checkboxes
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Fungsi untuk mengaktifkan atau menonaktifkan tombol hapus berdasarkan item yang dipilih
            function toggleDeleteButton() {
                const selected = $('.select-item:checked').length;
                const deleteButton = $('#deleteSelected');
                if (selected > 0) {
                    deleteButton.removeClass('d-none');
                } else {
                    deleteButton.addClass('d-none');
                }
            }

            // Notification function
            function showNotification(type, message) {
                let notificationTitle = '';
                let notificationClass = '';

                switch (type) {
                    case 'success':
                        notificationTitle = 'Sukses!';
                        notificationClass = 'alert-success';
                        break;
                    case 'error':
                        notificationTitle = 'Error!';
                        notificationClass = 'alert-danger';
                        break;
                    default:
                        notificationTitle = 'Notification';
                        notificationClass = 'alert-info';
                }

                $('#notificationTitle').text(notificationTitle);
                $('#notificationMessage').text(message);
                $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass)
                    .fadeIn();

                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }
        });
    </script>
@endsection
