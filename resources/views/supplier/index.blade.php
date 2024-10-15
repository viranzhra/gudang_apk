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

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            height: 80px;
        }

        .table-ellipsis {
            display: inline-block;
            max-width: 330px;
            /* Atur sesuai kebutuhan */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                    <form id="addSupplierForm" method="post" action="{{ route('supplier.store') }}"
                        enctype="multipart/form-data">
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
                            <textarea id="tambah-keterangan" name="keterangan" class="form-control"></textarea>
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
                            <textarea name="keterangan" id="edit-keterangan" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Edit</button>
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

    <!-- Modal Detail Data -->
    <div class="modal fade" id="detailData" tabindex="-1" aria-labelledby="detailDataLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailDataLabel">Detail Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-3"><strong>Supplier:</strong></div>
                        <div class="col-8"><span class="detail-nama"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><strong>Address:</strong></div>
                        <div class="col-8"><span class="detail-alamat"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><strong>Phone:</strong></div>
                        <div class="col-8"><span class="detail-telepon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-3"><strong>Description:</strong></div>
                        <div class="col-8"><span class="detail-keterangan"></span></div>
                    </div>
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

    {{-- detail --}}
    <script>
        $(document).on('click', '.btn-detail', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // mengambil data customer menggunakan API
            $.ajax({
                url: `{{ config('app.api_url') }}/suppliers/${id}`,
                type: 'GET',
                success: function(data) {
                    // sesuaikan dengan yang di modal
                    $('.detail-nama').text(data.nama);
                    $('.detail-alamat').text(data.alamat);
                    $('.detail-telepon').text(data.telepon);
                    $('.detail-keterangan').text(data.keterangan);

                    // perbarui judul dan menampilkan modal
                    $('#detailDataLabel').text('Detail Supplier');
                    $('#detailData').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch supplier data:', xhr.responseText);
                    alert('Failed to fetch supplier data.');
                }
            });
        });
    </script>

    {{-- konfirmasi hapus --}}
    <script>
        $(document).ready(function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let deleteUrl;
            let supplierId;

            // Clean phone number function
            function cleanPhoneNumber(phoneField) {
                var phoneValue = phoneField.val().trim();
                phoneValue = phoneValue.replace(/\D/g, ''); // Remove non-numeric characters
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

            // Automatically clean phone number on click (for both add and edit form)
            $('#tambah-telepon, #edit-telepon').on('focus', function() {
                cleanPhoneNumber($(this));
            });

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

                $('#confirmDelete').modal('show'); // Tampilkan modal konfirmasi
                $('#confirmDeleteButton').off('click').on('click', function() {
                    $.ajax({
                        url: `{{ config('app.api_url') }}/suppliers/delete-selected`, // Endpoint penghapusan
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
                                    'Selected data was successfully delected!');
                                $('#confirmDelete').modal('hide');
                                $('#supplier-table').DataTable().ajax.reload();
                            } else {
                                showNotification('error',
                                    'Gagal menghapus supplier terpilih.');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
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
            $('form[id="addSupplierForm"]').on('submit', function(e) {
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

                // Sending the data via AJAX to the API
                $.ajax({
                    url: '{{ config('app.api_url') }}/suppliers', // Use AJAX to avoid issues with POST
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Debugging line

                        // Set notification message based on controller response
                        if (response.success) {
                            // If successful, show success message
                            showNotification('success', response.message);
                        } else {
                            // If not successful, show error message
                            showNotification('error', response.message ||
                                'Gagal menambahkan supplier.');
                        }
                    },
                    error: function(xhr) {
                        // Handle AJAX errors
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menambahkan supplier.';
                        showNotification('error', message); // Show error message
                    },
                    complete: function() {
                        // Hide the modal after a delay
                        setTimeout(function() {
                            $('#tambahData').modal('hide'); // Hide modal
                            $('#supplier-table').DataTable().ajax
                                .reload(); // Reload DataTable if needed
                        }, 1000); // Delay of 1 second before hiding the modal

                        $submitButton.prop('disabled', false).html('Save'); // Re-enable button
                    }
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
                        data: 'nama',
                        render: function(data) {
                            return `<span class="table-ellipsis" title="${data}">${data}</span>`;
                        }
                    },
                    {
                        data: 'alamat',
                        render: function(data) {
                            return `<span class="table-ellipsis" title="${data}">${data}</span>`;
                        }
                    },
                    {
                        data: 'telepon',
                        render: function(data) {
                            return `<span class="table-ellipsis" title="${data}">${data}</span>`;
                        }
                    },
                    {
                        data: 'keterangan',
                        render: function(data) {
                            if (!data) {
                                return ''; // Tampilkan kosong jika data null atau undefined
                            }
                            return `<span class="table-ellipsis" title="${data}">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<div class="d-flex">
                                <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                    <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                </button>
                                <button data-id="${data}" class="btn-edit btn-action" aria-label="Edit">
                                    <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                                </button>
                                <button data-id="${data}" class="btn-delete btn-action" aria-label="Delete">
                                    <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                                </button>
                                </div>`;
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
