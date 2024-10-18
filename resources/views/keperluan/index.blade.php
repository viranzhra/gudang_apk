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

    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}


    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>
        <h4 class="mt-3" style="color: #8a8a8a; font-size: 21px;">Requirement Type</h4>
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
                    <th>Requirement Type</th>
                    <th>2 Dates?</th>
                    <th>Time Limit (Days)</th>
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
                    <h5 class="modal-title" id="tambahDataLabel">Add Requirement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="" method="post" action="{{ route('keperluan.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3"
                                role="alert">
                                <strong class="font-bold">Ups!</strong> Terjadi kesalahan:
                                <ul class="mt-3 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="nama" class="form-label">Requirement Type</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Untuk Dipinjam" required />
                        </div>

                        <div class="form-check mb-3">
                            <input id="extend" type="checkbox" name="extend" value="0" class="form-check-input">
                            <label for="extend" class="form-check-label">Apply two dates?</label>
                        </div>

                        <script>
                            document.getElementById('extend').addEventListener('change', function() {
                                this.value = this.checked ? '1' : '0';
                                document.getElementById('tanggalInputs').style.display = this.checked ? 'block' : 'none';
                                document.getElementById('nama_tanggal_akhir').required = this.checked;
                            });
                        </script>

                        <div id="tanggalInputs" style="display: none;">
                            <div class="row">
                                <!-- Input Nama Tanggal Akhir -->
                                <div class="col-md-6 mb-3">
                                    <label for="nama_tanggal_akhir" class="form-label">Extension Name</label>
                                    <input type="text" id="nama_tanggal_akhir" name="nama_tanggal_akhir"
                                        class="form-control" placeholder="Tanggal Peminjaman" value="Tanggal Peminjaman" />
                                </div>

                                <!-- Input Batas Waktu -->
                                <div class="col-md-6 mb-3">
                                    <label for="batas_hari" class="form-label">Time Limit (Days)</label>
                                    <input type="number" id="batas_hari" name="batas_hari" class="form-control"
                                        min="1" max="90" value="1" placeholder="max. 90 days" required />
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const batasWaktuInput = document.getElementById('batas_hari');
                                    const tanggalAkhirInput = document.getElementById('tanggal_akhir');

                                    // Fungsi untuk memperbarui tanggal pengembalian berdasarkan batas waktu
                                    function updateTanggalAkhir() {
                                        const batasWaktu = parseInt(batasWaktuInput.value) || 1;

                                        // Mengambil tanggal permintaan dari server
                                        const tanggalPermintaan = new Date("{{ date('Y-m-d') }}");

                                        const tanggalAkhir = new Date(tanggalPermintaan);
                                        tanggalAkhir.setDate(tanggalPermintaan.getDate() + batasWaktu); // Menambahkan batas waktu
                                        tanggalAkhirInput.value = tanggalAkhir.toISOString().split('T')[
                                            0]; // Mengatur nilai untuk tanggal akhir
                                    }

                                    // Event listener jika batas waktu diubah
                                    batasWaktuInput.addEventListener('input', updateTanggalAkhir);

                                    // Inisialisasi tanggal akhir saat halaman dimuat
                                    updateTanggalAkhir();
                                });
                            </script>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
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
                    <h5 class="modal-title" id="editDataLabel">Edit Requirement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id" name="id" value="">

                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Requirement Type</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control" required />
                        </div>

                        <div class="form-check mb-3">
                            <input id="edit-extend" type="checkbox" name="extend" value="1"
                                class="form-check-input">
                            <label for="edit-extend" class="form-check-label">Apply two dates?</label>
                        </div>

                        <div id="editExtensionNameField" style="display: none;">
                            <div class="row">
                                <!-- Input Nama Tanggal Akhir -->
                                <div class="col-md-6 mb-3">
                                    <label for="edit-nama_tanggal_akhir" class="form-label">Extension Name</label>
                                    <input type="text" id="edit-nama_tanggal_akhir" name="nama_tanggal_akhir"
                                        class="form-control" placeholder="Tanggal Peminjaman" />
                                </div>

                                <!-- Input Batas Waktu -->
                                <div class="col-md-6 mb-3">
                                    <label for="editbatas_hari" class="form-label">Time Limit (Days)</label>
                                    <input type="number" id="editbatas_hari" name="batas_hari" class="form-control"
                                        min="1" max="90" value="1" placeholder="max. 90 days"
                                        required />
                                </div>
                            </div>
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
                nama_tanggal_akhir: $('#extend').is(':checked') ? $('#end_date').val() :
                '', // Kirim string kosong jika tidak dicentang
                extend: $('#extend').is(':checked') // Mengubah menjadi boolean secara otomatis
            };

            console.log("Data yang dikirim:", formData); // Debug: cek data yang dikirim

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
                    console.log("Response dari server:", response); // Debug: log response dari server

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
                        showNotification('error', 'Terjadi kesalahan pada jaringan atau server.');
                    }
                }
            });
        });

        // Menyembunyikan input extension_name saat halaman pertama kali dimuat
        $(document).ready(function() {
            $('#extension_name').hide(); // Sembunyikan input extension_name saat pertama kali dimuat
        });

        // Menangani perubahan checkbox extend pada form tambah data
        $('#extend').change(function() {
            if ($(this).is(':checked')) {
                $('#extension_name').show(); // Tampilkan kolom extension_name jika checkbox dicentang
            } else {
                $('#extension_name').hide(); // Sembunyikan kolom extension_name jika checkbox tidak dicentang
                $('#extension_name').val(''); // Kosongkan input extension_name
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

                // if (selectedIds.length === 0) {
                //     showNotification('error', 'Select at least one type requirement to delete.');
                //     return;
                // }

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
                                    'Selected data was successfully delected!');
                                $('#confirmDelete').modal('hide');
                                $('#KeperluanTable').DataTable().ajax.reload(
                                    function() {
                                        // Setelah reload, sembunyikan tombol delete
                                        toggleDeleteButton();
                                    });
                            } else {
                                showNotification('error',
                                    'Failed to delete selected type requirement!');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            let message = xhr.responseJSON?.message ||
                                'An error occurred while deleting selected type requirement.';
                            showNotification('error', message);
                        }
                    });
                });
            });

            // Menangani submit form edit
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                // Ambil ID dari input hidden
                var id = $('#edit-id').val(); // ID yang akan di-update

                // Konversi form data ke array dan tambahkan nilai extend secara manual
                var formData = $(this).serializeArray();
                formData.push({
                    name: 'extend',
                    value: $('#edit-extend').is(':checked') ? '1' : '0' // Nilai extend
                });

                $.ajax({
                    url: `{{ config('app.api_url') }}/keperluan/${id}`, // Endpoint API
                    type: 'PUT', // Metode PUT untuk update data
                    data: formData, // Kirim form data
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', response.message); // Notifikasi sukses
                            $('#KeperluanTable').DataTable().ajax.reload(); // Reload DataTable
                        } else {
                            showNotification('error', response.message ||
                                'Gagal memperbarui requirement type.'); // Notifikasi gagal
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat memperbarui requirement type.';
                        showNotification('error', message); // Tampilkan notifikasi error
                    },
                    complete: function() {
                        $('#editData').modal('hide'); // Tutup modal setelah proses selesai
                    }
                });
            });

            // Menangani perubahan checkbox extend pada form edit data
            $('#edit-extend').change(function() {
                if ($(this).is(':checked')) {
                    $('#editExtensionNameField').show(); // Tampilkan input jika checkbox dicentang
                } else {
                    $('#editExtensionNameField').hide(); // Sembunyikan input jika checkbox tidak dicentang
                    $('#edit-nama_tanggal_akhir').val(''); // Kosongkan input extension name
                    $('#editbatas_hari').val(1); // Reset batas hari ke default
                }
            });

            // Memastikan kolom extension name tampil/sesuai saat membuka modal edit data
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id'); // Ambil ID dari data attribute
                var url = `{{ config('app.api_url') }}/keperluan/${id}`; // Endpoint API

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        // Periksa apakah data tersedia
                        if (!data || Object.keys(data).length === 0) {
                            showNotification('error', 'Data tidak ditemukan.');
                            return; // Hentikan eksekusi lebih lanjut
                        }

                        $('#edit-nama').val(data.nama);
                        $('#edit-extend').prop('checked', data.extend ==
                            1); // Sesuaikan checkbox
                        $('#edit-id').val(id);

                        if (data.extend == 1) {
                            $('#editExtensionNameField').show();
                            $('#edit-nama_tanggal_akhir').val(data
                                .nama_tanggal_akhir); // Isi extension name
                            $('#editbatas_hari').val(data.batas_hari); // Isi batas hari
                        } else {
                            $('#editExtensionNameField').hide();
                            $('#edit-nama_tanggal_akhir').val(''); // Kosongkan extension name
                            $('#editbatas_hari').val(1); // Reset batas hari ke default
                        }

                        $('#editData').modal('show'); // Tampilkan modal edit
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'Error fetching type requirement data.';
                        showNotification('error',
                            message); // Tampilkan pesan error yang lebih informatif
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
                    // {
                    //     data: 'created_at', // Tanggal Awal Dibuat (Start Date)
                    //     render: function(data) {
                    //         return data ? new Date(data).toLocaleDateString('id-ID') :
                    //             '-'; // Format tanggal
                    //     }
                    // },
                    {
                        data: 'batas_hari',
                        name: 'batas_hari',
                        render: function(data, type, row) {
                            // Jika extend bernilai 0 atau tidak ada batas hari, tampilkan "Tidak ada batas hari"
                            return row.extend == 0 ? 'Tidak ada batas hari' : (data ? data +
                                ' hari' : 'Tidak ada batas hari');
                        },
                        defaultContent: 'Tidak ada batas hari' // Tampilkan "Tidak ada batas hari" jika datanya kosong dari awal
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
                const selected = $('.select-item:checked').length;
                const deleteButton = $('#deleteSelected');
                if (selected > 0) {
                    deleteButton.removeClass('d-none');
                } else {
                    deleteButton.addClass('d-none');
                }
            }
        });
    </script>
@endsection