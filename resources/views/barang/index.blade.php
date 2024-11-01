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
            <h4 style="color: black;">Item</h4>
            <div class="d-flex gap-2">
                <!-- Add Button -->
                @can('item.create')
                <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
                    <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                    Add
                </button>
                @endcan

                <!-- Delete Button -->
                @can('item.delete')
                <button id="delete-selected" class="btn btn-danger d-none"
                    style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                    <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                    Delete
                </button>
                @endcan
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
        @can('item.view')
        <table id="barang-table" class="table table-hover table-sm text-dark pt-2" width="100%" style="font-size: 15px;">
            <thead class="thead-dark">
                <tr>
                    @can('item.delete')
                    <th style="width: 20px">
                        <input type="checkbox" id="select-all">
                    </th>
                    @endcan
                    <th>
                        No
                    </th>
                    <th>
                        Item Name
                    </th>
                    <th>
                        Item Type
                    </th>
                    <th>
                        Supplier
                    </th>
                    {{-- <th>
                        Stock
                    </th> --}}
                    <th>
                        Description
                    </th>
                    @canany(['item.edit', 'item.delete'])
                    <th>
                        Action
                    </th>
                    @endcanany
                </tr>
            </thead>
            {{-- Isi dari table --}}
            <tbody class="text-gray"></tbody>
        </table>
        @endcan
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="tambahForm" action="{{ route('barang.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Item Name</label>
                            <input type="text" id="nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="jenis_barang" class="form-label">Item Type</label>
                            <select id="jenis_barang" name="jenis_barang" class="form-control" required>
                                <option value="">Select Item Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <select id="supplier" name="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Description</label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Skrip Modal Tambah Data -->
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ env('API_URL') }}/barang/create',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                },
                success: function(data) {
                    var jenisBarangSelect = $('#jenis_barang');
                    var supplierSelect = $('#supplier');

                    $.each(data.jenis_barang, function(index, item) {
                        jenisBarangSelect.append($('<option>', {
                            value: item.id,
                            text: item.nama
                        }));
                    });

                    $.each(data.supplier, function(index, item) {
                        supplierSelect.append($('<option>', {
                            value: item.id,
                            text: item.nama
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

            $('#tambahForm').on('submit', function(e) {
            $('#tambahDataModal').modal('hide');
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                },
                data: form.serialize(),
                success: function(response) {
                    showNotification('success', 'Berhasil menambahkan data!'); //response.message
                    $('#barang-table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating data:', error);
                }
            });
        });
        });
    </script>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: black">
                    Are you sure you want to delete <b id="itemName"></b>?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Skrip Modal Delete Data --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function autoHideAlert(selector, timeout) {
                const alerts = document.querySelectorAll(selector);
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 500);
                    }, timeout);
                });
            }

            autoHideAlert('.alert-success', 3000);
            autoHideAlert('.alert-danger', 3000);
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const itemId = button.data('id');
            const itemName = button.closest('tr').find('td:nth-child(3)').text();

            $('#itemName').text(itemName);
            $('#deleteForm').attr('action', `/barang/delete/${itemId}`);
        });
    </script>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editData" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id" />

                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Item Name</label>
                            <input type="text" id="edit_nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_jenis_barang" class="form-label">Item Type</label>
                            <select id="edit_jenis_barang" name="jenis_barang" class="form-control" required>
                                <option value="">Select Item Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_supplier" class="form-label">Supplier</label>
                            <select id="edit_supplier" name="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_keterangan" class="form-label">Description</label>
                            <input type="text" id="edit_keterangan" name="keterangan" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Skrip Modal Edit Data --}}
    <script>
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var keterangan = $(this).data('keterangan');

            $('#editForm').attr('action', '{{ url('barang/update') }}/' + id);

            $('#edit_id').val(id);
            $('#edit_nama').val(nama);
            $('#edit_keterangan').val(keterangan);

            $.ajax({
                url: '{{ env('API_URL') }}/barang/' + id,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                },
                success: function(response) {
                    // Fill jenis barang select
                    var jenisBarangSelect = $('#edit_jenis_barang');
                    jenisBarangSelect.empty();
                    $.each(response.jenis_barang, function(index, item) {
                        jenisBarangSelect.append($('<option>', {
                            value: item.id,
                            text: item.nama,
                            selected: item.id == response.data.jenis_barang_id ?
                                true : false
                        }));
                    });

                    // Clear and fill supplier select
                    var supplierSelect = $('#edit_supplier');
                    supplierSelect.empty();
                    $.each(response.supplier, function(index, item) {
                        supplierSelect.append($('<option>', {
                            value: item.id,
                            text: item.nama,
                            selected: item.id == response.data.supplier_id ? true :
                                false
                        }));
                    });

                    $('#edit_keterangan').val(response.data.keterangan);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching edit data:', error);
                }
            });
        });

        $('#editForm').on('submit', function(e) {
            $('#editData').modal('hide');
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                },
                data: form.serialize(),
                success: function(response) {
                    showNotification('success', 'Berhasil memperbarui data!'); //response.message
                    $('#barang-table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating data:', error);
                }
            });
        });
    </script>

    {{-- Data Tabel --}}
    @can('item.view')
    <script>
        $(document).ready(function() {
            $('#barang-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: '{{ env('API_URL') }}/barang',
                    data: function(d) {},
                    headers: {
                        'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                    }
                },
                columns: [
                    @can('item.delete')
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="select-item flex justify-center items-center" value="${data}">`;
                        }
                    },
                    @endcan
                    {
                        data: null,
                        sortable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama_barang',
                        name: 'barang.nama'
                    },
                    {
                        data: 'nama_jenis_barang',
                        name: 'jenis_barang.nama'
                    },
                    {
                        data: 'nama_supplier',
                        name: 'supplier.nama'
                    },
                    // {
                    //     data: 'jumlah',
                    //     name: 'barang_masuk.jumlah',
                    //     searchable: false,
                    //     orderable: false
                    // },
                    {
                        data: 'keterangan_barang',
                        name: 'barang.keterangan'
                    },
                    @canany(['item.edit', 'item.delete'])
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row) {
                            return `
                    @can('item.edit')
                    <button type="button" class="btn-edit btn-action" 
                        data-id="${row.id}" 
                        data-nama="${row.nama_barang}"
                        data-bs-toggle="modal" data-bs-target="#editData"
                        aria-label="Edit">
                        <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                    </button>
                    @endcan
                    @can('item.delete')
                    <button type="button" data-id="${row.id}" class="btn-action" aria-label="Hapus"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                    </button>
                    @endcan
                `;
                        }
                    }
                    @endcanany
                ],
                // order: [
                //     [5, 'desc']
                // ],
                pagingType: 'full_numbers',
                language: {
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»'
                    }
                },
                drawCallback: function(settings) {
                    var pagination = $(this).closest('.dataTables_wrapper').find(
                    '.dataTables_paginate');
                    pagination.toggle(this.api().page.info().pages > 1);
                    pagination.find('.paginate_button').addClass('btn btn-sm btn-outline-secondary');
                    pagination.find('.paginate_button.current').removeClass('btn-outline-secondary')
                        .addClass('btn-secondary');
                }
            });
        });
    </script>
    @endcan

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
