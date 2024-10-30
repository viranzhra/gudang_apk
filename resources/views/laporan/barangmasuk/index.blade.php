@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .icon-edit,
        .icon-delete,
        .icon-detail {
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

        .icon-pdf,
        .icon-excel {
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
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Incoming Item</h4>

        <!-- Filter and Export Buttons -->
        <div class="filter-export">
            <!-- Date Range Filter -->
            <div class="d-flex filter-date">
                <label for="startDate">From:</label>
                <input type="text" id="startDate" class="datepicker form-control form-control" placeholder="Start Date">
        
                <label for="endDate">To:</label>
                <input type="text" id="endDate" class="datepicker form-control form-control" placeholder="End Date">
        
                <button id="filterBtn" class="btn btn-primary">Filter</button>
            </div>

            <!-- Export to Excel Button -->
            <a href="#" id="exportExcelBtn" class="btn-action" title="Download Excel">
                <div class="icon-excel">
                    <iconify-icon icon="mdi:file-excel"></iconify-icon>
                </div>
            </a>
        </div>

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
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Incoming Item Detail</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI CSS for Datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- jQuery UI JS for Datepicker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 4 integration -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<!-- Date -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- jQuery UI (for Datepicker) -->
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<!-- DataTables JS -->

<!-- Script for initializing DataTables and Datepicker -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Flatpickr untuk kedua input tanggal
        flatpickr("#startDate", {
            dateFormat: "d-m-Y", // format tanggal (tahun-bulan-hari)
            maxDate: new Date()  // batas maksimum tanggal adalah hari ini
        });

        flatpickr("#endDate", {
            dateFormat: "d-m-Y",
            maxDate: new Date()
        });
    });
</script>
<!-- Script for initializing DataTables -->
<script>
    $(document).ready(function() {

        // Initialize DataTables
        const table = $('#barangmasuk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'https://doaibutiri.my.id/gudang/api/laporan/barangmasuk', // API URL
                type: 'GET',
                data: function(d) {
                    // Kirim rentang tanggal dan input pencarian sebagai parameter
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
                { data: 'nama_barang' },
                { data: 'jumlah' },
                { data: 'keterangan' },
                { data: 'tanggal' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn-action detail-btn" 
                                data-id="${data.barang_masuk_id}" 
                                data-nama="${data.nama_barang}" 
                                data-jumlah="${data.jumlah}" 
                                data-keterangan="${data.keterangan}" 
                                data-tanggal="${data.tanggal}">
                                <div class="icon-detail"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                            </button>
                        `;
                    }
                }
            ],
            language: {
                emptyTable: "Tidak ada data yang tersedia",
                search: "Cari:",
                lengthMenu: "",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Filter button click event to reload the DataTable based on date range
        $('#filterBtn').on('click', function() {
            table.ajax.reload(); // Reload table data with new date range
        });

   // Detail button functionality
$(document).on('click', '.detail-btn', function() {
    const permintaanId = $(this).data('id'); // Get the ID from the button

    // Clear existing list content
    $('#detailList').empty();

    // Fetch details from the server
    $.ajax({
        url: 'https://doaibutiri.my.id/gudang/api/laporan/barangmasuk/' + permintaanId, // Fixed the URL concatenation
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + '{{ session("token") }}', // Fixed token reference
        },
        success: function(data) {
            if (data.length > 0) {
                // Populate the modal with fetched data as a list
                data.forEach(item => {
                    $('#detailList').append(`
                        <div class="detail-item">
                                    <p>Item Name: <strong>${item.nama_barang}</strong></p>
                                    <p>Item Type: <strong>${item.nama_jenis_barang}</strong></p>
                                    <p>Supplier Name: <strong>${item.nama_supplier}</strong></p>
                            <h6>Serial Number: <strong>${item.serial_number}</strong></h6>
                            <p>Status Barang: 
                                <strong style="color: ${item.warna_status_barang};">
                                    ${item.status_barang}
                                </strong>
                            </p>
                            <p>Kelengkapan Barang: <strong>${item.kelengkapan_barang}</strong></p>
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
        error: function(xhr, status, error) {
            alert('Failed to fetch details. Please try again.');
            console.error('Error:', error);
            console.error('Status:', status);
            console.error(xhr.responseText);
        }
    });
});


        // Export Excel button click event
        $('#exportExcelBtn').on('click', function(e) {
            e.preventDefault();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const url = `/export-barang-masuk?start_date=${startDate}&end_date=${endDate}`;
            window.location.href = url; // Redirect to the export URL
        });

        setInterval(function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            exportExcelBtn.href = `/export-barang-masuk?start_date=${startDate}&end_date=${endDate}`;
        }, 500);
    });
</script>
@endsection
