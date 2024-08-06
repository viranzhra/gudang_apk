<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Barang Masuk') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
              <form class="" method="post" action="{{ route('barangmasuk.store') }}" enctype="multipart/form-data">
                  @csrf
                  @if ($errors->any())
                  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                      <strong class="font-bold">Ups!</strong> Terjadi kesalahan:
                      <ul class="mt-3 list-disc list-inside">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                  @endif

                  <!-- <div class="mb-5">
                      <label for="bm_kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID Barang Masuk</label>
                      <input type="text" id="bm_kode" name="bm_kode" value="{{ $bm_kode_value }}" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 cursor-not-allowed" readonly />                      
                  </div> -->
                  <div class="mb-5">
                      <label for="jenis_barang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Barang</label>
                      <select id="jenis_barang" name="jenis_barang_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Pilih jenis barang</option>
                        @foreach($jenis_barang as $d)
                          <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="barang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Barang</label>
                      <select id="barang" name="barang_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Pilih barang</option>
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="supplier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                      <select id="supplier" name="supplier_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Pilih supplier</option>
                        @foreach($supplier as $d)
                          <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="serial_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Serial Number</label>
                      <input type="text" id="serial_number" name="serial_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required />
                  </div>
                  <!-- <div class="mb-5">
                      <label for="jumlah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                      <input type="number" id="jumlah" name="jumlah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                  </div> -->
                  <div class="mb-5">
                      <label for="status_barang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kondisi Barang</label>
                      <select id="status_barang" name="status_barang_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Pilih kondisi barang</option>
                        @foreach($status_barang as $d)
                          <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="mb-5">
                      <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                      <input type="text" id="keterangan" name="keterangan" class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  </div>
                  <div class="mb-5">
                      <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Masuk</label>
                      <input type="date" id="tanggal" name="tanggal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required />
                  </div>

                  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
              
              </form>
          </div>
      </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Initialize Select2
    $('.select2').select2();

    const jenisBarangSelect = $('#jenis_barang');
    const barangSelect = $('#barang');
  
    jenisBarangSelect.on('select2:select', function (e) {
        const jenisBarangId = e.params.data.id;
    
        fetch(`/barangmasuk/get-by-jenis/${jenisBarangId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Data barang:', data); // Debugging line
                barangSelect.empty(); // Clear current options
                barangSelect.append('<option selected>Pilih barang</option>');
                data.forEach(barang => {
                    const option = new Option(barang.nama, barang.id, false, false);
                    barangSelect.append(option);
                });
                barangSelect.trigger('change'); // Notify Select2 of the new options
            })
            .catch(error => console.error('Error fetching barang:', error));
    });
});

  </script>      
</x-app-layout>
