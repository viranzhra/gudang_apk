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
        <h4 class="mt-3" style="color: #8a8a8a;">Requirement Type</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#tambahData" style="width: 75px; height: 35px;">
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
                    <th style="width: 25px">No</th>
                    <th style="width: 450px">Requirement Type</th>
                    <th>2 Dates?</th>
                    <th style="width: 50px">Action</th>
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
                    <form id="addForm" method="post" action="{{ route('keperluan.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Requirement Type</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Untuk Dipinjam" required />
                        </div>
                        <div class="mb-3">
                            <input id="extend" type="checkbox" name="extend" class="form-check-input">
                            <label for="extend" class="form-check-label">Extend</label>
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
                    <form id="editForm" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id" name="id" />
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Requirement Type</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <input id="extend" type="checkbox" name="extend" class="form-check-input">
                            <label for="extend" class="form-check-label">Extend</label>
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
                    Are you sure you want to delete <span id="typeName"></span>?
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

    <script>
        // Menghandle pengiriman form
        $('#addForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Menyiapkan data untuk dikirim
            var formData = {
                _token: '{{ csrf_token() }}', // Pastikan CSRF token disertakan
                nama: $('#nama').val(),
                nama_tanggal_akhir: $('#setDates').is(':checked') ? $('#end_date').val() :
                '', // Kirim string kosong bukan null
                extend: $('#setDates').is(':checked') ? true : false // Memastikan boolean true/false
            };

            // Validasi: Pastikan nama tidak kosong
            if (!formData.nama) {
                showNotification('error', 'Nama jenis barang harus diisi.');
                return; // Hentikan pengiriman jika nama kosong
            }

            // Validasi: Pastikan nama_tanggal_akhir diisi jika extend true
            if (formData.extend && !formData.nama_tanggal_akhir) {
                showNotification('error', 'Nama tanggal akhir harus diisi saat extend dicentang.');
                return; // Hentikan pengiriman jika tidak ada nama_tanggal_akhir
            }

            // AJAX request untuk mengirim data
            $.ajax({
                url: 'https://doaibutiri.my.id/gudang/api/keperluan', // Pastikan URL API yang benar
                method: 'POST',
                contentType: 'application/json', // Mengirim data sebagai JSON
                dataType: 'json', // Mengharapkan respons dalam format JSON
                data: JSON.stringify(formData), // Mengubah formData menjadi string JSON
                success: function(response) {
                    // Tampilkan notifikasi jika sukses
                    if (response.success) {
                        showNotification('success', 'Data berhasil ditambahkan.');
                        $('#tambahData').modal('hide'); // Tutup modal
                        $('#addForm')[0].reset(); // Reset form setelah pengiriman
                        $('#KeperluanTable').DataTable().ajax.reload(); // Reload DataTable
                    } else {
                        showNotification('error', response.message ||
                            'Gagal menambahkan type requirement.');
                    }
                },
                error: function(xhr) {
                    // Tampilkan notifikasi jika terjadi error
                    console.error('Status:', xhr.status); // Log status code
                    console.error('Response Text:', xhr.responseText); // Log response text

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Tampilkan pesan error spesifik dari server
                        let errors = xhr.responseJSON.errors;
                        let message = errors.extend ? errors.extend[0] :
                            'Terjadi kesalahan saat menambahkan data.';
                        showNotification('error', message);
                    } else {
                        showNotification('error', 'Terjadi kesalahan pada jaringan.');
                    }
                }
            });
        });

        // Menangani perubahan checkbox untuk tanggal
        $('#setDates').change(function() {
            if ($(this).is(':checked')) {
                $('#dateFields').show(); // Menampilkan field tanggal saat checkbox dicentang
            } else {
                $('#dateFields').hide(); // Menyembunyikan field tanggal saat checkbox tidak dicentang
                $('#start_date').val(''); // Kosongkan nilai field tanggal saat disembunyikan
                $('#end_date').val(''); // Kosongkan nilai field tanggal akhir
            }
        });

        // Fungsi untuk menampilkan notifikasi
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
            $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass).fadeIn();

            setTimeout(function() {
                $('#notification').fadeOut();
            }, 3000); // Notifikasi menghilang setelah 3 detik
        }
    </script>


    <!-- Script untuk inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let deleteUrl;
            let typeId;

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

            // Delete button event listener for individual delete
            $(document).on('click', '.btn-delete', function() {
                typeId = $(this).data('id');
                const typeName = $(this).closest('tr').find('td:nth-child(3)').text();
                $('#typeName').text(typeName);
                deleteUrl = `{{ config('app.api_url') }}/keperluan/${typeId}`; // Adjusted to match the API
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
                            showNotification('success', 'Type requirement berhasil dihapus!');
                            deleteModal.hide();
                            $('#KeperluanTable').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Gagal menghapus type requirement.');
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menghapus type requirement.';
                        showNotification('error', message);
                    }
                });
            });

            // Delete selected types
            $('#deleteSelected').on('click', function() {
                let selectedIds = [];
                $('.select-item:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    showNotification('error', 'Pilih setidaknya satu type requirement untuk dihapus.');
                    return;
                }

                $('#confirmDelete').modal('show'); // Show confirmation modal
                $('#confirmDeleteButton').off('click').on('click', function() {
                    $.ajax({
                        url: `{{ config('app.api_url') }}/keperluan/delete-selected`, // Adjusted to match the API
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
                                    'Data yang dipilih berhasil dihapus!');
                                $('#confirmDelete').modal('hide');
                                $('#KeperluanTable').DataTable().ajax.reload();
                            } else {
                                showNotification('error',
                                    'Gagal menghapus type requirement terpilih.');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            let message = xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menghapus type requirement terpilih.';
                            showNotification('error', message);
                        }
                    });
                });
            });

            // Untuk edit data
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                // Ambil ID dari input hidden (pastikan ada input hidden di form)
                var id = $('#edit-id').val(); // ID yang akan di-update
                var formData = $(this).serialize(); // Ambil data dari form

                $.ajax({
                    url: `{{ config('app.api_url') }}/keperluan/${id}`, // Endpoint API
                    type: 'PUT', // Metode PUT untuk update data
                    data: formData, // Kirim data dari form
                    success: function(response) {
                        console.log('Success:', response); // Debug jika sukses
                        if (response.success) {
                            // Menampilkan notifikasi jika berhasil
                            showNotification('success', response
                                .message); // Gunakan message dari response
                            $('#KeperluanTable').DataTable().ajax.reload(); // Reload DataTable
                        } else {
                            // Notifikasi jika gagal
                            showNotification('error', response.message ||
                                'Gagal memperbarui type requirement.'
                            ); // Tampilkan message jika ada
                        }
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr); // Debug jika terjadi error
                        console.log('Response Text:', xhr
                            .responseText); // Log respon mentah dari server
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat memperbarui type requirement.';
                        showNotification('error', message); // Tampilkan notifikasi error
                    },
                    complete: function() {
                        $('#editData').modal('hide'); // Tutup modal setelah proses selesai
                    }
                });
            });

            // Mengambil data untuk ditampilkan di modal edit
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id'); // Mengambil ID dari data-id
                var url = `{{ config('app.api_url') }}/keperluan/${id}`; // Membentuk URL API berdasarkan ID

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#editForm').attr('action', url); // Atur action form ke URL
                        $('#edit-nama').val(data.nama); // Isi input dengan data yang diambil
                        $('#edit-nama-tanggal-akhir').val(data
                            .nama_tanggal_akhir); // Isi input nama_tanggal_akhir
                        $('#edit-extend').prop('checked', data.extend); // Atur checkbox extend
                        $('#edit-id').val(id); // Isi input hidden dengan ID
                        $('#editData').modal('show'); // Tampilkan modal
                    },
                    error: function(xhr) {
                        showNotification('error',
                            'Error fetching type requirement data.'
                        ); // Tampilkan notifikasi error
                    }
                });
            });

            // DataTable initialization
            var table = $('#KeperluanTable').DataTable({
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
                        data: 'nama'
                    },
                    {
                        data: 'extend',
                        render: function(data) {
                            return data == 1 ? 'Iya' : 'Tidak';
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<div class="d-flex"><button data-id="${data}" class="btn-edit btn-action" aria-label="Edit"><iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon></button>
                                <button data-id="${data}" class="btn-delete btn-action" aria-label="Delete"><iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon></button></div>`;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // Select-all checkbox
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // Individual checkboxes
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Enable/disable the delete selected button
            function toggleDeleteButton() {
                const anyChecked = $('.select-item:checked').length > 0;
                $('#deleteSelected').prop('disabled', !anyChecked);
            }
        });
    </script>
@endsection
