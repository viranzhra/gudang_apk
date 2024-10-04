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
    <h4 class="mt-3" style="color: #8a8a8a;">Stock Item</h4>
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
        {{-- <!-- Add Button -->
        <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
            <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
            Add
        </a> --}}
    
        {{-- <!-- Delete Selected Button -->
        <button id="deleteSelected" class="btn btn-danger d-none" style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
            <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
            Delete Selected
        </button> --}}
    </div>    
    
    <table class="table table-bordered table-striped table-hover" id="stok" width="100%">
        <thead class="thead-dark">
            <tr>
                {{-- <th style="width: 20px"><input type="checkbox" id="select-all"></th> --}}
                <th style="width: 25px;">No</th>
                <th>Item</th>
                <th>Item Type</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal untuk menampilkan detail barang -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Detail Stock Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID Item:</strong> <span id="viewBarangId"></span></p>
          <p><strong>Item Name:</strong> <span id="viewNamaBarang"></span></p>
          <p><strong>Item Type:</strong> <span id="viewJenisBarang"></span></p>
          <p><strong>Amount:</strong> <span id="viewJumlahBarang"></span></p>
          <span class="sidebar-divider lg"></span>
          <p><strong>SN / Kondisi</strong></p>
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

<!-- Script untuk inisialisasi DataTables dan modal -->
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        const table = $('#stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'https://doaibutiri.my.id/gudang/api/laporan/stok', // Use Laravel route helper
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}'
                },
                data: function(d) {
                    d.search = $('input[type="search"]').val(); // Default search input from DataTables
                    d.start_date = $('input[name="start_date"]').val(); // Optional additional search filters
                    d.end_date = $('input[name="end_date"]').val();
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama_barang' },
                { data: 'nama_jenis_barang' },
                { data: 'jumlah' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                        <button class="btn-action view-btn" data-id="${data.barang_id}" data-nama="${data.nama_barang}" data-jenis="${data.nama_jenis_barang}" data-jumlah="${data.jumlah}">
                            <div class="icon-detail"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                        </button>
                        `;
                    }
                }
            ],
            // language: {
            //     emptyTable: "Tidak ada data yang tersedia",
            //     search: "Cari:",
            //     lengthMenu: "Tampilkan _MENU_ entri",
            //     paginate: {
            //         first: "Pertama",
            //         last: "Terakhir",
            //         next: "Selanjutnya",
            //         previous: "Sebelumnya"
            //     }
            // }
        });

        // Saat tombol detail ditekan
    $(document).on('click', '.view-btn', function() {
        var barangId = $(this).data('id');
        var namaBarang = $(this).data('nama');
        var jenisBarang = $(this).data('jenis');
        var jumlahBarang = $(this).data('jumlah');
        
        // Set data ke dalam modal untuk dilihat
        $('#viewBarangId').text(barangId);
        $('#viewNamaBarang').text(namaBarang);
        $('#viewJenisBarang').text(jenisBarang);
        $('#viewJumlahBarang').text(jumlahBarang);
        
        // Tampilkan modal
        $('#viewModal').modal('show');
    });
});
</script>
@endsection
