@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-view,{
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

    .icon-edit {
        background-color: #000000;
    }

    .icon-view {
        background-color: #007bff; /* Blue color for view icon */
    }

    .icon-pdf {
        background-color: #dc3545; /* Red color for PDF export */
    }

    .icon-excel {
        background-color: #28a745; /* Green color for Excel export */
    }

    /* Additional styles to align elements */
    .filter-export {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 15px;
        margin-bottom: 20px;
    }

    .date-range {
        display: flex;
        align-items: center;
        gap: 10px; /* Space between the inputs */
    }

    .date-range input {
        width: 120px; /* Adjust width for date inputs */
    }
</style>

<div class="container mt-3" style="padding: 40px; width: 1160px;">     
    <h4 class="mt-3" style="color: #8a8a8a;">Outgoing Item</h4>

    <!-- Filter and Export Buttons -->
    <div class="filter-export">
        <div class="date-range">
            <p><strong>Dari:</strong></p>
            <input type="date" id="startDate" class="form-control" placeholder="Start Date">
            <p><strong>Sampai:</strong></p>
            <input type="date" id="endDate" class="form-control" placeholder="End Date">
            <button id="filterBtn" class="btn btn-primary">Filter</button>
        </div>

        <!-- Export to PDF Button -->
        <a href="{{ url('api/laporan/barangkeluar/export/pdf') }}" class="btn-action" title="Download PDF">
            <div class="icon-pdf">
                <iconify-icon icon="mdi:file-pdf"></iconify-icon>
            </div>
        </a>

        <!-- Export to Excel Button -->
        <a href="{{ url('api/laporan/barangkeluar/export/excel') }}" class="btn-action" title="Download Excel">
            <div class="icon-excel">
                <iconify-icon icon="mdi:file-excel"></iconify-icon>
            </div>
        </a>
    </div>

    <table class="table table-bordered table-striped table-hover" id="barangkeluar" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 25px;">No</th>
                <th>Customer Name</th>
                <th>Amount</th>
                <th>Purposes</th>
                <th>Date</th>
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

<!-- Script for initializing DataTables -->
<script>
    $(document).ready(function() {
        // Initialize DataTables
        const table = $('#barangkeluar').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'https://doaibutiri.my.id/gudang/api/laporan/barangkeluar', // Updated API URL for barang keluar
                type: 'GET',
                data: function(d) {
                    d.search = $('input[type="search"]').val();
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                },
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}',
                },
                error: function(xhr, error, code) {
                    console.error("Error fetching data:", xhr.responseText);
                    alert("Failed to load data. Please check the API or your token.");
                }
            },
            columns: [
                { data: null, orderable: false, render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama_customer' },
                { data: 'jumlah' },
                { data: 'nama_keperluan' },
                { data: 'tanggal_akhir' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn-action edit-btn" data-id="${data.permintaan_barang_keluar_id}" data-nama="${data.nama_barang}" data-deskripsi="${data.jumlah}">
                                <div class="icon-edit"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                            </button>
                        `;
                    }
                }
            ]
        });
    });
</script>
@endsection
