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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>


    <script>

    $(document).ready(function() {
        // Initialize DataTable
        $('#outboundTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ config('app.api_url') }}/barangkeluar', // URL API
                type: 'GET', // Metode HTTP yang digunakan
                headers: {
                    'Authorization': 'Bearer {{ session('token') }}' // Menggunakan token dari session
                },
                data: function (d) {
                    // Menambahkan parameter pencarian jika ada
                    d.search = d.search.value; // Ini akan mengirimkan nilai pencarian ke API
                },
                error: function (xhr, error, thrown) {
                    console.error('Ajax error:', xhr.responseText); // Menampilkan detail error di console
                    alert('Error fetching data: ' + xhr.status + ' ' + xhr.statusText); // Menampilkan alert dengan status error
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex', // Kolom ini harus ada jika kamu menggunakannya
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_customer', // Menyesuaikan dengan field dari API
                    defaultContent: 'Data tidak tersedia'
                },
                {
                    data: 'nama_keperluan', // Menyesuaikan dengan field dari API
                    defaultContent: '0'
                },
                {
                    data: 'jumlah', // Menyesuaikan dengan field dari API
                    defaultContent: '-'
                },
                {
                    data: 'tanggal_awal', // Menyesuaikan dengan field dari API
                    defaultContent: '-'
                },
                {
                    data: 'tanggal_akhir', // Menyesuaikan dengan field dari API
                    defaultContent: '-'
                },
                {
                    data: 'id', // Menggunakan ID untuk mengidentifikasi
                    orderable: false,
                    render: function (data) {
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
                [0, 'desc'] // Menyesuaikan dengan kolom yang diinginkan untuk diurutkan
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    });
    </script>

    <script>
        $(document).ready(function() {
            // Event listener for detail button
            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');
                fetch(`{{ config('app.api_url') }}/barangkeluar/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Pastikan data.detail tersedia
                        let detailHtml = '';
                        if (data.detail && Array.isArray(data.detail)) {
                            data.detail.forEach(function(detail) {
                                detailHtml += `
                        <tr>
                            <td>${detail.serial_number || 'Tidak tersedia'}</td>
                            <td>${detail.nama_barang || 'Tidak tersedia'}</td>
                            <td>${detail.nama_jenis_barang || 'Tidak tersedia'}</td>
                            <td>${detail.nama_supplier || 'Tidak tersedia'}</td>
                        </tr>`;
                            });
                        } else {
                            detailHtml = '<tr><td colspan="4">Detail tidak tersedia</td></tr>';
                        }

                        // Tampilkan detail dalam modal
                        $('#modalDetailBody').html(detailHtml);
                        $('#detailModal').modal('show');
                    })
                    .catch(error => {
                        console.error('Error fetching detail:', error);
                        alert('Gagal mengambil detail. Silakan coba lagi.');
                    });
            });
        });
    </script>
@endsection
