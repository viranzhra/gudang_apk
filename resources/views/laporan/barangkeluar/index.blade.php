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

        .date-input {
            padding-right: 30px; /* Add padding to avoid text overlapping with the icon */
            background-image: url('data:image/svg+xml;utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="%23999999" viewBox="0 0 256 256"%3E%3Cpath d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Zm-68-76a12,12,0,1,1-12-12A12,12,0,0,1,140,132Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,184,132ZM96,172a12,12,0,1,1-12-12A12,12,0,0,1,96,172Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,140,172Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,184,172Z"%3E%3C/path%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center; /* Position the icon on the right */
            background-size: 20px; /* Adjust the icon size */
            border: 1px solid #ced4da; /* Bootstrap default border color */
            border-radius: 0.25rem; /* Bootstrap default border radius */
            width: 200px; /* Adjust the width as needed */
            /* Optional: add min-width if needed for responsiveness */
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Outbound Item</h4>

        <!-- Filter and Export Buttons -->
        <div class="filter-export">
            <!-- Date Range Filter -->
            <div class="filter-date">
                <label for="startDate">From:</label>
                <input style="height: 38px;" type="text" id="startDate" class="datepicker form-control date-input" placeholder="Start Date">
        
                <label for="endDate">To:</label>
                <input style="height: 38px;" type="text" id="endDate" class="datepicker form-control date-input" placeholder="End Date">
        
                <button style="border-radius: 10px;" id="filterBtn" class="btn btn-primary d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10.97 20.344l-1.97 .656v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v1.5" />
                        <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                    </svg>
                    <span style="margin-left: 3px;">Filter</span>
                </button>
            </div>

            <!-- Export to Excel Button -->
            <a href="#" id="exportExcelBtn" class="btn-action" title="Download Excel">
                <div class="icon-excel">
                    {{-- <iconify-icon icon="mdi:file-excel"></iconify-icon> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 256 256"><path d="M156,208a8,8,0,0,1-8,8H120a8,8,0,0,1-8-8V152a8,8,0,0,1,16,0v48h20A8,8,0,0,1,156,208ZM92.65,145.49a8,8,0,0,0-11.16,1.86L68,166.24,54.51,147.35a8,8,0,1,0-13,9.3L58.17,180,41.49,203.35a8,8,0,0,0,13,9.3L68,193.76l13.49,18.89a8,8,0,0,0,13-9.3L77.83,180l16.68-23.35A8,8,0,0,0,92.65,145.49Zm98.94,25.82c-4-1.16-8.14-2.35-10.45-3.84-1.25-.82-1.23-1-1.12-1.9a4.54,4.54,0,0,1,2-3.67c4.6-3.12,15.34-1.72,19.82-.56a8,8,0,0,0,4.07-15.48c-2.11-.55-21-5.22-32.83,2.76a20.58,20.58,0,0,0-8.95,14.95c-2,15.88,13.65,20.41,23,23.11,12.06,3.49,13.12,4.92,12.78,7.59-.31,2.41-1.26,3.33-2.15,3.93-4.6,3.06-15.16,1.55-19.54.35A8,8,0,0,0,173.93,214a60.63,60.63,0,0,0,15.19,2c5.82,0,12.3-1,17.49-4.46a20.81,20.81,0,0,0,9.18-15.23C218,179,201.48,174.17,191.59,171.31ZM40,112V40A16,16,0,0,1,56,24h96a8,8,0,0,1,5.66,2.34l56,56A8,8,0,0,1,216,88v24a8,8,0,1,1-16,0V96H152a8,8,0,0,1-8-8V40H56v72a8,8,0,0,1-16,0ZM160,80h28.68L160,51.31Z"></path></svg>
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel" style="margin-left: 40%; font-weight: bold;">Outbound Item Detail</h5>
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <!-- Date -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            const table = $('#barangkeluar').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'https://doaibutiri.my.id/gudang/api/laporan/barangkeluar',
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
                columns: [{
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama_customer'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'nama_keperluan'
                    },
                    // {
                    //     data: 'tanggal_awal'
                    // },
                    {
    data: 'tanggal_awal',
    render: function(data, type, row) {
        moment.locale('id'); // Set locale to Indonesian
        return moment(data).format('dddd, DD MMMM YYYY'); // Format to Indonesian date
    }
},
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            return `
                            <button class="btn-action detail-btn" data-id="${data.permintaan_barang_keluar_id}">
                                <div class="icon-detail"><iconify-icon icon="mdi:file-document-outline"></iconify-icon></div>
                            </button>
                        `;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ],
            });

            // Filter button functionality
            $('#filterBtn').on('click', function() {
                table.ajax.reload(); // Reload the table when the filter button is clicked
            });

            // Export to Excel button functionality
            $('#exportExcelBtn').on('click', function(e) {
                e.preventDefault();

                // Get date range values
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                // Redirect to export route with the start and end date
                let exportUrl = `/export-barang-keluar?start_date=${startDate}&end_date=${endDate}`;
                window.location.href = exportUrl; // Perform export by navigating to the URL
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
                                                <div class="grid grid-cols-10 gap-2">
                                                <div class="row">
                                                    <div class="col-3"><strong>Serial Number</strong></div>:
                                                    <div class="col-8">${item.serial_number}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"><strong>Item Name</strong></div>:
                                                    <div class="col-8">${item.nama_barang}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"><strong>Item Type</strong></div>:
                                                    <div class="col-8">${item.nama_jenis_barang}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"><strong>Supplier Name</strong></div>:
                                                    <div class="col-8">${item.nama_supplier}</div>
                                                </div>
                                                <hr>
                                            </div>
                            `);
                            });
                        } else {
                            $('#detailList').append(
                                '<p class="text-center">No details available</p>');
                        }

                        // Show the modal
                        $('#detailModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Failed to fetch details. Please try again.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
