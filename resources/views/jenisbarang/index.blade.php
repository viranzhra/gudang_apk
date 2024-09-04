@extends('layouts.navigation')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Item Types</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
            Add Item Type
        </button>
    </div>

    @if (session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
    @endif

    <div class="table-responsive">
        <table id="jenisbarang-table" class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>No.</th>
                    <th>Item Type Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Add Item Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('jenisbarang.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_jenis_barang" class="form-label">Item Type Name</label>
                            <input type="text" name="nama_jenis_barang" class="form-control" id="nama_jenis_barang"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Script -->
@section('scripts')
<script src="https://code.jquery.com/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#jenisbarang-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('jenisbarang.data') }}",
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, full, meta) {
                return '<input type="checkbox" name="id[]" value="' + data + '">';
            }},
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[1, 'asc']]
    });

    $('#select-all').on('click', function(){
        var rows = $('#jenisbarang-table').DataTable().rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
});
</script>
@endsection

@endsection
