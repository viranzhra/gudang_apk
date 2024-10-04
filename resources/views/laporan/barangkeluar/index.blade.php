@extends('layouts.navigation')

@section('content')
<style>
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

    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
    }
</style>

<div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
    <h4 class="mt-3" style="color: #8a8a8a;">Outbound Item</h4>
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
        {{-- Uncomment to add buttons if needed
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">Add</a>
        <button id="deleteSelected" class="btn btn-danger d-none">Delete Selected</button>
        --}}
    </div>    
    
    <table class="table table-bordered table-striped table-hover" id="barangkeluar" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 25px;">No</th>
                <th>Customer Name</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 4 integration -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<!-- Script for initializing DataTables and handling actions -->
<script>
    $(document).ready(function() {
        // Initialize DataTables
        const table = $('#barangkeluar').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
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
                },
                error: function(xhr, error, code) {
                    console.error("Error fetching data:", xhr.responseText);
                    alert("Failed to load data. Please check the API or your token.");
                }
            },
            columns: [
                { data: null, orderable: false, render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Row numbering
                }},
                { data: 'nama_customer' },
                { data: 'nama_keperluan' },
                { data: 'tanggal', render: function(data) {
                    return new Date(data).toLocaleDateString(); // Format the date
                }},
                { data: 'jumlah' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn-action view-btn" data-id="${data.permintaan_barang_keluar_id}" data-nama="${data.nama_customer}" data-keperluan="${data.nama_keperluan}" data-jumlah="${data.jumlah}">
                                <div class="icon-detail"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                            </button>
                        `;
                    }
                }
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
        });
    });
</script>

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

@endsection
