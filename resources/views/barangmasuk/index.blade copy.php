@extends('layouts.navigation')

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>

<style>
    .card {
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .table-responsive {
        margin-top: 20px;
    }                                  

    th, td {
        padding: 8px;
        text-align: left;
        color: black;
    }

    th {
        background-color: #f2f2f2;
        cursor: default;
        font-weight: bold;
        color: rgba(0, 0, 0, 0.829);
    }

    .search-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .search-box {
        display: flex;
        align-items: center;
        position: relative;
    }

    .search-box input[type="search"] {
        padding: 5px 30px 5px 10px;
        width: 250px;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .search-box .search-icon {
        position: absolute;
        right: 5px;
        padding: 5px;
        font-size: 18px;
        color: gray;
        cursor: pointer;
    }

    .search-container label {
        margin-right: 10px;
    }

    .search-container select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-actions-container {
        display: flex; /* Align items in a row */
        justify-content: flex-end; /* Align items to the right */
        gap: 5px; /* Space between buttons */
        align-items: center; /* Center items vertically */
    }

    .btn-action {
        border: none; /* Remove border */
        background: none; /* Remove background */
        padding: 0; /* Remove padding */
        cursor: pointer; /* Pointer cursor for interaction */
    }

    .icon-edit, .icon-delete, .icon-detail, .icon-tambah {
        color: #ffffff; 
        font-size: 18px;
        width: 30px; 
        height: 30px; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        margin-right: 5px;
    }

    .icon-detail {
        background-color: #112337;
    }

    .icon-tambah {
        background-color: #01578d;
    }

    .icon-edit {
        background-color: #01578d;
    }

    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
    }

    /* Ensure the button and controls container adapt well to various screen sizes */
    .controls-container {
    display: flex;
    align-items: center; /* Align items vertically in the center */
    gap: 10px; /* Spacing between elements */
    flex-wrap: wrap; /* Allow items to wrap to the next line on smaller screens */
    }

    .controls-container label {
    margin-right: 5px; /* Space between label and select */
    }

    .controls-container select {
    margin-right: 5px; /* Space between select and other elements */
    }

    .btn-primary {
    display: flex; /* Align icon and text in a row */
    align-items: center;
    background-color: #635bff;
    color: #ffffff;
    border: none;
    padding: 7px 10px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px; /* Adjust font size for better responsiveness */
    white-space: nowrap; /* Prevent text from wrapping */
    }

    .btn-primary:hover {
    background-color: #0056b3; /* Color on hover */
    }

    /* Media query for small screens */
    @media (max-width: 576px) {
    .search-container {
        flex-direction: column; /* Stack items vertically */
        align-items: flex-start; /* Align items to the start of the container */
    }

    .search-box input[type="search"] {
        width: 100%; /* Make the search input full width */
        margin-bottom: 10px; /* Add space below the search input */
    }

    .btn-primary {
        width: 100%; /* Make the button full width */
        text-align: center; /* Center the text */
        font-size: 16px; /* Adjust font size for better readability */
    }

    .controls-container {
        flex-direction: column; /* Stack controls vertically on small screens */
        align-items: stretch; /* Stretch controls to full width */
    }
    }

    /* Flex container for info and pagination */
    .info-pagination-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .info-text {
        margin: 0;
        font-size: 14px;
        color: #8a8a8a;
    }

    .pagination-container {
        margin: 0;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 20px;
        text-align: right;
    }

    .pagination {
        display: inline-flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
    }

    .pagination .page-item {
        display: inline;
    }

    .pagination .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin: 0;
        font-size: 14px; /* Ukuran font untuk ikon */
        line-height: 1.25;
        color: #007bff;
        text-decoration: none;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .pagination .page-link .fa {
        font-size: 14px; /* Ukuran font ikon panah */
    }

    .pagination .page-item.active .page-link {
        background-color: #635bff;
        border-color: #635bff;
        color: #ffffff;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .hidden {
        display: none;
    }

    /* Modal Body */
    .modal-body {
        padding: 1.5rem;
        color: black;
    }

    .modal-dialog {
            max-height: 50px; /* Small modal width on larger screens */
            max-width: 400px;
        }

    /* Detail Item */
    .detail-item {
        margin-bottom: 1rem; /* Jarak antara baris detail */
        display: flex;
        align-items: center; /* Vertically center the content */
    }

    /* Label */
    .detail-item strong {
        margin-right: 1rem; /* Jarak antara label dan isi */
        flex: 0 0 100px; /* Width of label column */
    }

    /* Isi Detail */
    .detail-item span {
        color: #333; 
        font-size: 1rem; 
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
            justify-content: flex-start; /* Tetap di sebelah kiri */
            align-items: center;
            text-align: left; /* Teks tetap rata kiri */
            /* Hidden by default */
        }
</style>

<div class="container mt-3" style="padding: 30px; padding-bottom: 13px;">
    <h4 style="color: #8a8a8a;">Incoming Item</h4>
    <!-- Notification Element -->
    <div id="notification" class="alert" style="display: none;">
        <strong id="notificationTitle">Notification</strong>
        <p id="notificationMessage"></p>
    </div>

    <div class="d-flex align-items-center gap-3 justify-content-end pb-3">
        <!-- Tombol Unduh Template -->
        <a href="{{ route('template.download') }}" class="btn btn-primary d-flex align-items-center" style="height: 40px;">
            <iconify-icon icon="mdi:download" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
            Unduh Template
        </a>
    
        <!-- Form Upload -->
        <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
            @csrf
            <label class="btn btn-success d-flex align-items-center justify-content-center" for="fileInput" style="cursor: pointer; height: 40px;">
                <iconify-icon icon="mdi:upload" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
                Upload File
            </label>
            <input type="file" id="fileInput" name="file" class="d-none" accept=".xlsx, .xls, .csv" required>
            <button type="submit" class="btn btn-primary ms-2" style="height: 40px;">
                Kirim
            </button>
        </form>
        
        <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary d-flex align-items-center" style="height: 40px;">
            <iconify-icon icon="mdi:plus-circle" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
            Add
        </a>        
    
        <!-- Delete Selected Button -->
        <button id="deleteSelected" class="btn btn-danger d-none d-flex align-items-center"
            style="height: 40px; background-color: #910a0a; border: none;">
            <iconify-icon icon="mdi:delete" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
            Delete Selected
        </button>
    </div>    
    
    <table class="table table-bordered table-striped table-hover" id="barangMasukTable" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>No</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Tanggal Masuk</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- <!-- Modal Detail Data -->
<div class="modal fade" id="detailBarangMasuk" tabindex="-1" aria-labelledby="detailDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailDataLabel">Detail Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-5"><strong>Nama Barang:</strong></div>
                    <div class="col-8"><span class="detail-nama-barang"></span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-5"><strong>Jumlah:</strong></div>
                    <div class="col-8"><span class="detail-jumlah"></span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-5"><strong>Keterangan:</strong></div>
                    <div class="col-8"><span class="detail-keterangan-barang"></span></div>
                </div>
                <div class="row">
                    <div class="col-5"><strong>Tanggal Masuk:</strong></div>
                    <div class="col-8"><span class="detail-tanggal-barang"></span></div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="detailBarangMasuk" tabindex="-1" aria-labelledby="detailBarangMasukLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailBarangMasukLabel">Detail Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <strong>Nama Barang:</strong>
                    <div class="detail-nama-barang"></div>
                </div>
                <div>
                    <strong>Jumlah:</strong>
                    <div class="detail-jumlah"></div>
                </div>
                <div>
                    <strong>Keterangan:</strong>
                    <div class="detail-keterangan-barang"></div>
                </div>
                <div>
                    <strong>Tanggal Masuk:</strong>
                    <div class="detail-tanggal-barang"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document"> <!-- Add modal-sm class here -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                Are you sure you want to delete <span style="font-weight: bold;" id="barangmasuk"></span>?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background-color: #910a0a; color: white;">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal hapus terpilih -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the selected data?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #910a0a; color: white;" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Include the required JS files -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- detail --}}
<script>
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        // Mengambil data barang masuk menggunakan API
        $.ajax({
            url: `{{ config('app.api_url') }}/barangmasuk/${id}`, // Sesuaikan endpoint API jika perlu
            type: 'GET',
            success: function(data) {
                // Bersihkan detail sebelumnya
                $('.detail-nama-barang').empty();
                $('.detail-jumlah').empty();
                $('.detail-keterangan-barang').empty();
                $('.detail-tanggal-barang').empty();

                // Pastikan respons mengandung field yang diharapkan
                if (data.length > 0) {
                    // Loop melalui detail dan tambahkan ke modal
                    $.each(data, function(index, item) {
                        // Perbarui konten modal untuk setiap item
                        $('.detail-nama-barang').append(`<p>${item.serial_number}</p>`); // Asumsikan 'serial_number' relevan
                        $('.detail-jumlah').append(`<p>${item.kelengkapan_barang}</p>`); // Sesuaikan jika perlu
                        $('.detail-keterangan-barang').append(`<p>${item.kelengkapan_barang}</p>`); // Sesuaikan jika perlu
                        $('.detail-tanggal-barang').append(`<p>${moment(item.tanggal_masuk).format('dddd, D MMMM YYYY')}</p>`); // Sesuaikan berdasarkan field tanggal Anda
                    });
                } else {
                    $('.detail-nama-barang').text('Tidak ada detail ditemukan.');
                    $('.detail-jumlah').text('');
                    $('.detail-keterangan-barang').text('');
                    $('.detail-tanggal-barang').text('');
                }

                // Perbarui judul dan menampilkan modal
                $('#detailBarangMasukLabel').text('Detail Barang Masuk');
                $('#detailBarangMasuk').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil data barang masuk:', xhr.responseText);
                alert('Gagal mengambil data barang masuk.');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menampilkan notifikasi
    function showNotification(type, message) {
        let notificationTitle = '';
        let notificationClass = '';

        // Mengatur judul dan kelas berdasarkan tipe notifikasi
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

        // Mengatur konten notifikasi
        $('#notificationTitle').text(notificationTitle);
        $('#notificationMessage').text(message);
        $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass);

        // Menampilkan notifikasi
        $('#notification').fadeIn();

        // Menyembunyikan notifikasi setelah 3 detik
        setTimeout(function() {
            $('#notification').fadeOut();
        }, 3000);
    }

    // Fungsi untuk membuka modal konfirmasi hapus
    window.openDeleteModal = function(id, element) {
        // Mendapatkan nama barang dari elemen yang diklik
        const barangNama = $(element).closest('tr').find('td:nth-child(3)').text(); // Ganti dengan indeks yang sesuai

        // Mengisi informasi barang di modal
        $('#barangmasuk').text(barangNama);
        
        // Menetapkan URL penghapusan pada form
        $('#deleteForm').attr('action', `/barangmasuk/delete/${id}`);

        // Menampilkan modal konfirmasi hapus
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Menangani pengiriman form penghapusan
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form default

        // Mengirim permintaan AJAX untuk menghapus data
        $.ajax({
            url: $(this).attr('action'),
            type: 'DELETE',
            data: $(this).serialize(),
            success: function(response) {
                showNotification('success', 'Data berhasil dihapus.');
                // Refresh DataTable setelah penghapusan
                table.ajax.reload();
            },
            error: function(xhr) {
                showNotification('error', 'Error deleting data: ' + xhr.responseText);
            }
        });

        // Menutup modal setelah pengiriman form
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        deleteModal.hide();
    });

    // Fungsi untuk menampilkan notifikasi jika ada di sessionStorage
    function displayNotification() {
        const notificationData = sessionStorage.getItem('notification');
        
        if (notificationData) {
            const { type, message } = JSON.parse(notificationData);
            showNotification(type, message);
            sessionStorage.removeItem('notification');
        }
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(type, message) {
        const notification = document.getElementById('notification');
        const notificationTitle = document.getElementById('notificationTitle');
        const notificationMessage = document.getElementById('notificationMessage');

        notificationTitle.innerText = type === 'success' ? 'Success!' : 'Error!';
        notificationMessage.innerText = message;
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Panggil displayNotification saat halaman dimuat
    window.onload = displayNotification;
</script>



<!-- Script untuk inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        const table = $('#barangMasukTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ config('app.api_url') }}/barangmasuk',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}'
                },
                dataSrc: function (json) {
                    console.log(json); // Debug response dari API
                    // Memastikan data dikembalikan dengan benar
                    return json.data || [];
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                    console.log("Response:", xhr.responseText); // Log error response
                }
            },
            columns: [
                {
                    data: 'id',
                    orderable: false,
                    render: function(data) {
                        return `<input type="checkbox" class="select-item" value="${data}">`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Menampilkan nomor urut
                    }
                },
                { data: 'nama_barang', name: 'barang.nama' }, // Pastikan nama kolom sesuai
                { data: 'jumlah', name: 'barang_masuk.jumlah' }, // Pastikan nama kolom sesuai
                { 
                    data: 'keterangan_barang', 
                    name: 'barang.keterangan', // Pastikan nama kolom sesuai
                    render: function(data) {
                        const content = data ? data : '-';
                        return `<div style="text-align: center;">${content}</div>`; // Center the text using inline style
                    }
                },
                {
                    data: 'tanggal_barang', // Gunakan tanggal yang sudah diformat di controller
                    name: 'barang_masuk.tanggal', // Pastikan nama kolom sesuai
                    render: function(data) {
                        return data ? moment(data).format('dddd, D MMMM YYYY') : ''; // Format tanggal
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    render: function(data) {
                        return `
                            <div class="d-flex">
                                <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                    <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                </button>
                                <a href="/barangmasuk/create/${data}" class="btn-action">
                                    <iconify-icon icon="mdi:plus-circle" class="icon-tambah"></iconify-icon>
                                </a> 
                                <button id="deleteModal-${data}" data-id="${data}" class="btn-action btn-delete" aria-label="Delete" onclick="openDeleteModal('${data}', this)">
                                    <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [
                [2, 'asc'] // Sorting berdasarkan nama_barang
            ],
            // language: {
            //     processing: "Sedang memproses...",
            //     search: "Cari:",
            //     lengthMenu: "Tampilkan _MENU_ entri",
            //     info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            //     infoEmpty: "Tidak ada entri yang tersedia",
            //     infoFiltered: "(disaring dari _MAX_ total entri)",
            //     paginate: {
            //         first: "Pertama",
            //         last: "Terakhir",
            //         next: "Selanjutnya",
            //         previous: "Sebelumnya"
            //     },
            //     // Tambahkan pengaturan bahasa lain sesuai kebutuhan
            // }
        });
    });
</script>


{{-- Hapus terpilih --}}
<script>
    $(document).on('change', '#select-all', function() {
        const isChecked = $(this).is(':checked');
        $('.select-item').prop('checked', isChecked);
        toggleDeleteButton();
    });

    $(document).on('change', '.select-item', function() {
        toggleDeleteButton();
    });

    function toggleDeleteButton() {
        const selected = $('.select-item:checked').length;
        const deleteButton = $('#deleteSelected');
        if (selected > 0) {
            deleteButton.removeClass('d-none');
        } else {
            deleteButton.addClass('d-none');
        }
    }

    $(document).on('click', '#deleteSelected', function() {
        const selected = [];
        $('.select-item:checked').each(function() {
            selected.push($(this).val());
        });

        if (selected.length > 0) {
            // Show confirmation modal
            $('#confirmDeleteModal').modal('show');
            // Store selected IDs in the confirm delete button
            $('#confirmDeleteButton').data('ids', selected);
        } else {
            showNotification('error', 'Tidak ada data yang dipilih.');
        }
    });

    $(document).on('click', '#confirmDeleteButton', function() {
        const selected = $(this).data('ids');

        fetch('{{ config('app.api_url') }}/barangmasuk/delete-selected', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: selected })
        }).then(response => {
            if (response.ok) {
                showNotification('success', 'Data yang dipilih berhasil dihapus.');
                $('#barang-container').DataTable().ajax.reload(); // Reload DataTable if needed
            } else {
                showNotification('error', 'Gagal menghapus data.');
            }
            $('#confirmDeleteModal').modal('hide');
            // Hide the delete button after deletion
            $('#deleteSelected').addClass('d-none');
        }).catch(() => {
            showNotification('error', 'Terjadi kesalahan saat menghapus data.');
            $('#confirmDeleteModal').modal('hide');
        });
    });

    function showNotification(type, message) {
        const notification = $('#notification');
        const notificationTitle = $('#notificationTitle');
        const notificationMessage = $('#notificationMessage');

        notificationTitle.text(type === 'success' ? 'Sukses!' : 'Error!');
        notificationMessage.text(message);
        notification.removeClass('alert-success alert-danger')
                .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                .show();

        // Hide the notification after 3 seconds
        setTimeout(() => {
            notification.hide();
        }, 3000);
    }
</script>

{{-- <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#barangMasukTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ config('app.api_url') }}/barangmasuk',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}'
                }
            },
            columns: [
                { data: 'select', name: 'select', orderable: false, searchable: false }, // Kolom select
                { data: 'keterangan', name: 'keterangan' },
                { data: 'formatted_tanggal', name: 'formatted_tanggal' },
                { data: 'nama_barang', name: 'nama_barang' },
                { data: 'nama_jenis_barang', name: 'nama_jenis_barang' },
                { data: 'nama_supplier', name: 'nama_supplier' },
                { data: 'jumlah', name: 'jumlah' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <a href="/barangmasuk/edit/${row.barang_masuk_id}" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.barang_masuk_id}">Hapus</button>
                        `;
                    }
                }
            ]
        });
    
        // Handle select all checkboxes
        $('#select-all').on('click', function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            toggleDeleteButton();
        });
    
        // Handle checkbox change event
        $('#barangMasukTable tbody').on('change', 'input[type="checkbox"]', function() {
            toggleDeleteButton();
        });
    
        // Toggle delete button visibility
        function toggleDeleteButton() {
            var anyChecked = $('#barangMasukTable tbody input[type="checkbox"]:checked').length > 0;
            $('#delete-selected').toggleClass('hidden', !anyChecked);
        }
    
        // Add event listener for delete selected
        $('#delete-selected').on('click', function() {
            // Get selected item IDs
            var selectedIds = [];
            $('#barangMasukTable tbody input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).data('id'));
            });
    
            if (selectedIds.length === 0) {
                alert('No items selected for deletion');
                return;
            }
    
            // Confirm deletion
            if (confirm('Are you sure you want to delete the selected items?')) {
                // Perform delete request
                $.ajax({
                    url: '{{ config('app.api_url') }}/barangmasuk/delete-selected', // Ganti dengan URL endpoint delete
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    data: {
                        ids: selectedIds // Kirimkan array ID ke server
                    },
                    success: function(response) {
                        // Refresh DataTable after successful deletion
                        table.ajax.reload();
                        alert('Selected items deleted successfully');
                    },
                    error: function(xhr) {
                        alert('Error deleting selected items: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    
        // Handle individual delete button clicks
        $('#barangMasukTable tbody').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
    
            if (confirm('Are you sure you want to delete this item?')) {
                // Perform delete request
                $.ajax({
                    url: '{{ config('app.api_url') }}/barangmasuk/' + id, // Ganti dengan URL endpoint delete item
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    success: function(response) {
                        // Refresh DataTable after successful deletion
                        table.ajax.reload();
                        alert('Item deleted successfully');
                    },
                    error: function(xhr) {
                        alert('Error deleting item: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>     --}}

@endsection