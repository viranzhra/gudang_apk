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

    <div class="container mt-3" style="padding: 40px; width: 1160px;">
        <h4 class="mt-3" style="color: #8a8a8a;">Outbound Item</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px"></div>

        <table class="table table-bordered table-striped table-hover" id="OutboundItem" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 20px"><input type="checkbox" id="select-all"></th>
                    <th style="width: 25px;">No</th>
                    <th>Type Item</th>
                    <th>Description</th>
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

    <!-- Script untuk inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            const table = $('#OutboundItem').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'https://doaibutiri.my.id/gudang/api/barangkeluar',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    }
                },
                columns: [{
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="select-item" value="${data}">`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            return `
                            <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                    <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                            </button>
                        `;
                        }
                    }
                ],
            });

        });
    </script>
@endsection
