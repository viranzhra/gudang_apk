<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Rayya RR',
                'email' => 'rayya@gmail.com',
                'password' => Hash::make('123123123'),
            ],
        ];

        DB::table('users')->insert($users);

        ///

        $faker = Faker::create('id_ID');

        $supplier = [];
        for ($i = 0; $i < 50; $i++) {
            $supplier[] = [
                'nama' => $faker->company,
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber,
            ];
        }

        DB::table('supplier')->insert($supplier);

        ///
        
        $jenisbarang = [
            [
                'nama' => 'Makanan',
            ],
            [
                'nama' => 'Minuman',
            ],
            [
                'nama' => 'Elektronik',
            ],
            [
                'nama' => 'Alat Rumah Tangga',
            ],
        ];

        DB::table('jenis_barang')->insert($jenisbarang);

        ///
        
        $statusbarang = [
            [
                'nama' => 'Baik',
            ],
            [
                'nama' => 'Rusak',
            ],
        ];

        DB::table('status_barang')->insert($statusbarang);

        ///
        
        $barang = [];
        $jenis_barang = ['makanan', 'minuman', 'elektronik', 'alat rumah tangga'];
        $nama_barang = [
            'makanan' => ['Nasi Goreng', 'Rendang', 'Sate', 'Gado-gado', 'Bakso', 'Mie Ayam', 'Soto', 'Pempek', 'Martabak', 'Nasi Uduk'],
            'minuman' => ['Es Teh', 'Es Jeruk', 'Kopi', 'Jus Alpukat', 'Es Cendol', 'Es Cincau', 'Wedang Jahe', 'Soda Gembira', 'Es Kelapa Muda'],
            'elektronik' => ['Televisi', 'Kulkas', 'Mesin Cuci', 'AC', 'Kipas Angin', 'Rice Cooker', 'Blender', 'Setrika', 'Microwave', 'Dispenser'],
            'alat rumah tangga' => ['Sapu', 'Pel', 'Ember', 'Keset', 'Rak Piring', 'Gelas', 'Sendok', 'Gunting', 'Pisau']
        ];

        for ($i = 0; $i < 30; $i++) {
            $jenis = $faker->randomElement($jenis_barang);
            $jenis_barang_id = array_search($jenis, $jenis_barang) + 1;
            $barang[] = [
                'jenis_barang_id' => $jenis_barang_id,
                'nama' => $faker->randomElement($nama_barang[$jenis]),
                'jumlah' => 0,
            ];
        }

        DB::table('barang')->insert($barang);
    }
}
