<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Report;
use App\Models\ReportForwarding;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $roman = User::create([
            'name' => 'Roman',
            'email' => 'roman@karhutla.test',
            'password' => 'password',
            'role' => 'pelapor',
        ]);

        $talia = User::create([
            'name' => 'Talia',
            'email' => 'talia@karhutla.test',
            'password' => 'password',
            'role' => 'pelapor',
        ]);

        $weak = User::create([
            'name' => 'Weak Kingsley',
            'email' => 'weakkingsley@karhutla.test',
            'password' => 'password',
            'role' => 'relawan',
        ]);

        $d = User::create([
            'name' => 'D',
            'email' => 'd@karhutla.test',
            'password' => 'password',
            'role' => 'koordinator',
        ]);

        $oki = Area::create(['nama' => 'Kecamatan Air Sugihan', 'kabupaten' => 'Ogan Komering Ilir']);
        $pelalawan = Area::create(['nama' => 'Kecamatan Langgam', 'kabupaten' => 'Pelalawan']);
        $kapuas = Area::create(['nama' => 'Kecamatan Mantangai', 'kabupaten' => 'Kapuas']);
        $kuburaya = Area::create(['nama' => 'Kecamatan Sungai Raya', 'kabupaten' => 'Kubu Raya']);
        $jambi = Area::create(['nama' => 'Kecamatan Sekernan', 'kabupaten' => 'Muaro Jambi']);

        $data = [
            // Roman cuma punya 1 laporan "dikirim", jadi masih bisa bikin laporan baru (AC-02)
            [$roman, $oki, 'Asap tebal di kebun sawit', 'Asap putih tebal naik dari lahan gambut, api kecil kelihatan dari jalan', 'berat', -3.4120000, 105.2410000, 0, 'dikirim', null, null],
            [$roman, $pelalawan, 'Api kecil di semak pinggir jalan', 'Ada api sekitar 2 meter di semak, belum menyebar', 'ringan', 0.3120000, 102.0450000, 2, 'ditinjau', 'Informasi lengkap dan wajar, sudah dicek ada laporan lain di area sama', $weak],
            [$roman, $oki, 'Lahan terbakar dekat kanal', 'Lahan sekitar 1 hektar terbakar, asap menyebar ke arah permukiman', 'berat', -3.4180000, 105.2500000, 3, 'perlu_diteruskan', 'Kondisi berat dan ada laporan lain di area ini, perlu diteruskan', $weak],
            [$roman, $kapuas, 'Bekas pembakaran lahan', 'Bekas api sudah padam, tinggal asap tipis', 'ringan', -2.9100000, 114.3100000, 9, 'selesai', 'Sudah tidak ada api, proses di aplikasi ditutup', $weak],

            [$talia, $kuburaya, 'Asap dari lahan kosong', 'Asap tipis dari lahan kosong dekat permukiman', 'sedang', -0.0700000, 109.4000000, 0, 'dikirim', null, null],
            [$talia, $kuburaya, 'Api di tumpukan sampah kebun', 'Tumpukan ranting dibakar dan mulai merambat', 'sedang', -0.0750000, 109.4100000, 1, 'dikirim', null, null],
            [$talia, $jambi, 'Asap di kebun karet', 'Asap terlihat dari arah kebun karet sejak pagi', 'ringan', -1.6000000, 103.6100000, 1, 'dikirim', null, null],
            [$talia, $oki, 'Api merambat di lahan gambut', 'Api merambat pelan di lahan gambut, sulit dipadamkan warga', 'berat', -3.4150000, 105.2450000, 4, 'ditinjau', 'Cocok dengan laporan lain di area sama, lanjut dipantau', $weak],
            [$talia, $pelalawan, 'Kebakaran lahan sudah ditangani', 'Warga bersama petugas desa sudah memadamkan api', 'sedang', 0.3200000, 102.0500000, 5, 'diteruskan', 'Sudah diteruskan ke pihak terkait', $weak],
            [$talia, $jambi, 'Asap hilang setelah hujan', 'Hujan turun, api padam sendiri', 'ringan', -1.6050000, 103.6150000, 12, 'selesai', 'Selesai, tidak ada tindak lanjut', $weak],

            [$weak, $kapuas, 'Asap di sekitar rumah warga', 'Asap tipis dari lahan belakang rumah warga', 'sedang', -2.9150000, 114.3150000, 0, 'dikirim', null, null],

            [$roman, $oki, 'Asap di tepi hutan', 'Asap tipis di tepi hutan, tidak ada api yang kelihatan', 'ringan', -3.4000000, 105.2300000, 6, 'selesai', 'Asap hilang, proses ditutup', $weak],
            [$roman, $oki, 'Titik api dekat kanal', 'Ada titik api kecil di pinggir kanal', 'sedang', -3.4050000, 105.2350000, 5, 'ditinjau', 'Sedang dipantau', $weak],
            [$roman, $oki, 'Lahan gambut berasap', 'Lahan gambut berasap tebal sejak semalam', 'berat', -3.4100000, 105.2380000, 4, 'perlu_diteruskan', 'Perlu diteruskan, asap makin tebal', $weak],
            [$talia, $oki, 'Api kecil di kebun warga', 'Api kecil di kebun, sudah padam sendiri', 'ringan', -3.4200000, 105.2520000, 8, 'selesai', 'Sudah padam', $weak],
            [$talia, $oki, 'Asap tipis pagi hari', 'Asap tipis terlihat setiap pagi dari arah lahan kosong', 'ringan', -3.4220000, 105.2540000, 7, 'selesai', 'Tidak ada tindak lanjut', $weak],
            [$talia, $oki, 'Api merambat ke semak', 'Api merambat ke semak-semak dekat jalan desa', 'sedang', -3.4250000, 105.2560000, 3, 'diteruskan', 'Sudah diteruskan', $weak],
            [$talia, $oki, 'Bekas bakar lahan baru', 'Lahan baru terbakar, masih ada bara di beberapa titik', 'sedang', -3.4280000, 105.2580000, 2, 'ditinjau', 'Dipantau relawan', $weak],
            [$roman, $oki, 'Kebakaran dekat permukiman', 'Api terlihat dekat permukiman warga, warga mulai mengungsi', 'berat', -3.4300000, 105.2600000, 1, 'diteruskan', 'Sudah diteruskan', $weak],
        ];

        foreach ($data as $row) {
            $report = Report::create([
                'user_id' => $row[0]->id,
                'area_id' => $row[1]->id,
                'judul' => $row[2],
                'kondisi' => $row[3],
                'tingkat' => $row[4],
                'latitude' => $row[5],
                'longitude' => $row[6],
                'waktu_kejadian' => now()->subDays($row[7]),
                'status' => $row[8],
                'catatan_relawan' => $row[9],
                'reviewed_by' => $row[10]?->id,
            ]);

            if ($row[8] === 'diteruskan') {
                ReportForwarding::create([
                    'report_id' => $report->id,
                    'forwarded_by' => $d->id,
                    'pihak_terkait' => 'BPBD ' . $row[1]->kabupaten,
                    'catatan' => 'Informasi sudah disampaikan lewat telepon',
                ]);
            }
        }
    }
}