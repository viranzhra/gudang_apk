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

        /* Remove top border of the table header */
        #previewTable thead tr {
            border-top: none;
            /* Remove top border */
        }

        /* Add a bottom border to each table row */
        #previewTable tbody tr {
            border-bottom: 1px solid #dee2e6;
            /* Adjust color and width as needed */
        }

        /* Optional: Remove the border from the last row */
        #previewTable tbody tr:last-child {
            border-bottom: none;
        }

        .loading {
            animation: spin 1s linear infinite;
            /* Animasi berputar */
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        @if (session('success'))
            <script>
                // Reset kelas dan konten untuk notifikasi
                const notificationElement = document.getElementById('notification');
                notificationElement.classList.remove('alert-danger');
                notificationElement.classList.add('alert-success');
                document.getElementById('notificationTitle').innerText = "Success";
                document.getElementById('notificationMessage').innerText = "{{ session('success') }}";
                notificationElement.style.display = 'block';

                // Sembunyikan notifikasi setelah beberapa detik
                setTimeout(() => {
                    notificationElement.style.display = 'none';
                }, 7000);
            </script>
        @endif

        @if (session('notifications'))
            <script>
                const notificationElement = document.getElementById('notification');
                let notificationQueue = @json(session('notifications'));

                // Fungsi untuk menampilkan notifikasi satu per satu
                function displayNotification(message) {
                    // Ubah kelas notifikasi menjadi alert-danger (notifikasi error)
                    notificationElement.classList.remove('alert-success');
                    notificationElement.classList.add('alert-danger');
                    document.getElementById('notificationTitle').innerText = "Error";
                    document.getElementById('notificationMessage').innerText = message;
                    notificationElement.style.display = 'block';

                    // Sembunyikan notifikasi setelah beberapa detik
                    setTimeout(() => {
                        notificationElement.style.display = 'none';
                        notificationElement.classList.remove('alert-danger');
                        processNextNotification(); // Lanjutkan ke notifikasi berikutnya
                    }, 7000);
                }

                // Proses antrian notifikasi
                function processNextNotification() {
                    if (notificationQueue.length > 0) {
                        let nextNotification = notificationQueue.shift(); // Ambil notifikasi berikutnya
                        displayNotification(nextNotification); // Tampilkan notifikasi berikutnya
                    }
                }

                // Mulai memproses antrian notifikasi
                processNextNotification();
            </script>
        @endif

        @if (session('finalMessage'))
            <script>
                // Reset kelas dan konten untuk notifikasi
                const notificationElement = document.getElementById('notification');
                notificationElement.classList.remove('alert-danger', 'alert-success'); // Hapus kelas apapun
                notificationElement.style.display = 'block'; // Tampilkan notifikasi

                // Ambil pesan dari session
                const message = "{{ session('finalMessage') }}";
                if (message.includes("Error")) {
                    notificationElement.classList.add('alert-danger'); // Jika ada error, tambahkan kelas danger
                    document.getElementById('notificationTitle').innerText = "Error";
                } else {
                    notificationElement.classList.add('alert-success'); // Jika tidak ada error, tambahkan kelas success
                    document.getElementById('notificationTitle').innerText = "Success";
                }
                document.getElementById('notificationMessage').innerText = message;

                // Sembunyikan notifikasi setelah beberapa detik
                setTimeout(() => {
                    notificationElement.style.display = 'none';
                }, 7000);
            </script>
        @endif

        {{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}


        <div class="d-flex align-items-center gap-3 justify-content-end pb-3 flex-wrap">
            <!-- Tombol Unduh Template -->
            <a href="{{ route('download.template') }}" class="btn btn-primary d-flex align-items-center"
                title="Download Template" style="height: 40px;">
                <iconify-icon icon="mdi:download" style="font-size: 20px; margin-right: 8px;"></iconify-icon>
                Template File
            </a>

            <!-- Form untuk Unggah File Excel -->
            <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data"
                class="d-flex align-items-center gap-2" id="uploadForm">
                @csrf
                <div class="input-group mb-0">
                    <input type="file" name="file" id="file" required class="form-control"
                        aria-label="Unggah File Excel" style="width: 235px;">
                    {{-- <button type="submit" class="btn btn-primary" id="uploadButton" title="Click to Upload">
                        <iconify-icon id="uploadIcon" icon="mdi:upload" style="font-size: 20px;"></iconify-icon>
                    </button> --}}
                </div>
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

        <div id="loadingIndicator" style="display: none;">Loading...</div>

        <!-- Container for preview table -->
        <div id="previewContainer" style="display: none;">
            <hr class="col-span-10 my-3">
            <div class="d-flex align-items-center justify-content-between" style="position: relative;">
                <button type="submit" class="btn btn-primary" id="uploadButton" title="Click to Upload" style="position: absolute; top: 0; left: 0;">
                    <iconify-icon id="uploadIcon" icon="mdi:upload" style="font-size: 20px; margin-right: 8px;"></iconify-icon> Import Data
                </button>
                <h5 class="mt-3" style="color: #26116b; text-align: center; flex-grow: 1;">Imported Data</h5>
            </div>
            
            <table id="previewTable" class="table table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 5px;">No</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Serial Number</th>
                        <th>Status Item</th>
                        <th>Requirement</th>
                        <th>Kesalahan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <!-- Pagination controls -->
            <div id="pagination" class="mt-3"></div>
            <hr class="col-span-10 my-3">
        </div>

        <table class="table table-bordered table-striped table-hover" id="barang-table" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>No</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Date of Entry</th>
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

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function() {
            const uploadIcon = document.getElementById('uploadIcon');

            // Ganti ikon menjadi loading dan tambahkan kelas loading
            uploadIcon.setAttribute('icon', 'mdi:sync');
            uploadIcon.classList.add('loading');
        });
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
    // Inisialisasi DataTable
    const table = $('#previewTable').DataTable({
        paging: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        language: {
            error: ''
        },
        columnDefs: [{
            targets: 0,
            orderable: false,
            searchable: false,
        }]
    });

    // Mengatur event listener untuk input file
    document.getElementById('file').addEventListener('change', function() {
        // Memanggil fungsi previewData ketika file dipilih
        if (this.files.length > 0) {
            previewData(); // Panggil fungsi previewData ketika file dipilih
        }
    });

    // Fungsi untuk melakukan preview data dari file yang diunggah
    window.previewData = async function() {
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];

        // Memastikan ada file yang dipilih
        if (!file) {
            alert('Silakan pilih file terlebih dahulu!');
            return;
        }

        // Tampilkan indikator loading
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';

        const reader = new FileReader();
        reader.onload = async function(e) {
            const data = e.target.result;
            const workbook = XLSX.read(data, {
                type: 'binary'
            });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                header: 1
            });

            console.log('Data dari Excel:', jsonData); // Log data dari Excel

            // Mengosongkan tabel preview
            table.clear();

            // Panggil fungsi untuk validasi serial number menggunakan API
            const rowErrors = await checkExistingSerialNumbers(jsonData.slice(1)); // Validasi kesalahan

            console.log('Row Errors:', rowErrors); // Log kesalahan yang ditemukan

            // Menyimpan baris dengan dan tanpa kesalahan
            const rowsWithErrors = []; // Menyimpan baris dengan kesalahan
            const rowsWithoutErrors = []; // Menyimpan baris tanpa kesalahan

            // Mengolah data untuk ditambahkan ke tabel
            jsonData.slice(1).forEach((row, index) => {
                const errorMessage = rowErrors[index] || ''; // Ambil pesan kesalahan untuk baris ini
                const rowData = [
                    '', // Placeholder untuk nomor urut (akan diupdate nanti)
                    row[0] || '', // Barang ID
                    row[1] || '', // Keterangan
                    row[2] || '', // Serial Number
                    row[3] || '', // Kondisi Barang
                    row[4] || '', // Kelengkapan
                    errorMessage // Kesalahan
                ];

                if (errorMessage) {
                    rowsWithErrors.push(rowData); // Jika ada kesalahan, masukkan ke array rowsWithErrors
                } else {
                    rowsWithoutErrors.push(rowData); // Jika tidak ada kesalahan, masukkan ke array rowsWithoutErrors
                }
            });

            // Gabungkan kedua array, rowsWithErrors di atas
            const combinedRows = [...rowsWithErrors, ...rowsWithoutErrors];

            // Menambahkan data ke DataTable dengan pewarnaan
            combinedRows.forEach((row) => {
                const rowNode = table.row.add(row).draw(false).node(); // Tambahkan baris ke DataTable
                if (row[6]) { // Cek jika ada kesalahan
                    // Menggunakan atribut style untuk mengatur warna teks kolom kesalahan
                    $(rowNode).find('td').last().css('color', '#f00'); // Ganti dengan warna yang Anda inginkan
                }
            });

            // Mengupdate nomor urut setelah data ditambahkan
            updateRowNumbers();

            // Tampilkan/ sembunyikan tombol upload berdasarkan hasil validasi
            toggleUploadButton(rowErrors);

            // Menampilkan kontainer pratinjau
            document.getElementById('previewContainer').style.display = 'block';

            // Sembunyikan indikator loading
            loadingIndicator.style.display = 'none';
        };

        reader.onerror = function(error) {
            console.error('Error reading file:', error);
            alert('Error reading file. Please try again.');
            loadingIndicator.style.display = 'none'; // Sembunyikan loading indicator
        };

        reader.readAsBinaryString(file);
    };

    // Fungsi untuk mengecek serial number yang ada di database melalui API
    async function checkExistingSerialNumbers(dataRows) {
        try {
            // Panggil API untuk mendapatkan serial number yang sudah ada
            const response = await fetch('https://doaibutiri.my.id/gudang/api/serialnumber', {
                method: 'GET', // Gunakan GET untuk mendapatkan data
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.text(); // Menangkap data kesalahan sebagai teks
                console.error('Error Response:', errorData); // Log error response
                throw new Error(`HTTP error! status: ${response.status}`); // Tangkap kesalahan jika tidak OK
            }

            const existingData = await response.json(); // Ambil respons sebagai JSON
            const existingNumbers = existingData.map(item => item.serial_number); // Ambil hanya serial number
            console.log('Existing Serial Numbers:', existingNumbers); // Log serial numbers yang ada

            const errors = [];

            dataRows.forEach(row => {
                // Cek apakah serial number ada dan bukan undefined atau null
                const serialNumber = row[2]; // Ambil serial number dari baris saat ini

                if (serialNumber && existingNumbers.includes(serialNumber.toString())) {
                    errors.push(`Serial number sudah terpakai`); // Simpan pesan kesalahan
                } else {
                    errors.push(''); // Jika tidak ada kesalahan, tambahkan string kosong
                }
            });

            return errors; // Kembalikan array pesan kesalahan
        } catch (error) {
            console.error('Error fetching serial numbers:', error);
            return dataRows.map(() => 'Error checking serial numbers'); // Kembalikan error jika terjadi masalah
        }
    }

    // Fungsi untuk menampilkan/ menyembunyikan tombol upload berdasarkan hasil validasi
    function toggleUploadButton(rowErrors) {
        const uploadButton = document.getElementById('uploadButton');
        const hasErrors = rowErrors.some(error => error !== ''); // Cek apakah ada error
        if (hasErrors) {
            uploadButton.classList.add('d-none'); // Sembunyikan tombol upload jika ada kesalahan
        } else {
            uploadButton.classList.remove('d-none'); // Tampilkan tombol upload jika tidak ada kesalahan
        }
    }

    // Fungsi untuk mengupdate nomor urut
    function updateRowNumbers() {
        table.rows().every(function(rowIdx) {
            this.data()[0] = rowIdx + 1;
            this.invalidate();
        });
        table.draw();
    }

    // Event listener untuk tombol upload
    document.getElementById('uploadButton').addEventListener('click', async function(event) {
        event.preventDefault(); // Mencegah default form submission

        // Ambil data dari tabel
        const rows = table.rows().data().toArray();
        const payload = rows.map(row => ({
            barangId: row[1],
            keterangan: row[2],
            serialNumber: row[3],
            kondisiBarang: row[4],
            kelengkapan: row[5]
        }));

        // Kirim data ke server menggunakan fetch
        try {
            const response = await fetch('YOUR_API_ENDPOINT_HERE', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            console.log('Upload Result:', result);
            alert('Data berhasil diupload!'); // Notifikasi keberhasilan upload
            table.clear().draw(); // Kosongkan tabel setelah upload
            document.getElementById('file').value = ''; // Reset input file
        } catch (error) {
            console.error('Error uploading data:', error);
            alert('Terjadi kesalahan saat mengupload data. Silakan coba lagi.'); // Notifikasi error
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
                                                    <div class="col-8">
                                                        ${namaJenisBarang !== null && namaJenisBarang !== '' && namaJenisBarang !== 'null' ? namaJenisBarang : '—'}
                                                    </div>
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
                                                    <div class="col-8">
                                                        ${keteranganBarang !== null && keteranganBarang !== '' && keteranganBarang !== 'null' ? keteranganBarang : '—'}
                                                    </div>
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
