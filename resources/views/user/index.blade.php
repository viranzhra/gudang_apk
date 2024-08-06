@extends('layouts.navigation')

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

    <style>
      .card {
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
      }

      .table-responsive {
        margin-top: 20px;
      }

      th,
      td {
        padding: 8px;
        text-align: left;
        color: black;
      }

      th {
        background-color: #f2f2f2;
        cursor: default;
        font-weight: semibold;
        color: rgba(0, 0, 0, 0.829);
      }

      /* search */
      .search-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }

      .search-box {
        display: flex;
        align-items: center;
        position: relative;
      }

      .search-box input[type="search"] {
        padding: 5px 30px 5px 10px;
        width: 250px;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      .search-box .search-icon {
        position: absolute;
        right: 5px;
        padding: 5px;
        font-size: 18px;
        border-radius: 4px;
        color: gray;
        cursor: pointer;
      }

      .search-container label {
        margin-right: 10px;
      }

      .search-container select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      .btn-action {
        background: none; 
        border: none;
        padding: 0;
        cursor: pointer;
        margin-right: 5px;
      }

      .icon-edit {
        color: #ffffff;
        background-color: #12b75c;
        font-size: 20px; 
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center; 
        justify-content: center; 
        border-radius: 4px;
      }

      .icon-delete {
        color: #ffffff;
        background-color: #dc3545;
        font-size: 20px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
      }

      .btn-action:hover .icon-edit,
      .btn-action:hover .icon-delete {
        opacity: 0.8;
      }

      .controls-container {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap; 
      }

      .controls-container label {
        margin-right: 5px; 
      }

      .controls-container select {
        margin-right: 5px;
      }

      .btn-primary {
        display: flex;
        align-items: center;
        background-color: #635bff;
        color: #ffffff;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        white-space: nowrap; 
      }

      .btn-primary:hover {
        background-color: #0056b3; /* Color on hover */
      }

      /* Media query for small screens */
      @media (max-width: 576px) {
        .search-container {
          flex-direction: column; /* Stack items vertically */
          align-items: flex-start; /* Align items to the start of the container */
        }

        .search-box input[type="search"] {
          width: 100%; /* Make the search input full width */
          margin-bottom: 10px; /* Add space below the search input */
        }

        .btn-primary {
          width: 100%; /* Make the button full width */
          text-align: center; /* Center the text */
          font-size: 16px; /* Adjust font size for better readability */
        }

        .controls-container {
          flex-direction: column; /* Stack controls vertically on small screens */
          align-items: stretch; /* Stretch controls to full width */
        }
      }
    </style>

<div class="container mt-3" style="padding: 30px;">
  <h4 class="mb-4" style="color: #8a8a8a;">User Management</h4>
      <div class="search-container">
          <form action="" method="GET" class="search-box">
              <input type="search" id="search-input" name="search" placeholder="Search..." value="{{ request('search') }}">
              <iconify-icon id="search-icon" name="search" icon="carbon:search" class="search-icon"></iconify-icon>
          </form>
          <div class="controls-container">
              <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                  <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                  Add
              </a>                    
          </div>
      </div>

  <!-- Notifikasi flash message -->
  @if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
  </div>
  @endif

  <div class="table-responsive">
      {{-- @if (!$data->isEmpty()) --}}
      <table class="table">
          <thead class="thead-lightblue">
              <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Privileges</th>
                  <th>Description</th>
                  <th>Action</th>
              </tr>
          </thead>
          {{-- <tbody>
              @foreach ($data as $d)
              <tr>
                  <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                  <td>{{ $d->nama }}</td>
                  <td>{{ $d->nama_jenis_barang }}</td>
                  <td>{{ $d->jumlah }}</td>
                  <td>{{ $d->keterangan }}</td>
                  <td>
                      <button type="button" class="btn-edit btn-action" 
                          data-id="{{ $d->id }}" 
                          data-nama="{{ $d->nama }}"
                          data-jenis="{{ $d->jenis_barang_id }}" 
                          data-keterangan="{{ $d->keterangan }}" 
                          aria-label="Edit">
                          <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                      </button>
                      <button data-id="{{ $d->id }}" class="btn-action" aria-label="Hapus">
                          <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                      </button>
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
      <div class="info-pagination-container">
          <div class="info-text">
              Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data pada halaman {{ $data->currentPage() }}.
          </div>
          <div class="pagination-container">
              {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
          </div>
      </div>
      @else
      <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
          <div class="mx-auto max-w-screen-sm text-center">
              <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Data tidak ditemukan.</p>
          </div>   
      </div>
      @endif
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="color: black">
              Apakah Anda yakin ingin menghapus <span id="itemName"></span> ini?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <form id="deleteForm" method="POST" action="">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Hapus</button>
              </form>                               
          </div>
      </div>
  </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Barang</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form method="post" action="{{ route('barang.store') }}" enctype="multipart/form-data">
                  @csrf
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

                  <div class="mb-3">
                      <label for="nama" class="form-label">Nama Barang</label>
                      <input type="text" id="nama" name="nama" class="form-control" required />
                  </div>
                  <div class="mb-3">
                      <label for="jenis_barang" class="form-label">Jenis Barang</label>
                      <select id="jenis_barang" name="jenis_barang" class="form-select" required>
                          <option value="" disabled selected>Pilih jenis barang</option>
                          @foreach($jenis_barang as $jb)
                              <option value="{{ $jb->id }}">{{ $jb->nama }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="keterangan" class="form-label">Keterangan</label>
                      <input type="text" id="keterangan" name="keterangan" class="form-control">
                  </div>
                  <button type="submit" class="btn btn-primary">Simpan</button>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="editData" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editDataLabel">Edit Data Barang</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="editForm" method="post" action="" enctype="multipart/form-data">
                  @csrf
                  @method('put')
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

                  <div class="mb-3">
                      <label for="nama" class="form-label">Nama Barang</label>
                      <input type="text" id="edit-nama" name="nama" class="form-control" required />
                  </div>
                  <div class="mb-3">
                      <label for="jenis_barang" class="form-label">Jenis Barang</label>
                      <select id="edit-jenis_barang" name="jenis_barang" class="form-select" required>
                          <option selected>Pilih jenis barang</option>
                          @foreach($jenis_barang as $d)
                              <option value="{{ $d->id }}">{{ $d->nama }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="keterangan" class="form-label">Keterangan</label>
                      <input type="text" id="edit-keterangan" name="keterangan" class="form-control">
                  </div>
                  <button type="submit" class="btn btn-primary">Edit</button>
              </form>
          </div>
      </div>
  </div>
</div>  --}}

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const editModal = new bootstrap.Modal(document.getElementById('editData'));

      document.querySelectorAll('.btn-edit').forEach(button => {
          button.addEventListener('click', function() {
              const barangId = this.getAttribute('data-id');
              const barangNama = this.getAttribute('data-nama');
              const barangJenis = this.getAttribute('data-jenis');
              const barangKeterangan = this.getAttribute('data-keterangan');

              // Update form action URL with the correct id
              document.getElementById('editForm').action = `/barang/update/${barangId}`;

              // Populate form fields
              document.getElementById('edit-nama').value = barangNama;
              document.getElementById('edit-jenis_barang').value = barangJenis;
              document.getElementById('edit-keterangan').value = barangKeterangan;

              // Show the modal
              editModal.show();
          });
      });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  const searchIcon = document.getElementById('search-icon');
  const searchForm = searchIcon.closest('form');

  searchIcon.addEventListener('click', function() {
      searchForm.submit(); // Submit the form
  });
});

</script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Function to auto-hide alerts
      function autoHideAlert(selector, timeout) {
          const alerts = document.querySelectorAll(selector);
          alerts.forEach(alert => {
              setTimeout(() => {
                  alert.classList.remove('show');
                  alert.classList.add('fade');
                  setTimeout(() => alert.remove(), 500); // Remove alert after fade transition
              }, timeout);
          });
      }

      // Hide success messages after 3 seconds
      autoHideAlert('.alert-success', 3000);

      // Hide error messages after 3 seconds
      autoHideAlert('.alert-danger', 3000);
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

  document.querySelectorAll('[aria-label="Hapus"]').forEach(button => {
      button.addEventListener('click', function() {
          const itemId = this.getAttribute('data-id');
          const itemName = this.closest('tr').querySelector('td:nth-child(2)').innerText;
          
          document.getElementById('itemName').innerText = itemName;
          document.getElementById('deleteForm').action = `/barang/delete/${itemId}`;
          
          deleteModal.show();
      });
  });
});
</script>


@endsection