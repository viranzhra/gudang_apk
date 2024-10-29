@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-delete {
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
        background-color: #000000;
    }
</style>

<div class="container mt-3" style="padding: 40px; width: 1160px;">     
    <h4 class="mt-3" style="color: #8a8a8a;">Incoming Item</h4>
    <table class="table table-bordered table-striped table-hover" id="barangmasuk" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Barang</th>
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
        const table = $('#barangmasuk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'https://doaibutiri.my.id/gudang/api/laporan/barangmasuk', // Your API URL
                type: 'GET', // Ensure the correct HTTP method is used
                data: function(d) {
                    // Send date range and search inputs
                    d.search = $('input[type="search"]').val();
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                },
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}', // Ensure token is passed correctly
                },
                error: function(xhr, error, code) {
                    // Handle any errors with the AJAX request
                    console.error("Error fetching data:", xhr.responseText);
                    alert("Failed to load data. Please check the API or your token.");
                }
            },
            columns: [
                { data: null, orderable: false, render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama_barang' },
                { data: 'jumlah' },
                { data: 'keterangan' },
                { data: 'tanggal' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn-action edit-btn" data-id="${data.id}" data-nama="${data.nama_barang}" data-deskripsi="${data.jumlah}">
                                <div class="icon-edit"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
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

        // Handling the edit button click event
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const deskripsi = $(this).data('deskripsi');
            $('#editForm').attr('action', `https://doaibutiri.my.id/gudang/api/laporan/barangmasuk/${id}`);
            $('#edit-nama').val(nama);
            $('#edit-deskripsi').val(deskripsi);
            $('#editData').modal('show');
        });
    });
</script>
@endsection