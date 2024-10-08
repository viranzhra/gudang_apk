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

        <table class="table table-bordered table-striped table-hover" id="outbound-table" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th class="d-flex justify-content-center align-items-center">No</th>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#outbound-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ config('app.api_url') }}/barangkeluar',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    dataSrc: function(json) {
                        // Cek apakah ada data
                        if (json.data && json.data.data) {
                            return json.data.data; // Mengambil data dari json
                        } else {
                            return []; // Mengembalikan array kosong jika tidak ada data
                        }
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Ajax error:', xhr
                        .responseText); // Menampilkan detail error di console
                        alert('Error fetching data: ' + xhr.status + ' ' + xhr
                        .statusText); // Menampilkan alert dengan status error
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_customer',
                        name: 'customer.nama',
                        defaultContent: 'Data tidak tersedia'
                    },
                    {
                        data: 'nama_keperluan',
                        name: 'keperluan.nama',
                        defaultContent: '0'
                    },
                    {
                        data: 'jumlah_permintaan',
                        name: 'permintaan_barang_keluar.jumlah',
                        defaultContent: '-'
                    },
                    {
                        data: 'tanggal_awal',
                        name: 'permintaan_barang_keluar.tanggal',
                        defaultContent: '-'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
                                <div class="d-flex">
                                    <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                        <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                    </button>
                                </div>`;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // Event listener for detail button
            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');
                // Fetch detail data and show modal
                fetch(`/barangkeluar/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate modal content (implement the modal structure here)
                        console.log(data);
                    });
            });
        });
    </script>
@endsection
