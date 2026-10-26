<?php

namespace Database\Seeders;
use App\Models\Area;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $Roman = User::create([
            'name' => 'Roman',
            'email' => 'Roman@karhutla.test',
            'password' => 'password',
            'role' => 'pelapor',
        ]);

        $Talia = User::create([
            'name' => 'Talia',
            'email' => 'Talia@karhutla.test',
            'password' => 'password',
            'role' => 'pelapor',
        ]);

        $WeakKingsley = User::create([
            'name' => 'Weak Kingsley',
            'email' => 'WeakKingsley@karhutla.test',
            'password' => 'password',
            'role' => 'relawan',
        ]);

        $D = User::create([
            'name' => 'D',
            'email' => 'D@karhutla.test',
            'password' => 'password',
            'role' => 'koordinator',
        ]);

        $oki = Area::create([
            'nama' => 'Kecamatan Air Sugihan',
            'kabupaten' => 'Ogan Komering Ilir',
        ]);

        $pelalawan = Area::create([
            'nama' => 'Kecamatan Langgam',
            'kabupaten' => 'Pelalawan',
        ]);

        $kapuas = Area::create([
            'nama' => 'Kecamatan Mantangai',
            'kabupaten' => 'Kapuas',
        ]);

        $kuburaya = Area::create([
            'nama' => 'Kecamatan Sungai Raya',
            'kabupaten' => 'Kubu Raya',
        ]);

        $jambi = Area::create([
            'nama' => 'Kecamatan Sekernan',
            'kabupaten' => 'Muaro Jambi',
        ]);

        $data = [
            [
                $Roman, $oki,
                'Asap tebal di kebun sawit',
                'Asap putih tebal naik dari lahan gambut, api kecil kelihatan dari jalan',
                'berat', -3.4120000, 105.2410000, 0,
                'dikirim', null, null
            ],
            [
                $Roman, $pelalawan,
                'Api kecil di semak pinggir jalan',
                'Ada api sekitar 2 meter di semak, belum menyebar',
                'ringan', 0.3120000, 102.0450000, 2,
                'ditinjau',
                'Informasi lengkap dan wajar, sudah dicek ada laporan lain di area sama',
                $WeakKingsley
            ],
            [
                $Roman, $oki,
                'Lahan terbakar dekat kanal',
                'Lahan sekitar 1 hektar terbakar, asap menyebar ke arah permukiman',
                'berat', -3.4180000, 105.2500000, 3,
                'perlu_diteruskan',
                'Kondisi berat dan ada laporan lain di area ini, perlu diteruskan',
                $WeakKingsley
            ],
            [
                $Roman, $kapuas,
                'Bekas pembakaran lahan',
                'Bekas api sudah padam, tinggal asap tipis',
                'ringan', -2.9100000, 114.3100000, 9,
                'selesai',
                'Sudah tidak ada api, proses di aplikasi ditutup',
                $WeakKingsley
            ],

            [
                $Talia, $kuburaya,
                'Asap dari lahan kosong',
                'Asap tipis dari lahan kosong dekat permukiman',
                'sedang', -0.0700000, 109.4000000, 0,
                'dikirim', null, null
            ],
            [
                $Talia, $kuburaya,
                'Api di tumpukan sampah kebun',
                'Tumpukan ranting dibakar dan mulai merambat',
                'sedang', -0.0750000, 109.4100000, 1,
                'dikirim', null, null
            ],
            [
                $Talia, $jambi,
                'Asap di kebun karet',
                'Asap terlihat dari arah kebun karet sejak pagi',
                'ringan', -1.6000000, 103.6100000, 1,
                'dikirim', null, null
            ],
            [
                $Talia, $oki,
                'Api merambat di lahan gambut',
                'Api merambat pelan di lahan gambut, sulit dipadamkan warga',
                'berat', -3.4150000, 105.2450000, 4,
                'ditinjau',
                'Cocok dengan laporan lain di area sama, lanjut dipantau',
                $WeakKingsley
            ],
            [
                $Talia, $pelalawan,
                'Kebakaran lahan sudah ditangani',
                'Warga bersama petugas desa sudah memadamkan api',
                'sedang', 0.3200000, 102.0500000, 5,
                'diteruskan',
                'Sudah diteruskan ke pihak terkait',
                $WeakKingsley
            ],
            [
                $Talia, $jambi,
                'Asap hilang setelah hujan',
                'Hujan turun, api padam sendiri',
                'ringan', -1.6050000, 103.6150000, 12,
                'selesai',
                'Selesai, tidak ada tindak lanjut',
                $WeakKingsley
            ],

            [
                $WeakKingsley, $kapuas,
                'Asap di sekitar rumah warga',
                'Asap tipis dari lahan belakang rumah warga',
                'sedang', -2.9150000, 114.3150000, 0,
                'dikirim', null, null
            ],
        ];

        foreach ($data as $d) {
            Report::create([
                'user_id' => $d[0]->id,
                'area_id' => $d[1]->id,
                'judul' => $d[2],
                'kondisi' => $d[3],
                'tingkat' => $d[4],
                'latitude' => $d[5],
                'longitude' => $d[6],
                'waktu_kejadian' => now()->subDays($d[7]),
                'status' => $d[8],
                'catatan_relawan' => $d[9],
                'reviewed_by' => $d[10]?->id,
            ]);
        }
    }
}