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

    <div class="container mt-3 rounded-4 shadow-sm" style="padding-bottom: 15px; padding-top: 10px; min-width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>

        <div class="d-flex align-items-center justify-content-between pb-3 pt-2">
            <div class="d-flex align-items-center">
                <h4>Requirement Type</h4>
            </div>

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
                    <form id="addForm" method="post" action="{{ route('keperluan.store') }}"
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

                        <div class="form-check form-switch mb-3">
                            <input id="extend" type="checkbox" role="switch" name="extend" value="0"
                                class="form-check-input">
                            <label for="extend" class="form-check-label">Apply two dates?</label>
                        </div>

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

                            {{-- <script>
                                document.getElementById('extend').addEventListener('change', function() {
                                    this.value = this.checked ? '1' : '0';
                                    document.getElementById('tanggalInputs').style.display = this.checked ? 'block' : 'none';
                                    document.getElementById('nama_tanggal_akhir').required = this.checked;
                                });
                            </script> --}}

                            <script>
                                // Handle checkbox change for extending dates
                                document.getElementById('extend').addEventListener('change', function() {
                                    this.value = this.checked ? '1' : '0'; // Set value based on checkbox state
                                    document.getElementById('tanggalInputs').style.display = this.checked ? 'block' :
                                        'none'; // Show/hide inputs
                                    // Clear the values of the inputs when hiding
                                    if (!this.checked) {
                                        document.getElementById('nama_tanggal_akhir').value = ''; // Clear Extension Name
                                        document.getElementById('batas_hari').value = 1; // Reset Time Limit
                                    }
                                });
                            </script>
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

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Ups!</strong> Terjadi kesalahan:
                                <ul class="mt-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Requirement Type</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control"
                                placeholder="Nama Keperluan" required />
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input id="edit-extend" type="checkbox" role="switch" name="extend"
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
                                        min="1" max="90" placeholder="max. 90 days" />
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengatur tampilan berdasarkan status checkbox
        function updateExtensionFieldVisibility() {
            var checkbox = document.getElementById('edit-extend');
            var extensionNameField = document.getElementById('editExtensionNameField');
            var namaTanggalAkhir = document.getElementById('edit-nama_tanggal_akhir');
            var batasHari = document.getElementById('editbatas_hari');

            // Tampilkan atau sembunyikan field berdasarkan status checkbox
            if (checkbox.checked) {
                extensionNameField.style.display = 'block'; // Tampilkan field
                namaTanggalAkhir.required = true; // Set field sebagai required
                // Menyamakan nilai batas_hari dengan input form jika checkbox diaktifkan
                batasHari.value = batasHari.value || 1; // Atur nilai default jika belum ada
            } else {
                extensionNameField.style.display = 'none'; // Sembunyikan field
                namaTanggalAkhir.required = false; // Set field tidak perlu diisi
                namaTanggalAkhir.value = ''; // Kosongkan input ketika checkbox tidak dicentang
                batasHari.value = ''; // Kosongkan batas_hari ketika checkbox dimatikan
            }
        }

        // Inisialisasi event listener setelah DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            var extendCheckbox = document.getElementById('edit-extend');
            if (extendCheckbox) {
                extendCheckbox.addEventListener('change', updateExtensionFieldVisibility);
                updateExtensionFieldVisibility(); // Panggil fungsi untuk mengatur tampilan awal
            }
        });
    </script>

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
                    Are you sure you want to delete <strong id="typeName"></strong>?
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
                    <p>Are you sure you want to delete <strong><span id="selectedCount"></span> selected data?</p><strong>
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
        $('form[id="addForm"]').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Prepare data for submission
            var formData = {
                _token: '{{ csrf_token() }}',
                nama: $('#nama').val(),
                nama_tanggal_akhir: $('#extend').is(':checked') ? $('#nama_tanggal_akhir').val() : 'null',
                extend: $('#extend').is(':checked') ? '1' : '0'
            };

            // Only add batas_hari if extend is checked and value is not empty
            if ($('#extend').is(':checked') && $('#batas_hari').val()) {
                formData.batas_hari = $('#batas_hari').val();
            }

            let $submitButton = $(this).find('button[type="submit"]');
            $submitButton.prop('disabled', true).html(
                '<i class="spinner-border spinner-border-sm"></i>'); // Disable button and show spinner

            $.ajax({
                url: 'https://doaibutiri.my.id/gudang/api/keperluan',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.success) {
                        // Simpan pesan sukses di sessionStorage
                        sessionStorage.setItem('notification', JSON.stringify({
                            type: 'success',
                            message: 'Successfully added data!'
                        }));

                        // Reload halaman setelah modal ditutup
                        location.reload(); // Reload halaman
                        $('#tambahData').modal('hide'); // Menutup modal

                    } else {
                        // Simpan pesan error di sessionStorage
                        sessionStorage.setItem('notification', JSON.stringify({
                            type: 'error',
                            message: response.message || 'Failed to add requirement type.'
                        }));
                        $('#tambahData').modal('hide'); // Menutup modal
                        location.reload(); // Reload halaman
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred when adding the requirement type.';

                    // Check if there's an error message from the server
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = xhr.responseJSON.errors.nama ? xhr.responseJSON.errors.nama[0] :
                            errorMessage;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    // Simpan pesan error di sessionStorage
                    sessionStorage.setItem('notification', JSON.stringify({
                        type: 'error',
                        message: errorMessage
                    }));
                    $('#tambahData').modal('hide'); // Menutup modal
                    location.reload(); // Reload halaman
                },
                complete: function() {
                    $submitButton.prop('disabled', false).html(
                        'Submit'); // Re-enable button after AJAX call
                }
            });
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
            $('#notification').removeClass('alert-success alert-danger alert-info')
                .addClass(notificationClass)
                .fadeIn();

            setTimeout(function() {
                $('#notification').fadeOut();
            }, 3000); // Notifikasi menghilang setelah 3 detik
        }

        // Menyembunyikan input batas_hari saat halaman pertama kali dimuat
        $(document).ready(function() {
            $('#extension_name').hide(); // Sembunyikan input batas_hari saat pertama kali dimuat

            // Cek apakah ada notifikasi yang disimpan di sessionStorage
            const notification = sessionStorage.getItem('notification');
            if (notification) {
                const {
                    type,
                    message
                } = JSON.parse(notification);
                showNotification(type, message); // Tampilkan notifikasi
                sessionStorage.removeItem(
                    'notification'); // Hapus notifikasi dari sessionStorage setelah ditampilkan
            }
        });

        // Menangani perubahan checkbox extend pada form tambah data
        $('#extend').change(function() {
            if ($(this).is(':checked')) {
                $('#extension_name').show(); // Tampilkan kolom batas_hari jika checkbox dicentang
            } else {
                $('#extension_name').hide(); // Sembunyikan kolom batas_hari jika checkbox tidak dicentang
                $('#nama_tanggal_akhir').val(''); // Kosongkan input nama_tanggal_akhir
                $('#batas_hari').val(''); // Kosongkan input batas_hari
            }
        });
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
                            showNotification('success',
                                'Requirement type successfully deleted!');
                            deleteModal.hide();
                            $('#KeperluanTable').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Failed to delete requirement type.');
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'An error occurred when deleting requirement type.';
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

                // Update jumlah data yang dipilih di modal
                $('#selectedCount').text(selectedIds.length);

                // Tampilkan modal konfirmasi
                $('#confirmDelete').modal('show');

                // Event untuk konfirmasi penghapusan
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
                                    'Selected data was successfully deleted!');
                                $('#confirmDelete').modal('hide');
                                $('#KeperluanTable').DataTable().ajax.reload(
                            function() {
                                    // Sembunyikan tombol delete setelah reload
                                    toggleDeleteButton();
                                    // Reset checkbox "select all" ke tidak tercentang
                                    $('#select-all').prop('checked', false);
                                });
                            } else {
                                showNotification('error',
                                    'Failed to delete selected requirement type!');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            let message = xhr.responseJSON?.message ||
                                'An error occurred when deleting selected requirement type.';
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

                // Jika extend tidak dicentang, set nama_tanggal_akhir menjadi 'tidak ada batas hari'
                if (!$('#edit-extend').is(':checked')) {
                    formData.push({
                        name: 'nama_tanggal_akhir',
                        value: 'tidak ada batas hari' // Set nilai ketika extend tidak dicentang
                    });
                }

                $.ajax({
                    url: `{{ config('app.api_url') }}/keperluan/${id}`, // Endpoint API
                    type: 'PUT', // Metode PUT untuk update data
                    data: formData, // Kirim form data
                    success: function(response) {
                        if (response.success) {
                            showNotification('success',
                                'Requirement type successfully updated !'); // Notifikasi sukses
                            $('#KeperluanTable').DataTable().ajax.reload(); // Reload DataTable
                        } else {
                            showNotification('error', response.message ||
                                'Failed to update requirement type.'); // Notifikasi gagal
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message ||
                            'An error occurred when updating requirement type.';
                        showNotification('error', message); // Tampilkan notifikasi error
                    },
                    complete: function() {
                        $('#editData').modal('hide'); // Tutup modal setelah proses selesai
                    }
                });
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
                            showNotification('error', 'Data not found.');
                            return; // Hentikan eksekusi lebih lanjut
                        }

                        $('#edit-nama').val(data.nama);
                        $('#edit-extend').prop('checked', data.extend ==
                            1); // Sesuaikan checkbox
                        $('#edit-id').val(id);
                        $('#editbatas_hari').val(data.batas_hari); // Set batas_hari dari data

                        if (data.extend == 1) {
                            $('#editExtensionNameField').show();
                            $('#edit-nama_tanggal_akhir').val(data
                                .nama_tanggal_akhir); // Isi extension name
                        } else {
                            $('#editExtensionNameField').hide();
                            $('#edit-nama_tanggal_akhir').val(''); // Kosongkan extension name
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
                // Nonaktifkan checkbox "select all" jika tidak ada checkbox individu yang dicentang
                const anyChecked = $('.select-item:checked').length > 0;
                $('#select-all').prop('checked', anyChecked); // Cek atau tidak
                $('#select-all').prop('disabled', !anyChecked); // Nonaktifkan jika tidak ada yang dicentang
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

            // Ketika halaman dimuat, nonaktifkan checkbox "select all" jika tidak ada checkbox individu
            $(document).ready(function() {
                const anyChecked = $('.select-item:checked').length > 0;
                $('#select-all').prop('disabled', !anyChecked); // Nonaktifkan jika tidak ada yang dicentang
            });
        });
    </script>
@endsection