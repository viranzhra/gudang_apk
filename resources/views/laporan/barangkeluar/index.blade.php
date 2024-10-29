@extends('layouts.navigation')

@section('content')
<style>
<<<<<<< HEAD
    .btn-action {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .icon-edit, .icon-delete, .icon-detail {
        color: #ffffff;
        font-size: 18px;
        width: 30px;
        height: 30px;
=======
    /* Button and icon styles */
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-delete, .icon-detail {
        color: #ffffff; 
        font-size: 18px;
        width: 30px; 
        height: 30px; 
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
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
        background-color: #000000;
    }

<<<<<<< HEAD
    .icon-pdf, .icon-excel {
        color: #ffffff;
        font-size: 25px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-right: 5px;
    }

    .icon-pdf {
        background-color: #dc3545;
    }

    .icon-excel {
        background-color: #28a745;
    }

    .filter-export {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-date {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-date input {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ced4da;
=======
    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
    }
</style>

<div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
    <h4 class="mt-3" style="color: #8a8a8a;">Outbound Item</h4>
<<<<<<< HEAD

    <!-- Filter and Export Buttons -->
    <div class="filter-export">
        <!-- Date Range Filter -->
        <div class="filter-date">
            <label for="startDate">From:</label>
            <input type="text" id="startDate" class="datepicker" placeholder="Start Date">
            
            <label for="endDate">To:</label>
            <input type="text" id="endDate" class="datepicker" placeholder="End Date">
            
            <button id="filterBtn" class="btn btn-primary">Filter</button>
        </div>

        <!-- Export to Excel Button -->
        <a href="#" id="exportExcelBtn" class="btn-action" title="Download Excel">
            <div class="icon-excel">
                <iconify-icon icon="mdi:file-excel"></iconify-icon>
            </div>
        </a>
    </div>

=======
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
        {{-- Uncomment to add buttons if needed
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">Add</a>
        <button id="deleteSelected" class="btn btn-danger d-none">Delete Selected</button>
        --}}
    </div>    
    
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
    <table class="table table-bordered table-striped table-hover" id="barangkeluar" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 25px;">No</th>
                <th>Customer Name</th>
<<<<<<< HEAD
                <th>Amount</th>
                <th>Purposes</th>
                <th>Date</th>
=======
                <th>Purpose</th>
                <th>Date</th>
                <th>Amount</th>
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<<<<<<< HEAD
<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Outbound Item Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailList">
                    <!-- Data will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI (for Datepicker) -->
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
=======
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 4 integration -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<<<<<<< HEAD
<!-- Script for initializing DataTables and Datepicker -->
<script>
    $(document).ready(function() {
        // Initialize Datepicker
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'  // Set date format to match Y-m-d
        });

=======
<!-- Script for initializing DataTables and handling actions -->
<script>
    $(document).ready(function() {
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
        // Initialize DataTables
        const table = $('#barangkeluar').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
<<<<<<< HEAD
                url: 'https://doaibutiri.my.id/gudang/api/laporan/barangkeluar',
                type: 'GET',
                data: function(d) {
                    d.search = $('input[type="search"]').val();
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                },
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}',
=======
                url: 'https://doaibutiri.my.id/gudang/api/laporan/barangkeluar', // Your API URL
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}', // Pass the token correctly
                },
                data: function(d) {
                    d.search = $('input[type="search"]').val(); // Search input from DataTables
                    d.start_date = $('#startDate').val(); // Get the start date value
                    d.end_date = $('#endDate').val(); // Get the end date value
                },
                dataSrc: function(json) {
                    return json.data.data; // Access the correct data array for DataTables
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
                },
                error: function(xhr, error, code) {
                    console.error("Error fetching data:", xhr.responseText);
                    alert("Failed to load data. Please check the API or your token.");
                }
            },
            columns: [
                { data: null, orderable: false, render: function(data, type, row, meta) {
<<<<<<< HEAD
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama_customer' },
                { data: 'jumlah' },
                { data: 'nama_keperluan' },
                { data: 'tanggal_awal' },
=======
                    return meta.row + meta.settings._iDisplayStart + 1; // Row numbering
                }},
                { data: 'nama_customer' },
                { data: 'nama_keperluan' },
                { data: 'tanggal', render: function(data) {
                    return new Date(data).toLocaleDateString(); // Format the date
                }},
                { data: 'jumlah' },
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
<<<<<<< HEAD
                            <button class="btn-action detail-btn" data-id="${data.permintaan_barang_keluar_id}">
=======
                            <button class="btn-action view-btn" data-id="${data.permintaan_barang_keluar_id}" data-nama="${data.nama_customer}" data-keperluan="${data.nama_keperluan}" data-jumlah="${data.jumlah}">
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
                                <div class="icon-detail"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                            </button>
                        `;
                    }
                }
<<<<<<< HEAD
            ]
        });

        // Filter button functionality
        $('#filterBtn').on('click', function() {
            table.ajax.reload();  // Reload the table when the filter button is clicked
        });

        // Export to Excel button functionality
        $('#exportExcelBtn').on('click', function(e) {
            e.preventDefault();

            // Get date range values
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            // Redirect to export route with the start and end date
            let exportUrl = `/export-barang-keluar?start_date=${startDate}&end_date=${endDate}`;
            window.location.href = exportUrl;  // Perform export by navigating to the URL
        });

        // Detail button functionality
        $(document).on('click', '.detail-btn', function() {
            const permintaanId = $(this).data('id'); // Get the ID from the button

            // Clear existing list content
            $('#detailList').empty();

            // Fetch details from the server
            $.ajax({
                url: `https://doaibutiri.my.id/gudang/api/laporan/barangkeluar/${permintaanId}`,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}',
                },
                success: function(data) {
                    if (data.length > 0) {
                        // Populate the modal with fetched data as a list
                        data.forEach(item => {
                            $('#detailList').append(`
                                <div class="detail-item">
                                    <h6>Serial Number: <strong>${item.serial_number}</strong></h6>
                                    <p>Item Name: <strong>${item.nama_barang}</strong></p>
                                    <p>Item Type: <strong>${item.nama_jenis_barang}</strong></p>
                                    <p>Supplier Name: <strong>${item.nama_supplier}</strong></p>
                                    <hr>
                                </div>
                            `);
                        });
                    } else {
                        $('#detailList').append('<p class="text-center">No details available</p>');
                    }

                    // Show the modal
                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    alert('Failed to fetch details. Please try again.');
                    console.error(xhr.responseText);
                }
            });
=======
            ],
        });

        // Filter button click event to reload the DataTable based on date range
        $('#filterBtn').on('click', function() {
            table.ajax.reload(); // Reload table data with new date range
        });

        // Handling the view button click event
        $(document).on('click', '.view-btn', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const keperluan = $(this).data('keperluan');
            const jumlah = $(this).data('jumlah');
            
            // Set the data into modal for viewing
            $('#viewBarangId').text(id);
            $('#viewNamaBarang').text(nama);
            $('#viewJenisBarang').text(keperluan);
            $('#viewJumlahBarang').text(jumlah);
            
            // Show the modal
            $('#viewModal').modal('show');
>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
        });
    });
</script>

<<<<<<< HEAD
=======
<!-- Modal for viewing item details -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Outbound Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Item:</strong> <span id="viewBarangId"></span></p>
                <p><strong>Customer Name:</strong> <span id="viewNamaBarang"></span></p>
                <p><strong>Purpose:</strong> <span id="viewJenisBarang"></span></p>
                <p><strong>Amount:</strong> <span id="viewJumlahBarang"></span></p>
                <span class="sidebar-divider lg"></span>
                <p><strong>SN / Kondisi</strong></p>
                <p id="viewSNKondisi"></p> <!-- Add a section for serial number or condition if needed -->
            </div>
        </div>
    </div>
</div>

>>>>>>> 94279b2e0f690329bccc69313aa8e0c577b41aa9
@endsection
