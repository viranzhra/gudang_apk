@extends('layouts.navigation')

@section('content')
    <style>
        /* Existing styles */
        .select-item:checked {
            accent-color: blue;
        }

        .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .icon-edit,
        .icon-delete {
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
            background-color: #01578d;
        }

        .icon-delete {
            background-color: #910a0a;
        }

        #notification {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            padding: 15px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            text-align: left;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 13px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Outbound Item</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px"></div>

        <table class="table table-bordered table-striped table-hover" id="outboundTable" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Purpose</th>
                    <th>Quantity</th>
                    <th>Request Date</th>
                    <th>Aksi</th>
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


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Tabel ditemukan. Memulai DataTables.');
            var table = $('#outboundTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'https://doaibutiri.my.id/gudang/api/barangkeluar',
                    error: function (xhr, error, thrown) {
                        console.error('Ajax error:', error);
                        alert('An error occurred while fetching data. Please try again later.');
                    }
                },
                columns: [
                    {
                        data: 'id',
                        sortable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama_customer',
                        defaultContent: 'Data tidak tersedia'
                    },
                    {
                        data: 'nama_keperluan',
                        defaultContent: '-'
                    },
                    {
                        data: 'jumlah',
                        defaultContent: '0'
                    },
                    {
                        data: 'tanggal',
                        defaultContent: '-'
                    },
                    {
                        data: 'permintaan_barang_keluar_id',
                        orderable: false,
                        render: function(data, type, row) {
                            return `<div class="d-flex">
                                <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                    <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                </button>
                            </div>`;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    $('.btn-detail').on('click', function() {
                        var id = $(this).data('id');
                        var rowData = table.row($(this).closest('tr')).data();
                        var detailHtml = '';
                        
                        if (rowData && rowData.detail) {
                            rowData.detail.forEach(function(item) {
                                detailHtml += `<tr>
                                    <td>${item.serial_number}</td>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.nama_jenis_barang}</td>
                                    <td>${item.nama_supplier}</td>
                                </tr>`;
                            });
                        }
                        
                        $('#modalDetailBody').html(detailHtml);
                        $('#detailModal').modal('show');
                    });
                }
            });
        });
    </script>

    <script>
        // Detail button functionality
        $(document).on('click', '.detail-btn', function() {
            const permintaanId = $(this).data('id'); // Get the ID from the button

            // Clear existing list content
            $('#detailList').empty();

            // Fetch details from the server
            $.ajax({
                url: https://doaibutiri.my.id/gudang/api/laporan/barangkeluar/${permintaanId},
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
        });
    </script>
@endsection
