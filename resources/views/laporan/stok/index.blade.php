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

<div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 13px; width: 1160px;">
    <h4 class="mt-3" style="color: #8a8a8a;">Stock Item</h4>
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 40px">
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

<!-- Modal for showing stock item details -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel" style="margin-left: 30%; font-weight: bold;">Detail Stock Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3"><strong>ID Item</strong></div>:
                    <div class="col-8"><span id="viewBarangId"></span></div>
                </div>
                <div class="row">
                    <div class="col-3"><strong>Item Name</strong></div>:
                    <div class="col-8"><span id="viewNamaBarang"></span></div>
                </div>
                <div class="row">
                    <div class="col-3"><strong>Item Type</strong></div>:
                    <div class="col-8"><span id="viewJenisBarang"></span></div>
                </div>
                <div class="row">
                    <div class="col-3"><strong>Amount</strong></div>:
                    <div class="col-8"><span id="viewJumlahBarang"></span></div>
                </div>
                <hr>
            <h5 style="padding-bottom: 10px; margin-left: 25%; font-weight: bold;"><strong>Serial Numbers & Status</strong></h5>
            <div id="serialDetails">
                <!-- Serial numbers and status will be appended here dynamically -->
            </div>
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
                    className: 'text-center',
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
            order: [
                    [2, 'asc']
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

// When the view detail button is clicked
$(document).on('click', '.view-btn', function() {
        var barangId = $(this).data('id');
        var namaBarang = $(this).data('nama');
        var jenisBarang = $(this).data('jenis');
        var jumlahBarang = $(this).data('jumlah');
        
        // Set the static modal details
        $('#viewBarangId').text(barangId);
        $('#viewNamaBarang').text(namaBarang);
        $('#viewJenisBarang').text(jenisBarang);
        $('#viewJumlahBarang').text(jumlahBarang);

        // Clear the previous serial details
        $('#serialDetails').empty();

        // Manually trigger the modal for testing
        $('#viewModal').modal('show');

        // Make AJAX request to get the stock details
        $.ajax({
            url: `https://doaibutiri.my.id/gudang/api/laporan/stok/${barangId}`, // Update to the correct route
            type: 'GET',
            success: function(response) {
                // Loop through the response and append each serial number and status to the modal
                response.forEach(function(item) {
                    $('#serialDetails').append(`
                            <div class="grid grid-cols-10 gap-2">
                            <div class="row">
                                <div class="col-3"><strong>Serial Number</strong></div>:
                                <div class="col-8">${item.serial_number} - <span style="color: ${item.warna_status_barang};">${item.status_barang}</span></div>
                            </div>
                            <div class="row">
                                <div class="col-3"><strong>Completeness</strong></div>:
                                <div class="col-8">${item.kelengkapan || '-'}</div>
                            </div>
                            <hr>
                        </div>
                    `);
                });
                
                // Show the modal after data is loaded
                $('#viewModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error fetching details:', xhr.responseText);
            }
        });
    });
});
</script>
@endsection
