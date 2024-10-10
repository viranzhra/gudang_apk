@extends('layouts.navigation')

@section('content')
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

        th,
        td {
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
            display: flex;
            /* Align items in a row */
            justify-content: flex-end;
            /* Align items to the right */
            gap: 5px;
            /* Space between buttons */
            align-items: center;
            /* Center items vertically */
        }

        .btn-action {
            border: none;
            /* Remove border */
            background: none;
            /* Remove background */
            padding: 0;
            /* Remove padding */
            cursor: pointer;
            /* Pointer cursor for interaction */
        }

        .icon-edit,
        .icon-delete,
        .icon-detail,
        .icon-tambah {
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

        .btn-action:hover .icon-edit,
        .btn-action:hover .icon-delete {
            opacity: 0.8;
        }

        /* Ensure the button and controls container adapt well to various screen sizes */
        .controls-container {
            display: flex;
            align-items: center;
            /* Align items vertically in the center */
            gap: 10px;
            /* Spacing between elements */
            flex-wrap: wrap;
            /* Allow items to wrap to the next line on smaller screens */
        }

        .controls-container label {
            margin-right: 5px;
            /* Space between label and select */
        }

        .controls-container select {
            margin-right: 5px;
            /* Space between select and other elements */
        }

        .btn-primary {
            display: flex;
            /* Align icon and text in a row */
            align-items: center;
            background-color: #635bff;
            color: #ffffff;
            border: none;
            padding: 7px 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            /* Adjust font size for better responsiveness */
            white-space: nowrap;
            /* Prevent text from wrapping */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Color on hover */
        }

        /* Media query for small screens */
        @media (max-width: 576px) {
            .search-container {
                flex-direction: column;
                /* Stack items vertically */
                align-items: flex-start;
                /* Align items to the start of the container */
            }

            .search-box input[type="search"] {
                width: 100%;
                /* Make the search input full width */
                margin-bottom: 10px;
                /* Add space below the search input */
            }

            .btn-primary {
                width: 100%;
                /* Make the button full width */
                text-align: center;
                /* Center the text */
                font-size: 16px;
                /* Adjust font size for better readability */
            }

            .controls-container {
                flex-direction: column;
                /* Stack controls vertically on small screens */
                align-items: stretch;
                /* Stretch controls to full width */
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
            font-size: 14px;
            /* Ukuran font untuk ikon */
            line-height: 1.25;
            color: #007bff;
            text-decoration: none;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .pagination .page-link .fa {
            font-size: 14px;
            /* Ukuran font ikon panah */
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

        /* Detail Item */
        .detail-item {
            margin-bottom: 1rem;
            /* Jarak antara baris detail */
            display: flex;
            align-items: center;
            /* Vertically center the content */
        }

        /* Label */
        .detail-item strong {
            margin-right: 1rem;
            /* Jarak antara label dan isi */
            flex: 0 0 100px;
            /* Width of label column */
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
            justify-content: flex-start;
            /* Tetap di sebelah kiri */
            align-items: center;
            text-align: left;
            /* Teks tetap rata kiri */
            /* Hidden by default */
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Incoming Item</h4>
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>

        <div class="d-flex align-items-center gap-3 justify-content-end pb-3">
            <!-- Tombol Unduh Template -->
            <a href="{{ route('template.download') }}" class="btn btn-primary d-flex align-items-center"
                style="height: 40px;">
                <iconify-icon icon="mdi:download" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
                Unduh Template
            </a>

            <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                @csrf
                <label class="btn btn-success d-flex align-items-center justify-content-center" for="fileInput" style="cursor: pointer; height: 40px; margin-right: 10px;">
                    <iconify-icon icon="mdi:upload" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
                    Upload File
                </label>
                <input type="file" id="fileInput" name="file" class="d-none" accept=".xlsx, .xls, .csv" required onchange="displayFileName()">
            
                <!-- Label for displaying selected file -->
                <div id="fileInfo" class="ms-2 d-none" style="font-size: 12px; font-weight: bold;">
                    File yang akan diimpor: 
                    <span id="fileName" class="badge ms-1" style="font-size: 12px; background-color: #635bff; color: white;"></span>
                </div>
            
                <!-- Preview Button -->
                <button id="previewButton" type="button" class="btn btn-secondary ms-3 d-none" onclick="previewFile()">
                    Preview
                </button>
            </form>
            <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary d-flex align-items-center"
                style="height: 40px;">
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

<!-- Table Preview -->
<div id="previewTable" class="mt-4 d-none">
    <h2>Preview Data</h2>
    <table class="table table-bordered" id="dataPreview">
        <thead>
            <tr id="tableHeader"></tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>

    <!-- Form untuk Konfirmasi Import -->
<form id="importForm" action="{{ route('upload.excel') }}" method="POST" class="mt-4" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Upload File</label>
        <input type="file" name="file" id="file" class="form-control" required onchange="setFileName()">
    </div>
    <input type="hidden" name="file_name" id="hiddenFileName">
    <button type="submit" class="btn btn-success" id="importButton"><i class="fa fa-file"></i> Import Data</button>
</form>

</div>

        <table class="table table-bordered table-striped table-hover" id="barang-table" width="100%">
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span style="font-weight: bold;" id="barangmasuk"></span>?
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

    <!-- Modal hapus terpilih -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
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
                    <button type="button" class="btn" style="background-color: #910a0a; color: white;"
                        id="confirmDeleteButton"><span id="confirmDeleteText">Delete</span>
                        <span class="loading-icon" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        function setFileName() {
            var fileInput = document.getElementById('file');
            var hiddenFileName = document.getElementById('hiddenFileName');
    
            // Cek jika ada file yang dipilih
            if (fileInput.files.length > 0) {
                // Set nilai hidden input dengan nama file yang dipilih
                hiddenFileName.value = fileInput.files[0].name;
            } else {
                hiddenFileName.value = ''; // Kosongkan jika tidak ada file yang dipilih
            }
        }
    </script>
    

    <script>
    // Import Form Submission
$('#importForm').on('submit', function(e) {
    e.preventDefault(); // Mencegah pengiriman form secara default

    var formData = new FormData(this); // Mengambil data form

    $.ajax({
        url: $(this).attr('action'), // Menggunakan URL dari action form
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Tangani respons sukses
            $('#notification')
                .removeClass('alert-danger')
                .addClass('alert-success')
                .show();

            $('#notificationTitle').text('Sukses!');
            $('#notificationMessage').text(response.message);
            // Reset form after successful import
            $('#importForm')[0].reset(); // Reset form fields
            displayFileName(); // Hide file info after reset
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
            let detailedMessages = [];

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Loop through each error and push the messages into the detailedMessages array
                $.each(xhr.responseJSON.errors, function(key, messages) {
                    detailedMessages.push(messages.join(', ')); // Join multiple messages for each field
                });
                errorMessage = detailedMessages.join(' | '); // Join field messages with a separator
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText; // Ambil pesan error dari response text
            }

            $('#notification')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .show();

            $('#notificationTitle').text('Error!');
            $('#notificationMessage').text(errorMessage);
        }
    });
});

    </script>
    

    <script>
        function displayFileName() {
            const fileInput = document.getElementById('fileInput');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const previewButton = document.getElementById('previewButton');
            
            fileName.textContent = fileInput.files[0].name;
            fileInfo.classList.remove('d-none');
            previewButton.classList.remove('d-none');
    
            // Set nama file untuk form import
            const importFileName = document.getElementById('importFileName');
            const hiddenFileName = document.getElementById('hiddenFileName');
            const hiddenFilePath = document.getElementById('hiddenFilePath');
    
            importFileName.textContent = fileInput.files[0].name;
            hiddenFileName.value = fileInput.files[0].name;
            hiddenFilePath.value = fileInput.files[0].path; // Menyimpan path file jika diperlukan
        }
    
        function previewFile() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
    
            const reader = new FileReader();
            reader.onload = function(event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet, {header: 1});
    
                // Tampilkan tabel preview
                const tableHeader = document.getElementById('tableHeader');
                const tableBody = document.getElementById('tableBody');
    
                // Kosongkan tabel sebelumnya
                tableHeader.innerHTML = '';
                tableBody.innerHTML = '';
    
                // Set header tabel
                jsonData[0].forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header;
                    tableHeader.appendChild(th);
                });
    
                // Set isi tabel
                jsonData.slice(1).forEach(row => {
                    const tr = document.createElement('tr');
                    row.forEach(cell => {
                        const td = document.createElement('td');
                        td.textContent = cell;
                        tr.appendChild(td);
                    });
                    tableBody.appendChild(tr);
                });
    
                // Tampilkan tabel dan form import
                document.getElementById('previewTable').classList.remove('d-none');
            };
    
            reader.readAsArrayBuffer(file);
        }
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
                const barangNama = $(element).closest('tr').find('td:nth-child(3)')
                    .text(); // Ganti dengan indeks yang sesuai

                // Mengisi informasi barang di modal
                $('#barangmasuk').text(barangNama);

                // Menetapkan URL penghapusan pada form
                $('#deleteForm').attr('action',
                    `{{ config('app.api_url') }}/barangmasuk/${id}`); // Pastikan URL sesuai dengan route

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
                    data: {
                        _token: $('meta[name="csrf-token"]').attr(
                            'content'), // Menyertakan CSRF token
                    },
                    success: function(response) {
                        showNotification('success', 'Data berhasil dihapus.');
                        // Refresh DataTable setelah penghapusan
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Menampilkan pesan kesalahan yang lebih informatif
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON
                                .message; // Menampilkan pesan kesalahan dari server
                        }
                        showNotification('error', errorMessage);
                    }
                });

                // Menutup modal setelah pengiriman form
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
            });


            // Fungsi untuk menampilkan detail modal
            window.showDetailModal = function(id, namaBarang, namaJenisBarang, namaSupplier, tanggalBarang,
                keteranganBarang, jumlah) {
                const existingModal = document.getElementById('detailModal');
                if (existingModal) {
                    existingModal.remove();
                }

                fetch(`{{ config('app.api_url') }}/barangmasuk/${id}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + '{{ session('token') }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!Array.isArray(data)) {
                            console.error('Unexpected data format:', data);
                            showNotification('error',
                                'Error loading item details.'); // Tampilkan notifikasi
                            return;
                        }

                        const detailContent = data.map((detail, index) => `
                            <hr class="col-span-10 my-3">
                            <div class="row">
                                <div class="col-3"><strong>Barang ${index + 1}</strong></div>
                                :<div class="col-8">${detail.serial_number} — <span style="color:${detail.warna_status_barang}">${detail.status_barang}</span></div>
                            </div>
                            <div class="row">
                                <div class="col-3"><strong>Kelengkapan</strong></div>
                                :<div class="col-8">${detail.kelengkapan_barang || '—'}</div>
                            </div>
                        `).join('');

                        const modalContent = `
                            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel" style="margin-left: 30%;">Detail Barang Masuk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="grid grid-cols-10 gap-2">
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Nama Barang</strong></div>:
                                                    <div class="col-8">${namaBarang || '—'}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Jenis Barang</strong></div>:
                                                    <div class="col-8">${namaJenisBarang || '—'}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Supplier</strong></div>:
                                                    <div class="col-8">${namaSupplier || '—'}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Tanggal Masuk</strong></div>:
                                                    <div class="col-8">${tanggalBarang || '—'}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Keterangan</strong></div>:
                                                    <div class="col-8">${keteranganBarang ? keteranganBarang : '—'}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3"><strong>Jumlah</strong></div>:
                                                    <div class="col-8">${jumlah || 0}</div>
                                                </div>
                                                ${detailContent}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        document.body.insertAdjacentHTML('beforeend', modalContent);
                            new bootstrap.Modal(document.getElementById('detailModal')).show();
                        })
                        .catch(error => {
                            console.error('Error fetching details:', error);
                            showNotification('error', 'Error loading item details.'); // Tampilkan notifikasi
                        });
            }

            // Inisialisasi DataTable
            const table = new DataTable('#barang-table', {
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ config('app.api_url') }}/barangmasuk',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    }
                },
                columns: [{
                        data: 'barang_masuk_id',
                        name: 'barang_masuk.id',
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
                        data: 'nama_barang',
                        name: 'barang.nama',
                        defaultContent: 'Data tidak tersedia'
                    },
                    {
                        data: 'jumlah',
                        name: 'barang_masuk.jumlah',
                        searchable: false,
                        defaultContent: '0'
                    },
                    {
                        data: 'keterangan_barangmasuk',
                        name: 'barang_masuk.keterangan',
                        defaultContent: '-'
                    },
                    {
                        data: 'tanggal_barang',
                        searchable: false,
                        defaultContent: '-'
                    },
                    {
                        data: 'barang_masuk_id',
                        name: 'barang_masuk.id',
                        orderable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex">
                                <button aria-label="Detail" onclick="showDetailModal(${data}, '${row.nama_barang}', '${row.nama_jenis_barang}', '${row.nama_supplier}', '${row.tanggal_barang}', '${row.keterangan_barangmasuk}', ${row.jumlah})" class="btn-detail btn-action" style="border: none;">
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
                    [2, 'asc']
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Menangani pemilihan semua checkbox
            $('#select-all').on('click', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // Menangani perubahan pada checkbox item
            $('#barang-table tbody').on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Fungsi untuk menampilkan/hide tombol delete
            function toggleDeleteButton() {
                const anyChecked = $('.select-item:checked').length > 0;
                console.log('Checkbox checked:', anyChecked);
                if (anyChecked) {
                    $('#deleteSelected').removeClass('d-none');
                } else {
                    $('#deleteSelected').addClass('d-none');
                }
            }

            // Menangani klik tombol hapus terpilih
            $('#deleteSelected').on('click', function() {
                $('#confirmDeleteModal').modal('show'); // Tampilkan modal konfirmasi
            });

            // Menangani konfirmasi penghapusan
            $('#confirmDeleteButton').on('click', function() {
                const selectedItems = $('.select-item:checked');
                const idsToDelete = [];

                // Ambil ID dari checkbox yang terpilih
                selectedItems.each(function() {
                    idsToDelete.push($(this).val());
                });

                // Menampilkan ikon loading dan menyembunyikan teks tombol
                $('#confirmDeleteText').hide(); // Sembunyikan teks tombol
                $('.loading-icon').show(); // Tampilkan ikon loading

                // Kirim permintaan AJAX untuk menghapus item
                $.ajax({
                    url: '{{ config('app.api_url') }}/barangmasuk/delete-selected', // Ganti dengan URL endpoint yang sesuai
                    type: 'POST', // Gunakan POST jika DELETE tidak berfungsi di server
                    data: {
                        ids: idsToDelete,
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // Jika menggunakan Laravel
                    },
                    success: function(response) {
                        // Hapus baris yang terpilih dari tabel
                        selectedItems.each(function() {
                            $(this).closest('tr').remove();
                        });

                        // Reload halaman sebelum menampilkan notifikasi
                        setTimeout(() => {
                            // Tampilkan notifikasi sukses
                            showNotification('Data berhasil dihapus.', 'success');
                            location.reload(); // Reload halaman
                        }, 3000); // Tampilkan notifikasi selama 3 detik sebelum reload
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        // Reload halaman sebelum menampilkan notifikasi
                        setTimeout(() => {
                            // Tampilkan notifikasi gagal
                            showNotification('Terjadi kesalahan saat menghapus data: ' +
                                xhr.responseText, 'error');
                            location.reload(); // Reload halaman
                        }, 3000); // Tampilkan notifikasi selama 3 detik sebelum reload
                    },
                    complete: function() {
                        // Menyembunyikan ikon loading dan menampilkan teks tombol
                        $('.loading-icon').hide(); // Sembunyikan ikon loading
                        $('#confirmDeleteText').show(); // Tampilkan teks tombol kembali
                        $('#confirmDeleteModal').modal(
                            'hide'); // Sembunyikan modal hanya setelah semua proses selesai
                    }
                });
            });

            // Fungsi untuk menampilkan notifikasi
            function showNotification(message, type) {
                // Atur judul dan pesan notifikasi
                $('#notificationTitle').text(type === 'success' ? 'Sukses' : 'Error');
                $('#notificationMessage').text(message);

                // Set kelas alert berdasarkan tipe
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                $('#notification').removeClass('alert-success alert-danger').addClass(alertClass).fadeIn();

                // Tampilkan notifikasi
                $('#notification').show();

                // Menghilangkan notifikasi setelah 3 detik
                setTimeout(() => {
                    $('#notification').fadeOut(); // Menghilangkan notifikasi
                }, 3000);
            }
        });
    </script>
@endsection