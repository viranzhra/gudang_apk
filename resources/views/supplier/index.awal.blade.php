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
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteLabel" aria-hidden="true">
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
            let supplierId; // Menyimpan supplierId di luar scope

            $(document).on('click', '.btn-delete', function() {
                supplierId = $(this).data('id'); // Simpan supplierId
                const supplierName = $(this).closest('tr').find('td:nth-child(3)').text();

                // Tampilkan nama pelanggan di modal
                $('#supplierName').text(supplierName);

                // Tentukan URL untuk delete API
                deleteUrl = `{{ config('app.api_url') }}/suppliers/${supplierId}`;

                // Update action form
                $('#deleteForm').attr('action', deleteUrl);

                // Tampilkan modal
                deleteModal.show();
            });

            // Event listener untuk tombol konfirmasi hapus
            $('#deleteForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form default
                console.log('Mengirim permintaan DELETE ke:', $(this).attr('action')); // Debug URL
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                $('#tambah-telepon').on('focus', function() {
                    var phoneValue = $(this).val().trim();
                    // cek apakah nomer di awali dengan "+62" atau "(+62)"
                    if (phoneValue.startsWith('(+62)')) {
                        // hapus (+62) dan ganti dengan "62" tanpa spasi
                        phoneValue = '62' + phoneValue.slice(5).replace(/\s+/g, '');
                    } else if (phoneValue.startsWith('+62')) {
                        phoneValue = '62' + phoneValue.slice(3).replace(/\s+/g, '');
                    } else {
                        // menjadikan nomer biasa dengan menghilangkan spasi
                        phoneValue = phoneValue.replace(/\s+/g, '');
                    }
                    $(this).val(phoneValue);
                });

                // form modal tambah
                $('form').submit(function(e) {
                    var phoneField = $('#tambah-telepon');
                    var phoneValue = phoneField.val().trim();
                    var errorField = $('#error-tambahtelepon');

                    // error message
                    errorField.text('').hide();

                    // menampilkan pesan eror jika ada selain angka
                    if (/[^0-9]/.test(phoneValue)) {
                        errorField.text(
                                'Phone number cannot contain spaces, parentheses, or plus signs.')
                            .show();
                        return false;
                    }

                    // proses menghapus spasi sebelum kirim data
                    if (phoneValue.startsWith('62')) {
                        phoneValue = '62' + phoneValue.slice(2).replace(/\s+/g, '');
                    } else {
                        phoneValue = phoneValue.replace(/\s+/g, '');
                    }

                    phoneField.val(phoneValue);
                });
            });
            // proses klik tombol edit
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                var url = '{{ config('app.api_url') }}/suppliers/' +
                    id; // sesuaikan URL agar sesuai dengan API

                // Get supplier data dari server
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        // mengisi kolom formulir
                        $('#editForm').attr('action', url);
                        $('#edit-nama').val(data.nama);
                        $('#edit-alamat').val(data.alamat);
                        $('#edit-telepon').val(data.telepon);
                        $('#edit-keterangan').val(data.keterangan);

                        // show modal
                        $('#editData').modal('show');
                    },
                    error: function() {
                        alert('Error fetching supplier data.');
                    }
                });
            });

            // proses form
            $('#edit-telepon').on('focus', function() {
                var phoneValue = $(this).val().trim();

                // cek apakah nilai dimulai dari "+62" atau "(+62)"
                if (phoneValue.startsWith('(+62)')) {
                    // hapus (+62) dan ganti dengan "62" tanpa spasi
                    phoneValue = '62' + phoneValue.slice(5).replace(/\s+/g, '');
                } else if (phoneValue.startsWith('+62')) {
                    phoneValue = '62' + phoneValue.slice(3).replace(/\s+/g, '');
                } else {
                    // menangani nomor biasa dengan menghilangkan spasi
                    phoneValue = phoneValue.replace(/\s+/g, '');
                }

                // mengatur nilai yang sudah dibersihkan
                $(this).val(phoneValue);
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var phoneField = $('#edit-telepon');
                var phoneValue = phoneField.val().trim();
                var errorField = $('#error-edittelepon');

                // reset pesan error
                errorField.text('').hide();

                // menampilkan pesan kesalahan
                if (/[^0-9]/.test(phoneValue)) {
                    errorField.text(
                            'Phone number cannot contain spaces, parentheses, or plus signs.')
                        .show();
                    return false;
                }

                // menghapus spasi sebelum submit
                if (phoneValue.startsWith('62')) {
                    phoneValue = '62' + phoneValue.slice(2).replace(/\s+/g, '');
                } else {
                    phoneValue = phoneValue.replace(/\s+/g, '');
                }

                phoneField.val(phoneValue); // mengatur nilai yang sudah dihapus agar kembali ke awal

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: form.serialize(),
                    success: function(response) {
                        // Close modal
                        $('#editData').modal('hide');

                        // Refresh DataTable
                        $('#supplier-table').DataTable().ajax.reload();

                        // menampilkan pesan sukses
                        alert('Supplier updated successfully.');
                    },
                    error: function() {
                        errorField.text('Error updating supplier.').show();
                    }
                });
            });
        });
    </script>

    <script>
        // proses tombol Hapus
        $(document).on('click', '.btn-action[aria-label="Delete"]', function() {
            var id = $(this).data('id');
            var url = '{{ config('app.api_url') }}/suppliers/' + id;

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
                            $('#supplier-table').DataTable().ajax.reload();
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

    <!-- Script untuk inisialisasi DataTable -->
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                var table = $('#supplier-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ config('app.api_url') }}/suppliers',
                        data: function(d) {
                            // d.search = $('input[name=search]').val();
                        },
                        headers: {
                            'Authorization': 'Bearer ' + '{{ session('token') }}'
                        }
                    },
                    columns: [{
                            data: 'id',
                            orderable: false, // mematikan fungsi pengurutan di kolom checkbox
                            render: function(data, type, row) {
                                return `<input type="checkbox" class="select-item flex justify-center items-center" value="${data}">`;
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
                            render: function(data, type, row) {
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
                        [2, 'asc']
                    ]
                });
            });

            // menangani semua kotak centang
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // menangani masing-masing kotak centang
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // fungsi untuk mengalihkan tombol hapus berdasarkan pilihan
            function toggleDeleteButton() {
                const selected = $('.select-item:checked').length;
                const deleteButton = $('#deleteSelected');
                if (selected > 0) {
                    deleteButton.removeClass('d-none');
                } else {
                    deleteButton.addClass('d-none');
                }
            }

            // klik tombol hapus di tabel
            $(document).on('click', '#deleteSelected', function() {
                const selected = [];
                $('.select-item:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('Are you sure you want to delete the selected data?')) {
                        $.ajax({
                            url: '{{ config('app.api_url') }}/suppliers/delete-selected', // route untuk menghapus
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF Token
                            },
                            data: {
                                ids: selected
                            },
                            success: function(response) {
                                if (response.success) {
                                    // refresh DataTable
                                    $('#supplier-table').DataTable().ajax.reload();

                                    // Uncheck all checkboxes
                                    $('#select-all').prop('checked', false);
                                    $('.select-item').prop('checked', false);

                                    // Hide delete button
                                    $('#deleteSelected').addClass('d-none');

                                    // Optional: Show success alert
                                    alert('Selected data successfully deleted.');
                                } else {
                                    alert('Failed to delete data.');
                                }
                            },
                            error: function() {
                                alert('Something went wrong.');
                            }
                        });
                    }
                } else {
                    alert('No data selected.');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function untuk menampilkan notifikasi
            function showNotification(type, message) {
                let notificationTitle = '';
                let notificationClass = '';

                //  Mengatur judul dan kelas berdasarkan tipe notifikasi
                switch (type) {
                    case 'success':
                        notificationTitle = 'Success!';
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

                // mengatur konten notifikasi
                $('#notificationTitle').text(notificationTitle);
                $('#notificationMessage').text(message);
                $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass);

                // menampilkan notifikasi
                $('#notification').fadeIn();

                // menyembunyikan notifikasi setelah 3 detik
                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }

            $(document).on('click', '#deleteSelected', function() {
                const selected = [];
                $('.select-item:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    // Tampilkan modal konfirmasi
                    $('#confirmDelete').modal('show');
                    // Simpan daftar ID yang akan dihapus
                    $('#confirmDeleteButton').data('ids', selected);
                } else {
                    showNotification('error', 'Tidak ada data yang dipilih.');
                }
            });

            // konfirmasi button hapus yang terpilih
            $(document).on('click', '#confirmDeleteButton', function() {
                const selected = $(this).data('ids');

                fetch('{{ config('app.api_url') }}/suppliers/delete-selected', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selected
                    })
                }).then(response => {
                    if (response.ok) {
                        showNotification('success', 'The selected data was successfully delected!');
                        $('#supplier-table').DataTable().ajax.reload(); // Reload DataTable
                    } else {
                        showNotification('error', 'Gagal menghapus data.');
                    }
                    $('#confirmDelete').modal('hide');
                    // Sembunyikan tombol "Hapus Terpilih"
                    $('#deleteSelected').hide();
                }).catch(() => {
                    showNotification('error', 'Terjadi kesalahan saat menghapus data.');
                    $('#confirmDelete').modal('hide');
                });
            });

            // untuk notifikasi form tambah data
            $('form[action="{{ route('supplier.store') }}"]').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize(); // Mengambil data dari form
                let $submitButton = $(this).find(
                'button[type="submit"]'); // Menyimpan referensi tombol submit

                // Disable tombol dan tampilkan loading
                $submitButton.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i>');

                // Mengirimkan data melalui AJAX
                $.post($(this).attr('action'), formData, function(response) {
                    if (response.success) {
                        showNotification('success', response.message); // Menampilkan pesan sukses
                        $('#tambahData').modal('hide'); // Menutup modal
                        $('#supplier-table').DataTable().ajax.reload(); // Reload tabel

                        // Reset form setelah berhasil submit
                        $('form[action="{{ route('supplier.store') }}"]')[0].reset();
                    } else {
                        showNotification('error', response.message); // Menampilkan pesan error
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', jqXHR.responseText);
                    showNotification('error',
                    'Terjadi kesalahan saat menambahkan supplier.'); // Menampilkan pesan error
                }).always(function() {
                    // Enable kembali tombol submit dan kembalikan teks aslinya
                    $submitButton.prop('disabled', false).html('Save');
                });
            });

            // Reset form ketika modal ditutup
            $('#tambahData').on('hidden.bs.modal', function() {
                $('form[action="{{ route('supplier.store') }}"]')[0].reset();
            });

            // untuk notifikasi edit data
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let id = $('#edit-id').val();
                $.ajax({
                    url: `{{ config('app.api_url') }}/suppliers/${id}`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', 'Supplier data successfully updated!');
                            $('#editDataModal').modal('hide');
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

            // untuk notifikasi delete
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            showNotification('success', 'Supplier data successfully deleted!');
                            $('#deleteModal').modal('hide');
                            $('#supplier-table').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Gagal menghapus supplier.');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan saat menghapus supplier.');
                    }
                });
            });
        });
    </script>
@endsection
