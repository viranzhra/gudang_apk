@extends('layouts.navigation')

@section('content')
<style>
#notification{position:fixed;top:10px;right:10px;width:300px;padding:15px;border-radius:5px;z-index:9999;display:none;text-align:center;justify-content:flex-start;align-items:center;text-align:left}
/* .alert-success{background-color:#d4edda;color:#155724;border:1px solid #c3e6cb;height:80px}.alert-danger{background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;height:80px}.alert-info{background-color:#d1ecf1;color:#0c5460;border:1px solid #bee5eb;height:80px} */
</style>
    <div class="container mt-3 shadow-sm" style="padding-bottom: 15px; padding-top: 10px; width: 1160px;border-radius: 20px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <h4 style="color: black;">Stock Out Request</h4>
            <div class="d-flex gap-2">
                <!-- Add Button -->
                <a type="button" class="btn btn-primary d-flex align-items-center justify-content-center"
                    href="{{ route('permintaanbarangkeluar.create') }}" style="width: 75px; height: 35px;">
                    <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                    Add
                </a>
            </div>
        </div>

        <!-- Alert -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Terjadi kesalahan:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Table --}}
        <table id="permintaan-table" class="table table-hover table-sm text-dark pt-2" width="100%" style="font-size: 15px;">
            <thead class="thead-dark">
                <tr>
                    <th class="d-flex justify-content-center align-items-center">No</th>
                    <th>Customer</th>
                    <th>Purpose</th>
                    <th>Quantity</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-gray"></tbody>
        </table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.showDetailModal = function(id, namaCustomer, namaKeperluan, tanggalAwal, extend,
        namaTanggalAkhir, TanggalAkhir, keterangan, jumlah, status) {
        // Hapus modal sebelumnya jika ada
        const existingModal = document.getElementById('detailModal');
        if (existingModal) {
            existingModal.remove();
        }

        const modalContent = `
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Permintaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-2">
                        <div id="loadingSpinner" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="modalContent" class="d-none">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="modalFooterContent"></div>
                        <button type="button" class="d-none btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();

        // Add a delay before fetching data
        setTimeout(() => {
            // Fetch data after modal is shown
            fetch(`{{ config('app.api_url') }}/permintaanbarangkeluar/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Periksa apakah data adalah array
                    if (!Array.isArray(data)) {
                        console.error('Data yang diterima tidak sesuai dengan format yang diharapkan:', data);
                        alert('Terjadi kesalahan saat memuat detail barang.');
                        return;
                    }

                    const detailData = data; // Data yang diterima sudah dalam format array
                    const detailContent = detailData.map((detail, index) => `
                        <hr class="w-100 my-2">
                        <div class="row align-items-center">
                            <div class="col-1 text-center">
                                <div class="font-weight-bold">${index + 1}</div>
                            </div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-3 font-weight-bold">Item / Qty</div>
                                    <div class="col-9">${detail.nama_barang || '—'} — ${detail.total_barang || '—'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-3 font-weight-bold">Item Type</div>
                                    <div class="col-9">${detail.nama_jenis_barang || '—'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-3 font-weight-bold">Supplier</div>
                                    <div class="col-9">${detail.nama_supplier || '—'}</div>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    const contentHtml = `
                        <div class="row g-2 gx-3">
                            <div class="col-3 fw-bold">Penerima:</div>
                            <div class="col-9">${namaCustomer || '—'}</div>
                            <div class="col-3 fw-bold">Keperluan:</div>
                            <div class="col-9">${namaKeperluan || '—'}</div>
                            <hr class="my-2">
                            <div class="col-12 fw-bold">Tanggal</div>
                            <div class="col-3 fw-bold ps-4">Permintaan:</div>
                            <div class="col-9">${tanggalAwal || '—'}</div>
                            ${extend == 1 ? `
                                <div class="col-3 fw-bold ps-4">${namaTanggalAkhir || '—'}:</div>
                                <div class="col-9">${TanggalAkhir}</div>
                            ` : ''}
                            <hr class="my-2">
                            <div class="col-3 fw-bold">Keterangan:</div>
                            <div class="col-9">${keterangan || '—'}</div>
                            <div class="col-3 fw-bold">Jumlah:</div>
                            <div class="col-9">${jumlah || '—'}</div>
                            <div class="col-3 fw-bold">Status:</div>
                            <div class="col-9">
                                ${status === 'Ditolak' ? `<span class="text-danger">${status}</span>` :
                                  status === 'Belum Disetujui' ? `<span class="text-warning">${status}</span>` :
                                  status === 'Disetujui' ? `<span class="text-success">${status}</span>` :
                                  status}
                            </div>
                            ${detailContent}
                        </div>
                    `;

                    // Hide loading spinner and show content
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    const modalContentElement = document.getElementById('modalContent');
                    modalContentElement.innerHTML = contentHtml;
                    modalContentElement.classList.remove('d-none');

                    // Update footer content
                    const footerContent = status === 'Belum Disetujui' ? `
                        <button type="button" class="btn btn-success me-2"
                            onclick="updateStatus(${id}, 'Disetujui')"
                            data-bs-dismiss="modal">Setujui</button>
                        <button type="button" class="btn btn-danger"
                            onclick="updateStatus(${id}, 'Ditolak')"
                            data-bs-dismiss="modal">Tolak</button>
                    ` : '';
                    document.getElementById('modalFooterContent').innerHTML = footerContent;
                })
                .catch(error => {
                    console.error('Error fetching detail data:', error);
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    const modalContentElement = document.getElementById('modalContent');
                    modalContentElement.innerHTML = '<div class="text-center">Terjadi kesalahan saat memuat detail permintaan.</div>';
                    modalContentElement.classList.remove('d-none');
                    setTimeout(function(){modal.hide()},1500);
                });
        }, 300); // Delay data
    }

    const table = new DataTable('#permintaan-table', {
        processing: false,
        serverSide: true,
        ajax: {
            url: '{{ env('API_URL') }}/permintaanbarangkeluar',
            headers: {
                'Authorization': 'Bearer ' + '{{ session('token') }}'
            }
        },
        columns: [{
                data: 'permintaan_barang_keluar_id',
                searchable: false,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
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
                defaultContent: '-'
            },
            {
                data: 'status',
                name: 'permintaan_barang_keluar.status',
                defaultContent: '-',
                render: function(data, type, row) {
                    if (data === 'Ditolak') {
                        return '<span class="text-danger">' + data + '</span>';
                    } else if (data === 'Belum Disetujui') {
                        return '<span class="text-warning">' + data + '</span>';
                    } else if (data === 'Disetujui') {
                        return '<span class="text-success">' + data + '</span>';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'permintaan_barang_keluar_id',
                name: 'permintaan_barang_keluar.id',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="flex gap-x-2">
                            <button aria-label="Detail" class="btn-detail btn-action" style="border: none;" onclick="showDetailModal(${data || ''}, '${row.nama_customer || ''}', '${row.nama_keperluan || ''}', '${row.tanggal_awal || ''}', '${row.extend || ''}', '${row.nama_tanggal_akhir || ''}', '${row.tanggal_akhir || ''}', '${row.keterangan || ''}', '${row.jumlah || ''}', '${row.status || ''}')">
                                <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [
            [4, 'desc']
        ]
    });
});
</script>
<script>
function updateStatus(id, status) {
    fetch('/permintaanbarangkeluar/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: id,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan: ' + error.message,
                icon: 'error'
            });
        });
}
</script>

    {{-- Notifikasi --}}
    <script>
        function showNotification(type, message) {
                let notificationTitle = '';
                let notificationClass = '';

                //  Mengatur judul dan kelas berdasarkan tipe notifikasi
                switch (type) {
                    case 'success':
                        notificationTitle = 'Success!';
                        notificationClass = 'alert-success';
                        break;
                    case 'error':
                        notificationTitle = 'Error!';
                        notificationClass = 'alert-danger';
                        break;
                    default:
                        notificationTitle = 'Notification';
                        notificationClass = 'alert-info';
                }

                // mengatur konten notifikasi
                $('#notificationTitle').text(notificationTitle);
                $('#notificationMessage').text(message);
                $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass);

                // menampilkan notifikasi
                $('#notification').fadeIn();

                // menyembunyikan notifikasi setelah 3 detik
                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }

            // @if (session('success'))
            //     showNotification('success', '{{ session('success') }}');
            // @endif
    </script>

    {{-- Select All Checkbox --}}
    <script>
        $(document).ready(function() {            
            // Ketika checkbox select-all diubah
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // Ketika checkbox item diubah
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Menampilkan/menghilangkan tombol "Hapus"
            function toggleDeleteButton() {
                const selected = $('.select-item:checked').length;
                const deleteButton = $('#delete-selected');
                if (selected > 0) {
                    deleteButton.removeClass('d-none');
                } else {
                    deleteButton.addClass('d-none');
                }
            }

            // Ketika tombol "Hapus" di klik
            $(document).on('click', '#delete-selected', function() {
                const selected = [];
                $('.select-item:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    $('#deleteModal').modal('show');
                    $('#itemName').text(selected.length + ' item');
                    $('#deleteForm').attr('action', '/barang/delete-selected');
                    $('#deleteForm').off('submit').on('submit', function(e) {
                        e.preventDefault();
                        fetch('/barang/delete-selected', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: selected
                            })
                        }).then(response => {
                            if (response.ok) {
                                location.reload();
                            } else {
                                alert('Gagal menghapus data.');
                            }
                        });
                    });
                } else {
                    alert('Tidak ada data yang dipilih.');
                }
            });
        });
    </script>
@endsection
