@extends('layouts.navigation')

@section('content')
<style>
.icon-edit {background-color: #01578d;} .icon-delete {background-color: #910a0a;}.btn-action:hover .icon-edit, .btn-action:hover .icon-delete {opacity: 0.8;}
#notification{position:fixed;top:10px;right:10px;width:300px;padding:15px;border-radius:5px;z-index:9999;display:none;text-align:center;justify-content:flex-start;align-items:center;text-align:left}
.alert{border-radius: 18px !important}
/* .alert-success{background-color:#d4edda;color:#155724;border:1px solid #c3e6cb;height:80px}.alert-danger{background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;height:80px}.alert-info{background-color:#d1ecf1;color:#0c5460;border:1px solid #bee5eb;height:80px} */
</style>
    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle" style="font-size: 15px">Notification</strong>
            <p id="notificationMessage" style="margin-top: 10px"></p>
        </div>

        <h4 class="mt-3" style="color: #8a8a8a;">Stock Out Request</h4>
        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
            <div class="d-flex gap-2">
                <!-- Add Button -->
                @can('item request.create')
                <a type="button" class="btn btn-primary d-flex align-items-center justify-content-center"
                    href="{{ route('permintaanbarangkeluar.create') }}" style="width: 75px; height: 35px;">
                    <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                    Add
                </a>
                @endcan
            </div>
        </div>

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
        @canany(['item request.viewAll', 'item request.viewFilterbyUser'])
        <table id="permintaan-table" class="table table-bordered table-striped table-hover" width="100%" style="font-size: 14px;">
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
            {{-- <tbody class="text-gray"></tbody> --}}
        </table>
        @endcanany
        
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.showDetailModal = function(id, namaCustomer, namaKeperluan, tanggalAwal, extend,
        namaTanggalAkhir, TanggalAkhir, keterangan, jumlah, status, alasan) {
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
                        <h5 class="modal-title" id="detailModalLabel">Request Detail</h5>
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
            fetch(`{{ env('API_URL') }}/permintaanbarangkeluar/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Periksa apakah data adalah array
                    if (!Array.isArray(data)) {
                        console.error('Data received does not match the expected format:', data);
                        alert('Error occurred when loading item details.');
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
                                    <div class="col-9">
                                        ${detail.nama_barang || '—'} — 
                                        ${'<b>' + detail.total_barang + '</b>' || '—'}
                                        ${status === 'Disetujui' ? `
                                        <div class="dropdown d-inline-block ms-2">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownSerialNumber${detail.barang_id}" data-bs-toggle="dropdown" aria-expanded="false" onclick="window.loadSerialNumbers(${id}, ${detail.barang_id}, this)">
                                                Serial Numbers
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownSerialNumber${detail.barang_id}" id="serialNumbers-${detail.barang_id}">
                                                <li><span class="dropdown-item">Loading...</span></li>
                                            </ul>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 font-weight-bold">Item Type</div>
                                    <div class="col-9">${detail.nama_jenis_barang || '—'}</div>
                                </div>
                                <div class="row ${status === 'Disetujui' ? 'mt-2' : ''}">
                                    <div class="col-3 font-weight-bold">Supplier</div>
                                    <div class="col-9">${detail.nama_supplier || '—'}</div>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    const contentHtml = `
                        <div class="row g-2 gx-3">
                            <div class="col-3 fw-bold">Customer:</div>
                            <div class="col-9">${namaCustomer || '—'}</div>
                            <div class="col-3 fw-bold">Purpose:</div>
                            <div class="col-9">${namaKeperluan || '—'}</div>
                            <hr class="my-2">
                            <div class="col-12 fw-bold">Date</div>
                            <div class="col-3 fw-bold ps-4">Request:</div>
                            <div class="col-9">${tanggalAwal || '—'}</div>
                            ${extend == 1 ? `
                                <div class="col-3 fw-bold ps-4">${namaTanggalAkhir || '—'}:</div>
                                <div class="col-9">${TanggalAkhir}</div>
                            ` : ''}
                            <hr class="my-2">
                            <div class="col-3 fw-bold">Description:</div>
                            <div class="col-9">${keterangan || '—'}</div>
                            <div class="col-3 fw-bold">Quantity:</div>
                            <div class="col-9">${jumlah || '—'}</div>
                            <div class="col-3 fw-bold">Status:</div>
                            <div class="col-9">
                                ${status === 'Ditolak' ? `<span class="badge text-bg" style="background-color: #910a0a; color: white;">${status}</span><br><span style="display:flex;gap:4px;width:100%;margin-top:10px"><div><span class="badge text-bg-light" style="padding:6px">Reasons:</span></div><p>${alasan}</p></span>` :
                                  status === 'Belum Disetujui' ? `<span class="badge text-bg-warning">${status}</span>` :
                                  status === 'Disetujui' ? `<span class="badge text-bg" style="background-color: #19850b; color: white;">${status}</span>` :
                                  status ? `<span class="badge text-bg-secondary">${status}</span>` : '—'}
                            </div>
                            ${detailContent}
                        </div>
                    `;

                    // Hide loading spinner and show content
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    const modalContentElement = document.getElementById('modalContent');
                    modalContentElement.innerHTML = contentHtml;
                    modalContentElement.classList.remove('d-none');

                    @ifcan('item request.confirm')                    
                        const footerContent = status === 'Belum Disetujui' ? `
                            <button type="button" class="btn" style="background-color: #19850b; color: white;"
                                onclick="updateStatus(${id}, 'Diproses')"
                                data-bs-dismiss="modal">Proses</button>
                            <button type="button" class="btn" style="background-color: #910a0a; color: white;"
                                onclick="updateStatus(${id}, 'Ditolak')"
                                data-bs-dismiss="modal">Tolak</button>
                        ` : status === 'Diproses' ? `
                            <button type="button" class="btn btn-primary"
                                onclick="window.location.href='/permintaanbarangkeluar/selectSN/${id}'">
                                Pilih SN
                            </button>
                        ` : '';
                        document.getElementById('modalFooterContent').innerHTML = footerContent;
                    @endifcan
                })
                .catch(error => {
                    console.error('Error fetching detail data:', error);
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    const modalContentElement = document.getElementById('modalContent');
                    modalContentElement.innerHTML = '<div class="text-center">Error occurred when loading the request details.</div>';
                    modalContentElement.classList.remove('d-none');
                    setTimeout(function(){modal.hide()},1500);
                });
        }, 300); // Delay data

        window.loadSerialNumbers = function(id, barangId, button) {
            const serialNumbersElement = document.getElementById(`serialNumbers-${barangId}`);
            
            // Cek apakah data sudah dimuat sebelumnya
            if (button.dataset.loaded === 'true') {
                return;
            }

            serialNumbersElement.innerHTML = '<li><span class="dropdown-item">Loading...</span></li>';

            setTimeout(() => {
                fetch(`{{ env('API_URL') }}/permintaanbarangkeluar/show-detail-sn/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const filteredData = data.filter(item => item.barang_id == barangId);
                    const serialNumbersHtml = filteredData.map(item => `
                        <li><span class="dropdown-item">${item.serial_number || 'N/A'}</span></li>
                    `).join('');

                    serialNumbersElement.innerHTML = serialNumbersHtml || '<li><span class="dropdown-item">No serial numbers available</span></li>';
                    
                    // Tandai SN sudah dimuat
                    button.dataset.loaded = 'true';
                })
                .catch(error => {
                    console.error('Error fetching serial numbers:', error);
                    serialNumbersElement.innerHTML = '<li><span class="dropdown-item">Error loading serial numbers</span></li>';
                });
            }, 300); // Delay pemuatan SN
        }
    }

    var urlDataPermintaan = '{{ env('API_URL') }}/permintaanbarangkeluar/onlyfor';
    @can('item request.viewAll')
        var urlDataPermintaan = '{{ env('API_URL') }}/permintaanbarangkeluar';
    @endcan

    const table = new DataTable('#permintaan-table', {
        processing: false,
        serverSide: true,
        debug: false,
        ajax: {
            url: urlDataPermintaan,
            headers: {
                'Authorization': 'Bearer ' + '{{ $jwt_token }}'
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
                defaultContent: '-'
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
                        return '<span class="badge text-bg" style="background-color: #910a0a; color: white;">' + data + '</span>';
                    } else if (data === 'Belum Disetujui') {
                        return '<span class="badge text-bg-warning">' + data + '</span>';
                    } else if (data === 'Disetujui') {
                        return '<span class="badge text-bg" style="background-color: #19850b; color: white;">' + data + '</span>';
                    } else {
                        return '<span class="badge text-bg-secondary">' + data + '</span>';
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
                            <button aria-label="Detail" class="btn-detail btn-action" style="border: none;" onclick="showDetailModal(${data || ''}, '${row.nama_customer || ''}', '${row.nama_keperluan || ''}', '${row.tanggal_awal || ''}', '${row.extend || ''}', '${row.nama_tanggal_akhir || ''}', '${row.tanggal_akhir || ''}', '${row.keterangan || ''}', '${row.jumlah || ''}', '${row.status || ''}', '${row.alasan || ''}')">
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

    function updateStatus(id, status) {
        if (status === 'Ditolak') {
            const modalHtml = `
                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea id="rejectReason" class="form-control" placeholder="Masukkan alasan penolakan..." maxlength="150" rows="3"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="submitRejectBtn" onclick="submitRejection(${id})">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Hapus modal sebelumnya jika ada
            const existingModal = document.getElementById('rejectModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Tambahkan modal ke body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Tampilkan modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        } else {
            submitStatusUpdate(id, status);
        }
    }

    function submitRejection(id) {
        const reason = document.getElementById('rejectReason').value;

        if (!reason) {
            showNotification('error', 'Reasons for rejection must be filled in.');
            return;
        }

        const submitBtn = document.getElementById('submitRejectBtn');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        submitBtn.disabled = true;

        submitStatusUpdate(id, 'Ditolak', reason);
    }

    function submitStatusUpdate(id, status, reason = null) {
        fetch('/permintaanbarangkeluar/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: id,
                status: status,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            const existingModal = document.getElementById('rejectModal');
            if (existingModal) {
                const bsModal = bootstrap.Modal.getInstance(existingModal);
                bsModal.hide();
            }

            if (data.success) {
                if (status === 'Diproses') {
                    window.location.href = `/permintaanbarangkeluar/selectSN/${id}`;
                } else {
                    showNotification('success', data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            } else {
                showNotification('error', data.message);
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        })
        .catch(error => {
            showNotification('error', error.message);
            setTimeout(function() {
                location.reload();
            }, 3000);
        });
    }

// function updateStatus(id, status) {
//     fetch('/permintaanbarangkeluar/update-status', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
//             },
//             body: JSON.stringify({
//                 id: id,
//                 status: status
//             })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // Swal.fire({
//                 //     title: 'Berhasil!',
//                 //     text: data.message,
//                 //     icon: 'success'
//                 // }).then(() => {
//                 //     location.reload();
//                 // });

//                 if (status === 'Diproses') {
//                     window.location.href = `/permintaanbarangkeluar/selectSN/${id}`;
//                 } else {
//                     showNotification('success', data.message);
//                     setTimeout(function() {
//                         location.reload();
//                     }, 3000);
//                 }            
//             } else {
//                 // Swal.fire({
//                 //     title: 'Gagal!',
//                 //     text: data.message,
//                 //     icon: 'error'
//                 // });
//                 showNotification('error', data.message);
//                 setTimeout(function() {
//                     location.reload();
//                 }, 3000);
//             }
//         })
//         .catch(error => {
//             // Swal.fire({
//             //     title: 'Error',
//             //     text: 'Terjadi kesalahan: ' + error.message,
//             //     icon: 'error'
//             // });
//             showNotification('error', error.message);
//             setTimeout(function() {
//                 location.reload();
//             }, 3000);
//         });
// }

// /* Alasan Penolakan */
// function submitRejection() {
//     const id = document.getElementById('rejectId').value;
//     const reason = document.getElementById('reason').value;

//     // Kirim alasan penolakan beserta ID
//     fetch('/permintaanbarangkeluar/reject', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': '{{ csrf_token() }}'
//         },
//         body: JSON.stringify({
//             id: id,
//             status: 'Ditolak',
//             reason: reason
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             location.reload();
//         }
//     })
//     .catch(error => {
//         console.error('Error rejecting request:', error);
//     });
// }
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
                }, 5000);
            }

            // @if (session('success'))
            //     showNotification('success', '{{ session('success') }}');
            // @endif
    </script>

    <!-- Alert -->
    @if (session('error'))
        <script>
            showNotification('error', '{{ session('error') }}');
        </script>
    @elseif (session('success'))
        <script>
            showNotification('success', '{{ session('success') }}');
        </script>
    @endif

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js">
    
@endsection
