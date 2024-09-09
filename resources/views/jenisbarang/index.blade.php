@extends('layouts.navigation')

@section('content')
<div class="container mt-3" style="padding: 30px; padding-bottom: 13px; width: 1160px;">
    <h2 style="padding-bottom: 25px; color: #8a8a8a;">Data Jenis Barang</h2>
    <table class="table table-bordered table-striped table-hover" id="jenisBarangTable" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Nama Jenis Barang</th>
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

<!-- Script untuk inisialisasi DataTables -->
<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#jenisBarangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jenisbarang.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama', name: 'nama'}
            ],
            responsive: true,
            language: {
                paginate: {
                    next: 'Next →', 
                    previous: '← Previous' 
                },
                search: "Search:",
                lengthMenu: "Display _MENU_ records per page",
                zeroRecords: "No matching records found",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries available",
            }
        });
    });
</script>
@endsection
