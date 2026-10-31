# Karhutla

aplikasi web komunitas buat lapor dan pantau indikasi kebakaran hutan dan lahan ini mini project proyek 3 kasus b dibuat pake laravel mysql blade css dan javascript

## identitas

- Anand Fairuzza Islami Permana
- 251511002
- D3-2A
- judul mini project pelaporan dan pemantauan kebakaran hutan dan lahan kasus B
- repository https://github.com/ABreathing/Mini-Project-Kasus-B-Pelaporan-dan-Pemantauan-Hutan-dan-Lahan.git

## apa aja yang bisa dilakuin

ada tiga jenis pengguna pelapor relawan pemantau dan koordinator relawan

pelapor bisa bikin laporan lengkap sama lokasi koordinat dan satu file bukti lalu mantau perkembangan laporannya sendiri dan boleh ngubah laporannya selama masih berstatus dikirim

relawan bisa nampilin semua laporan nilai kelengkapan dan kewajarannya liat laporan lain di area yang sama ngasih catatan lalu nandain laporan ditinjau perlu diteruskan atau selesai

koordinator bisa liat semua laporan lihat rekap nyatet laporan yang udah diteruskan ke pihak terkait dan nyelesain laporannya

alur status laporan dikirim lalu ditinjau lalu perlu_diteruskan lalu diteruskan lalu selesai atau dari ditinjau bisa langsung selesai

## business rule

- br-01 laporan cuma bisa diedit atau dihapus pemiliknya selama masih dikirim
- br-02 relawan gak boleh nilai laporan miliknya sendiri
- br-03 penerusan ke pihak terkait cuma bisa dicatat kalau statusnya perlu_diteruskan
- br-04 satu pengguna maksimal punya 3 laporan berstatus dikirim

## kebutuhan environment

- php 8.4 atau lebih baru
- composer
- mysql atau mariadb bisa pake xampp
- git

gak perlu node atau npm karena tampilan pake css biasa

## langkah instalasi

```
git clone https://github.com/ABreathing/Mini-Project-Kasus-B-Pelaporan-dan-Pemantauan-Hutan-dan-Lahan.git
cd Mini-Project-Kasus-B-Pelaporan-dan-Pemantauan-Hutan-dan-Lahan
composer install
copy .env.example .env
php artisan key:generate
```

kalau pake linux atau mac ganti copy jadi cp

## konfigurasi database

nyalain mysql dulu lewat xampp lalu buka phpmyadmin dan bikin database kosong bernama karhutla dengan collation utf8mb4_unicode_ci

di file .env isinya udah cocok buat xampp default yaitu user root tanpa password kalau punya kamu beda ubah DB_USERNAME dan DB_PASSWORD

## migration dan seeding

```
php artisan migrate --seed
```

kalau mau ngulang dari awal dengan data bersih pake

```
php artisan migrate:fresh --seed
```

## akun demo

semua password nya password

| role | email | catatan |
|---|---|---|
| pelapor | roman@karhutla.test | punya 1 laporan dikirim |
| pelapor | talia@karhutla.test | punya 3 laporan dikirim jadi kena br-04 kalau bikin laporan baru |
| relawan pemantau | weakkingsley@karhutla.test | punya laporan sendiri laporan nomor 11 buat ngetes br-02 |
| koordinator relawan | d@karhutla.test | bisa lihat rekap dan catat penerusan |

data seed isinya 19 laporan di 5 area dengan berbagai status dan semuanya data dummy

## cara menjalankan

```
php artisan serve
```

lalu buka http://127.0.0.1:8000 dan login pake salah satu akun demo

## catatan

file bukti disimpan privat di storage/app/private/evidence jadi gak perlu storage link dan cuma bisa dibuka lewat aplikasi oleh yang berhak