# Panduan Lengkap Pengelola Website MotoShop

Panduan ini disiapkan sebagai bahan pelatihan dan SOP pengelolaan website PT Mahkota Inti Sejahtera. Versi ringkas dan interaktif tersedia di **Dashboard WordPress > MotoShop > Panduan**.

> **Status dokumen:** panduan operasional dan bahan pelatihan  
> **Sistem:** WordPress, tema MotoShop, dan WooCommerce  
> **Sasaran pengguna:** administrator, operator website, sales, dan penanggung jawab konten

## Daftar isi

1. [Fungsi website](#fungsi-website)
2. [Alur kerja sistem](#alur-kerja-sistem)
3. [Pembagian peran pengguna](#pembagian-peran-pengguna)
4. [Menu khusus MotoShop](#menu-khusus-motoshop)
5. [Pengelolaan produk](#cara-menambahkan-produk-woocommerce)
6. [Spesifikasi produk](#cara-memasukkan-spesifikasi)
7. [Permintaan pelanggan](#menangani-permintaan-pelanggan)
8. [Evaluasi pemasaran digital](#evaluasi-pemasaran-digital)
9. [Persiapan data](#persiapan-data-sebelum-mengelola-website)
10. [Kategori, gambar, harga, dan stok](#mengelola-kategori-produk)
11. [Artikel, menu, logo, dan halaman](#mengelola-artikel)
12. [SOP konten dan keamanan](#standar-penulisan-konten)
13. [Backup dan pemeliharaan](#backup-dan-pemeliharaan)
14. [Troubleshooting](#troubleshooting)
15. [Skenario pelatihan](#skenario-praktik-pelatihan)
16. [Checklist peluncuran](#checklist-sebelum-website-dipublikasikan)

## Fungsi website

Website ini berfungsi sebagai katalog penjualan dan pintu konsultasi pembelian motor, bukan toko yang wajib langsung menerima pembayaran online. Pengunjung dapat:

- melihat kategori, foto, detail, dan spesifikasi motor;
- memilih rencana pembelian tunai atau kredit;
- memilih cabang terdekat;
- mengirim permintaan agar ditindaklanjuti sales;
- mengenal layanan Sales, Service, dan Sparepart.

Alur utamanya adalah **lihat katalog > buka detail > kirim permintaan > sales menghubungi > kunjungan/proses pembelian**.

Website ini pada tahap sekarang tidak mewajibkan pembayaran langsung. WooCommerce digunakan sebagai pengelola katalog yang terstruktur. Fitur keranjang, checkout, ongkos kirim, atau pembayaran daring dapat diaktifkan kemudian jika perusahaan memutuskan menjual produk secara langsung.

## Alur kerja sistem

### Alur pengunjung

1. Pengunjung membuka beranda.
2. Pengunjung memilih kategori kendaraan atau membuka katalog Motor.
3. Pengunjung membaca deskripsi, foto, dan spesifikasi unit.
4. Pengunjung memilih metode pembelian tunai atau kredit.
5. Pengunjung memilih cabang dan mengirim formulir konsultasi.
6. Sistem menyimpan data ke menu **Permintaan Motor**.
7. Sales menghubungi pengunjung melalui WhatsApp atau telepon.
8. Proses dilanjutkan ke simulasi, kunjungan dealer, pemesanan, atau serah terima.

### Alur operator

1. Operator memeriksa ringkasan pada menu **MotoShop**.
2. Operator memperbarui katalog, stok, harga, promo, artikel, dan informasi cabang.
3. Operator atau sales memeriksa permintaan baru.
4. Sales menghubungi calon pembeli.
5. Status permintaan diperbarui sampai selesai atau batal.
6. Penanggung jawab melakukan evaluasi berkala terhadap produk dan permintaan.

### Status permintaan

| Status | Arti | Tindakan berikutnya |
|---|---|---|
| Baru | Formulir baru masuk | Periksa data dan hubungi calon pembeli |
| Dihubungi | Sales sudah melakukan kontak | Catat kebutuhan dan tawarkan tindak lanjut |
| Janji Kunjungan | Calon pembeli akan datang ke cabang | Konfirmasi jadwal dan kesiapan unit |
| Selesai | Permintaan sudah selesai ditangani | Simpan sebagai arsip pelaporan |
| Batal | Pelanggan tidak melanjutkan | Simpan alasan secara internal bila diperlukan |

## Pembagian peran pengguna

### Administrator

- mengatur akun pengguna;
- memasang dan memperbarui plugin;
- mengubah konfigurasi WordPress dan WooCommerce;
- melakukan backup dan pemulihan;
- mengelola struktur menu dan tampilan umum.

### Operator website

- menambah dan memperbarui produk;
- mengelola kategori, gambar, deskripsi, harga, dan stok;
- menerbitkan artikel;
- memeriksa formulir permintaan;
- memperbarui informasi cabang dan kontak.

### Sales

- melihat data calon pembeli;
- menghubungi calon pembeli;
- memberikan informasi harga, stok, kredit, dan jadwal kunjungan;
- memperbarui status tindak lanjut.

### Penanggung jawab konten

- memastikan informasi berasal dari sumber resmi;
- menyetujui harga, promo, spesifikasi, dan materi visual;
- memeriksa kesesuaian bahasa dan identitas perusahaan;
- melakukan audit konten secara berkala.

> Gunakan akun yang berbeda untuk setiap pengguna. Jangan membagikan satu akun administrator kepada seluruh peserta atau staf.

Peran Administrator, Operator website, dan Sales harus diterapkan melalui akun dan hak akses yang berbeda. Operator hanya memperoleh akses untuk mengelola produk, konten, dan permintaan sesuai tugasnya, sedangkan Sales hanya memperoleh akses yang diperlukan untuk melihat dan memperbarui tindak lanjut calon pembeli.

> **Status implementasi:** tema menyediakan role **Operator MotoShop** dan **Sales MotoShop**. Operator memperoleh hak untuk mengelola katalog serta permintaan pelanggan, sedangkan Sales dibatasi pada pemeriksaan permintaan dan pembaruan status tindak lanjut. Administrator tetap bertanggung jawab membuat akun staf dan memastikan setiap akun hanya memperoleh role sesuai tugasnya.

## Menu khusus MotoShop

Masuk ke WordPress lalu buka menu **MotoShop**. Menu ini menjadi pintu kerja utama operator:

- **Ringkasan**: jumlah produk dan permintaan terbaru.
- **Semua Produk**: mengedit katalog yang sudah ada.
- **Tambah Produk**: membuat unit baru.
- **Kategori Produk**: mengatur Niaga, Listrik, ATV, dan Trail.
- **Permintaan Motor**: data calon pembeli dari formulir website.
- **Pesanan**: pesanan standar WooCommerce jika kelak transaksi online diaktifkan.
- **Panduan**: petunjuk kerja langsung di dashboard.

Menu bawaan WooCommerce tetap ditampilkan karena dibutuhkan untuk konfigurasi teknis. Operator harian cukup memulai dari menu **MotoShop**.

## Cara menambahkan produk WooCommerce

1. Buka **MotoShop > Tambah Produk**.
2. Isi **nama motor** pada kolom judul.
3. Isi **deskripsi lengkap**: fungsi kendaraan, keunggulan, dan penggunaan yang cocok.
4. Di kotak **Data produk**, pilih **Produk sederhana**.
5. Isi **harga reguler** hanya jika sudah dikonfirmasi. Jika belum, biarkan kosong agar website menampilkan “Hubungi sales”.
6. Pada bagian Inventaris, atur status stok sesuai kondisi cabang.
7. Pilih **kategori produk** pada panel kanan.
8. Pasang **gambar produk** sebagai foto utama dan tambahkan beberapa foto pada galeri produk.
9. Isi **deskripsi singkat** dengan 1–2 paragraf ringkas.
10. Gunakan tombol **Pratinjau**, periksa hasilnya, lalu klik **Terbitkan**.

## Cara memasukkan spesifikasi

Pada kotak **Data produk**, pilih tab **Atribut**. Tambahkan atribut satu per satu, misalnya:

- Tipe Mesin
- Volume Langkah / Daya Motor
- Tenaga Maksimum
- Torsi Maksimum
- Sistem Transmisi
- Kapasitas Tangki atau Baterai
- Dimensi
- Berat
- Pilihan Warna

Centang **Tampilkan di halaman produk**, klik **Simpan atribut**, lalu perbarui produk. Tema akan menampilkan atribut tersebut sebagai tabel spesifikasi.

## Menangani permintaan pelanggan

1. Buka **MotoShop > Permintaan Motor**.
2. Lihat motor, metode pembelian, dan cabang yang dipilih.
3. Klik nomor WhatsApp untuk menghubungi calon pembeli.
4. Buka detail data lalu ubah status: **Baru > Dihubungi > Janji Kunjungan > Selesai**. Gunakan **Batal** bila calon pembeli tidak melanjutkan.
5. Hindari menghapus data sebelum proses tindak lanjut selesai.

## Persiapan data sebelum mengelola website

Siapkan data berikut agar proses input tidak terhenti di tengah jalan.

### Data perusahaan

- logo resmi dalam format PNG transparan atau SVG;
- nama badan usaha yang benar;
- nomor WhatsApp perusahaan dan cabang;
- alamat, jam operasional, dan tautan peta setiap cabang;
- akun media sosial resmi;
- email perusahaan;
- profil singkat, visi, misi, dan sejarah perusahaan;
- kebijakan privasi dan persetujuan penggunaan data pelanggan.

### Data produk

- nama resmi produk;
- kategori kendaraan;
- foto utama dan galeri;
- harga atau keterangan “Hubungi sales”;
- status stok;
- deskripsi singkat;
- deskripsi lengkap;
- spesifikasi resmi;
- pilihan warna;
- informasi garansi;
- cabang yang menyediakan unit;
- masa berlaku promo;
- sumber data dan tanggal verifikasi.

### Standar file gambar

- gunakan JPG atau WebP untuk foto;
- gunakan PNG transparan untuk logo;
- ukuran foto produk disarankan minimal 1.200 × 900 piksel;
- usahakan rasio gambar antarproduk konsisten;
- hindari foto buram, hasil tangkapan layar, watermark pihak lain, dan latar yang terlalu ramai;
- nama file harus jelas, misalnya `viar-karya-200-merah-depan.webp`;
- kompres gambar sebelum diunggah agar website tetap cepat.

## Masuk ke dashboard WordPress

1. Buka alamat website lalu tambahkan `/wp-admin`.
2. Masukkan akun pengguna masing-masing.
3. Setelah berhasil masuk, buka menu **MotoShop**.
4. Jangan menyimpan kata sandi pada komputer umum.
5. Klik avatar di kanan atas lalu pilih **Log Keluar** setelah selesai menggunakan komputer bersama.

> Alamat login, nama pengguna, dan kata sandi tidak dicantumkan dalam dokumen ini demi keamanan.

## Mengenal halaman Ringkasan MotoShop

Halaman Ringkasan menampilkan informasi yang paling sering dibutuhkan operator:

- jumlah produk yang sedang tayang;
- jumlah seluruh permintaan pelanggan;
- jumlah permintaan berstatus Baru;
- tombol Tambah Produk;
- akses ke Permintaan Motor;
- akses ke panduan;
- daftar calon pembeli terbaru.

Ringkasan dibuat sebagai pintu utama operator. Menu bawaan WordPress dan WooCommerce tetap tersedia untuk kebutuhan administrasi lanjutan.

## Mengedit produk yang sudah ada

1. Buka **MotoShop > Semua Produk**.
2. Cari produk melalui kolom pencarian atau filter kategori.
3. Klik nama produk atau tombol **Sunting**.
4. Ubah data yang diperlukan.
5. Klik **Perbarui**.
6. Buka halaman produk di frontend dan periksa hasil akhirnya.

Gunakan **Sunting Cepat** hanya untuk perubahan sederhana seperti nama, kategori, status, atau tanggal. Untuk foto, deskripsi, harga, dan spesifikasi, buka editor produk lengkap.

## Menonaktifkan produk tanpa menghapusnya

Jika produk sedang tidak dijual atau menunggu konfirmasi:

1. buka produk;
2. ubah status menjadi **Draf**;
3. klik **Perbarui**.

Gunakan draf agar data dapat dipulihkan dengan mudah. Hapus produk hanya bila benar-benar tidak diperlukan. Produk yang dihapus masuk ke Sampah dan masih dapat dipulihkan sebelum sampah dikosongkan.

## Mengelola kategori produk

1. Buka **MotoShop > Kategori Produk**.
2. Isi nama kategori, misalnya Motor Niaga.
3. Isi slug menggunakan huruf kecil dan tanda hubung, misalnya `motor-niaga`.
4. Tambahkan deskripsi kategori bila diperlukan.
5. Klik **Tambah kategori baru**.

Kategori yang digunakan pada desain saat ini:

- Motor Niaga;
- Motor Listrik;
- Sepeda Listrik;
- Motor Trail;
- ATV.

Gunakan satu kategori utama yang paling relevan untuk setiap produk. Kategori tambahan boleh digunakan jika benar-benar diperlukan, tetapi hindari membuat kategori yang memiliki arti sama.

## Mengatur harga

### Harga sudah pasti

Isi **Harga reguler** pada Data produk. Masukkan angka tanpa simbol rupiah dan tanpa pemisah ribuan. Contoh: `28500000`.

### Harga promosi

Isi **Harga promo** dan tentukan jadwal mulai serta berakhir. Setelah masa promo selesai, periksa kembali tampilan produk.

### Harga belum pasti

Kosongkan harga. Tema akan menampilkan **Hubungi sales**. Pilihan ini cocok bila harga berbeda berdasarkan cabang, karoseri, program kredit, atau periode promosi.

> Jangan menerbitkan harga yang belum dikonfirmasi oleh penanggung jawab perusahaan.

## Mengatur stok

Pada **Data produk > Inventaris**:

- pilih **Tersedia** jika unit dapat ditawarkan;
- pilih **Habis** jika unit belum tersedia;
- gunakan SKU yang unik untuk membantu pencarian internal;
- aktifkan pengelolaan jumlah stok hanya jika cabang memiliki prosedur pembaruan stok yang konsisten.

Jika stok berbeda di setiap cabang, jelaskan ketersediaannya pada deskripsi atau konfirmasikan melalui sales. Sistem saat ini belum memisahkan jumlah stok per cabang.

## Mengelola gambar produk

### Gambar utama

1. Klik **Tetapkan gambar produk**.
2. Unggah atau pilih foto dari Media Library.
3. Isi teks alternatif dengan deskripsi singkat, misalnya “VIAR Karya 200 warna biru tampak samping”.
4. Klik **Tetapkan gambar produk**.

### Galeri produk

1. Klik **Tambahkan gambar galeri produk**.
2. Pilih foto tampak depan, samping, belakang, detail fitur, dan pilihan warna.
3. Susun urutan gambar secara logis.
4. Klik **Perbarui**.

Jangan mengunggah file yang sama berulang kali. Periksa Media Library terlebih dahulu.

### Sinkronisasi bahan katalog dari folder

Untuk katalog pelatihan ini, bahan produk dapat disusun dalam satu folder produk dengan pola berikut:

```text
Nama Produk/
├── Thumbnail.webp
├── Banner.webp
├── Spesifikasi.txt
├── Fitur/
│   ├── Nama fitur 1.webp
│   └── Nama fitur 2.webp
└── Warna/
    ├── Merah.webp
    └── Hitam.webp
```

- **Thumbnail** dipakai sebagai gambar kartu katalog.
- **Banner** dipakai sebagai gambar utama pada detail produk. Jika banner tidak tersedia, tema memakai thumbnail.
- Gambar di folder **Fitur** tampil sebagai slider otomatis pada detail produk.
- Gambar di folder **Warna** masuk ke galeri produk.
- Setiap baris `Spesifikasi.txt` menggunakan pola `Nama atribut : Nilai` atau dipisahkan dengan tombol tab.

Pada komputer pengembangan, jalankan sinkronisasi dari folder WordPress:

```powershell
php wp-content/themes/motoshop/scripts/sync-local-catalog.php "C:\Users\fariz\Downloads\Produk VIAR"
```

Skrip memperbarui produk dengan nama yang sama dan menambahkan produk yang belum tersedia. Harga yang sudah diisi tidak dihapus. Setelah sinkronisasi, periksa kembali nama, kategori, gambar, satuan spesifikasi, dan kebenaran materi sebelum produk dipublikasikan pada website produksi.

## Contoh struktur deskripsi produk

Gunakan pola berikut agar isi setiap produk konsisten.

```text
[Nama produk] adalah kendaraan yang dirancang untuk [kebutuhan utama].
Produk ini cocok digunakan oleh [target pengguna] untuk [contoh penggunaan].

Keunggulan utama:
- Keunggulan pertama
- Keunggulan kedua
- Keunggulan ketiga

Ketersediaan unit, warna, harga, dan program kredit dapat berbeda di setiap cabang.
Hubungi sales untuk memperoleh informasi terbaru.
```

Hindari klaim seperti “paling murah”, “pasti disetujui kredit”, atau “stok selalu tersedia” jika tidak memiliki bukti dan persetujuan perusahaan.

## Contoh atribut spesifikasi

| Jenis kendaraan | Atribut yang disarankan |
|---|---|
| Motor bensin | Tipe Mesin, Volume Langkah, Tenaga, Torsi, Transmisi, Tangki, Rem, Ban, Dimensi, Berat |
| Motor listrik | Daya Motor, Kecepatan Maksimum, Jarak Tempuh, Baterai, Pengisian, Rem, Dimensi, Berat |
| Sepeda listrik | Daya Motor, Baterai, Jarak Tempuh, Kecepatan, Waktu Pengisian, Ban, Rem, Beban Maksimum |
| Motor niaga | Mesin, Transmisi, Dimensi Bak, Daya Angkut, Suspensi, Rem, Tangki, Dimensi Kendaraan |
| ATV atau Trail | Mesin, Transmisi, Tangki, Suspensi, Rem, Ban, Ground Clearance, Dimensi, Berat |

Tuliskan satuan secara konsisten, misalnya `cc`, `kW`, `Nm`, `km`, `liter`, `kg`, dan `mm`. Jangan mencampur sumber atau spesifikasi dari varian yang berbeda.

## Menangani data calon pembeli dengan benar

Data pada Permintaan Motor dibatasi pada nama, nomor WhatsApp atau telepon, produk yang diminati, pilihan pembelian tunai atau kredit, cabang yang dipilih, sumber permintaan, serta catatan tindak lanjut yang tidak memuat dokumen identitas, data rekening, atau informasi pengajuan kredit. Data tersebut hanya boleh digunakan untuk pelayanan dan tindak lanjut atas permintaan pelanggan.

### SOP tindak lanjut

1. Periksa permintaan baru minimal pada awal dan akhir jam kerja.
2. Pastikan nomor dan produk yang dipilih terbaca dengan benar.
3. Hubungi calon pembeli melalui saluran resmi.
4. Perkenalkan nama sales dan cabang.
5. Konfirmasi kebutuhan, lokasi, metode pembayaran, dan waktu tindak lanjut.
6. Jangan menjanjikan stok, harga, diskon, atau persetujuan kredit sebelum dikonfirmasi.
7. Perbarui status pada dashboard.
8. Simpan hasil penting pada prosedur pencatatan internal perusahaan.

### Contoh pesan awal sales

```text
Selamat [pagi/siang/sore], Bapak/Ibu [Nama].

Saya [Nama Sales] dari VIAR Mahkota cabang [Cabang]. Kami menerima permintaan informasi mengenai [Nama Produk] melalui website. Apakah Bapak/Ibu berkenan melanjutkan konsultasi terkait ketersediaan unit dan pilihan pembelian tunai atau kredit?

Terima kasih.
```

Sesuaikan pesan dengan kebijakan komunikasi perusahaan. Jangan mengirim spam atau meneruskan data pelanggan kepada pihak yang tidak berkepentingan.

## Evaluasi pemasaran digital

Evaluasi dilakukan secara berkala dengan mencatat jumlah kunjungan situs web, jumlah permintaan yang masuk, sumber permintaan, produk yang diminati, cabang yang dipilih, dan status akhir tindak lanjut. Data tersebut digunakan untuk membandingkan efektivitas kanal pemasaran serta menentukan produk atau konten yang perlu diprioritaskan.

Data kunjungan diperoleh melalui perangkat analitik yang telah dikonfigurasi dengan memperhatikan kebijakan privasi. Data permintaan dan tindak lanjut diperoleh dari menu **Permintaan Motor**. Jika ringkasan atau ekspor data belum tersedia di dalam sistem, pengelola menggunakan format evaluasi yang disiapkan untuk mencatat hasilnya secara berkala. Jangan menyatakan data tersedia sebelum perangkat analitik atau format pencatatannya benar-benar diterapkan.

## Mengelola artikel

Artikel dapat digunakan untuk edukasi, berita perusahaan, panduan perawatan, dan promo.

1. Buka **Pos > Tambah Pos Baru**.
2. Isi judul yang jelas.
3. Tulis isi artikel dengan paragraf pendek dan subjudul.
4. Pilih kategori artikel.
5. Tambahkan gambar unggulan.
6. Isi kutipan bila diperlukan.
7. Periksa tanggal, tautan, dan masa berlaku informasi.
8. Pratinjau lalu terbitkan.

Contoh topik artikel:

- cara memilih motor niaga sesuai usaha;
- perbedaan motor listrik dan sepeda listrik;
- checklist perawatan kendaraan;
- panduan dokumen pengajuan kredit;
- kegiatan dealer dan pelatihan;
- promo dengan tanggal mulai dan berakhir yang jelas.

## Mengelola menu navigasi

1. Buka **Tampilan > Menu**.
2. Pilih menu utama.
3. Tambahkan halaman atau tautan yang diperlukan.
4. Susun urutan dengan drag-and-drop.
5. Pilih lokasi **Menu utama**.
6. Simpan menu.

Struktur yang disarankan:

- Beranda
- Motor
- Layanan
- Tentang Kami
- Artikel
- Kontak

Hindari menu terlalu banyak. Gunakan maksimal sekitar enam atau tujuh item utama agar navigasi tetap mudah dipahami.

## Mengganti logo

1. Buka **Tampilan > Sesuaikan > Identitas Situs**.
2. Pilih **Logo**.
3. Unggah logo resmi.
4. Periksa versi desktop dan ponsel.
5. Terbitkan perubahan.

Beranda saat ini memakai bar keunggulan layanan, bukan logo mitra fiktif. Logo resmi mitra hanya boleh ditambahkan setelah memperoleh file dan persetujuan penggunaan. Jangan menampilkan logo perusahaan pembiayaan atau pihak lain bila hubungan kerja sama belum dikonfirmasi.

## Mengubah informasi cabang dan kontak

Data cabang dan nomor WhatsApp pada versi awal dikelola melalui konfigurasi tema. Perubahan data dilakukan oleh administrator atau pengembang yang berwenang melalui fungsi `motoshop_branches()` di file `functions.php`. Operator biasa tidak disarankan mengubah kode tema. Untuk penggunaan jangka panjang, bagian ini dapat dikembangkan menjadi halaman pengaturan agar pengelola yang berwenang dapat memperbarui data tanpa menyentuh kode.

Setiap perubahan cabang harus memeriksa:

- nama cabang;
- alamat lengkap;
- nomor WhatsApp;
- jam operasional;
- tautan Google Maps;
- wilayah layanan;
- status cabang aktif atau tidak aktif.

## Standar penulisan konten

- gunakan bahasa Indonesia yang jelas dan konsisten;
- gunakan kata “Anda” untuk komunikasi kepada pengunjung;
- hindari paragraf terlalu panjang;
- gunakan subjudul dan daftar untuk informasi teknis;
- tulis nama produk sesuai materi resmi;
- periksa ejaan, angka, satuan, harga, dan tanggal;
- hindari penggunaan huruf kapital pada seluruh kalimat;
- jangan menyalin artikel pihak lain tanpa izin;
- cantumkan periode pada setiap promo;
- gunakan teks alternatif pada gambar.

## Standar desain dan identitas

Tema menggunakan karakter visual merah, navy, putih, dan abu-abu dingin. Merah dipakai sebagai aksen dan tindakan utama, bukan sebagai warna seluruh halaman. Judul dan isi menggunakan maksimal dua kelompok font agar hierarki tetap jelas.

Saat menambahkan konten:

- hindari tulisan merah di atas latar merah;
- hindari teks abu-abu muda di atas latar putih;
- jangan memasukkan teks penting langsung ke dalam gambar;
- gunakan tombol hanya untuk tindakan;
- pertahankan ruang kosong agar halaman tidak terasa penuh;
- periksa tampilan pada ponsel.

## Keamanan akun

- gunakan kata sandi unik dan panjang;
- aktifkan autentikasi dua langkah bila tersedia;
- jangan mengirim kata sandi melalui grup chat;
- jangan memakai akun `admin` untuk seluruh staf;
- hapus akun pengguna yang sudah tidak bertugas;
- berikan peran sesuai kebutuhan;
- jangan memasang plugin atau tema dari sumber tidak resmi;
- lakukan pembaruan hanya setelah backup tersedia.

## Backup dan pemeliharaan

### Jadwal yang disarankan

| Kegiatan | Frekuensi |
|---|---|
| Memeriksa permintaan pelanggan | Setiap hari kerja |
| Memeriksa stok, harga, dan promo | Minimal mingguan |
| Memeriksa tautan dan formulir | Bulanan |
| Backup database | Harian atau mingguan sesuai aktivitas |
| Backup seluruh file | Mingguan dan sebelum pembaruan besar |
| Pembaruan WordPress/plugin | Terjadwal setelah backup dan pengujian |
| Audit akun pengguna | Setiap tiga bulan |
| Audit konten produk | Setiap tiga bulan atau saat ada katalog baru |

Simpan backup di lokasi yang berbeda dari server website. Backup yang hanya berada pada komputer atau server yang sama belum cukup aman. Lakukan uji pemulihan secara berkala.

## Pembaruan WordPress dan plugin

1. Buat backup database dan file.
2. Catat versi sebelum pembaruan.
3. Perbarui satu komponen pada satu waktu.
4. Periksa beranda, katalog, detail produk, formulir, dan dashboard.
5. Jika terjadi masalah, hentikan pembaruan berikutnya dan pulihkan dari backup atau hubungi pengembang.

Jangan mengubah file inti WordPress, WooCommerce, atau plugin. Perubahan desain dan fungsi khusus harus dilakukan pada tema atau plugin khusus.

## Troubleshooting

### Produk tidak muncul di katalog

- pastikan status produk sudah Terbit;
- pastikan visibilitas katalog tidak disembunyikan;
- pastikan gambar dan kategori sudah dipilih;
- bersihkan cache browser atau plugin cache;
- periksa apakah WooCommerce aktif.

### Harga tidak muncul

- periksa Harga reguler pada Data produk;
- jika harga dikosongkan, tampilan “Hubungi sales” merupakan perilaku yang memang dirancang;
- pastikan format angka tidak memakai simbol mata uang.

### Spesifikasi tidak muncul

- buka Data produk > Atribut;
- pastikan nilai atribut sudah diisi;
- centang **Tampilkan di halaman produk**;
- klik Simpan atribut dan Perbarui produk.

### Gambar terlihat pecah atau terpotong

- unggah gambar dengan resolusi lebih baik;
- gunakan rasio yang konsisten;
- jangan memperbesar gambar kecil;
- regenerasi thumbnail jika ukuran gambar tema berubah.

### Formulir tidak masuk

- lakukan pengujian menggunakan data nonpribadi;
- pastikan semua kolom wajib terisi;
- periksa menu Permintaan Motor;
- pastikan tidak ada plugin keamanan atau cache yang memblokir `admin-post.php`;
- periksa log PHP bila masalah berlanjut.

### Menu website tidak sesuai

- buka Tampilan > Menu;
- periksa lokasi Menu utama dan Menu footer;
- simpan ulang menu;
- bersihkan cache.

### Website lambat

- kompres gambar;
- hapus plugin yang tidak diperlukan setelah diperiksa;
- gunakan cache yang sesuai;
- periksa kapasitas hosting;
- hindari video besar yang diputar otomatis;
- lakukan optimasi database secara terencana.

## Skenario praktik pelatihan

### Latihan 1 — Menambah produk

Peserta membuat satu produk draf dengan nama “Produk Latihan”, memilih kategori, menambahkan satu gambar, menulis deskripsi, dan memasukkan minimal lima atribut. Produk tidak perlu diterbitkan ke publik.

### Latihan 2 — Memperbarui produk

Peserta mengubah deskripsi, status stok, dan satu atribut pada Produk Latihan, lalu menggunakan Pratinjau untuk memeriksa hasil.

### Latihan 3 — Mengelola permintaan

Instruktur mengirim formulir uji dengan data fiktif. Peserta membuka Permintaan Motor, memeriksa data, dan mengubah status dari Baru menjadi Dihubungi lalu Selesai.

### Latihan 4 — Menulis artikel

Peserta membuat artikel draf berjudul “Tips Memilih Kendaraan untuk Usaha”, menambahkan subjudul, daftar, dan gambar unggulan.

### Latihan 5 — Pemeriksaan kualitas

Peserta memeriksa website pada desktop dan ponsel, menguji semua tombol utama, membuka satu produk, dan mengirim formulir uji.

### Hasil yang diharapkan

Setelah pelatihan, peserta mampu:

- masuk dan keluar dari dashboard dengan aman;
- menambahkan serta memperbarui produk;
- mengelola foto, harga, stok, kategori, dan spesifikasi;
- menindaklanjuti permintaan pelanggan;
- menerbitkan artikel;
- mengenali masalah umum;
- menjalankan checklist sebelum publikasi.

Keberhasilan pelatihan ditandai dengan peningkatan nilai rata-rata tes akhir sekurang-kurangnya 20% dibandingkan tes awal serta kemampuan sedikitnya dua staf dalam menambah dan memperbarui produk, memeriksa permintaan pelanggan, mengubah status tindak lanjut, mengelola konten promosi, dan melakukan pencadangan dasar secara mandiri.

## Checklist sebelum produk diterbitkan

- [ ] Nama resmi produk sudah benar.
- [ ] Kategori produk sudah dipilih.
- [ ] Deskripsi singkat dan lengkap sudah diperiksa.
- [ ] Harga sudah dikonfirmasi atau sengaja dikosongkan.
- [ ] Status stok sudah benar.
- [ ] Foto utama dan galeri sudah tersedia.
- [ ] Teks alternatif gambar sudah diisi.
- [ ] Spesifikasi berasal dari sumber resmi.
- [ ] Satuan dan penulisan atribut konsisten.
- [ ] Informasi promo memiliki periode yang jelas.
- [ ] Tampilan desktop dan ponsel sudah diperiksa.
- [ ] Tombol konsultasi mengarah ke formulir yang benar.

## Checklist sebelum website dipublikasikan

### Identitas dan konten

- [ ] Logo resmi sudah digunakan.
- [ ] Nama dan profil perusahaan sudah disetujui.
- [ ] Semua alamat dan nomor kontak sudah diverifikasi.
- [ ] Jam operasional cabang sudah benar.
- [ ] Logo mitra hanya ditampilkan bila file dan izin penggunaannya sudah dikonfirmasi.
- [ ] Produk contoh sudah diperbarui dengan data resmi.
- [ ] Artikel contoh atau teks dummy sudah dihapus.

### Fungsi

- [ ] Beranda dapat dibuka tanpa error.
- [ ] Katalog menampilkan produk.
- [ ] Filter kategori bekerja.
- [ ] Detail dan spesifikasi produk tampil benar.
- [ ] Formulir dapat dikirim.
- [ ] Data masuk ke Permintaan Motor.
- [ ] Tautan WhatsApp bekerja.
- [ ] Menu desktop dan ponsel bekerja.
- [ ] Semua tautan penting sudah diuji.

### Keamanan dan operasional

- [ ] Kata sandi awal sudah diganti.
- [ ] Akun pengguna dibuat sesuai peran.
- [ ] Backup awal sudah dibuat.
- [ ] WordPress dan plugin penting sudah diperbarui serta diuji.
- [ ] Kebijakan privasi tersedia.
- [ ] HTTPS aktif pada website produksi.
- [ ] Prosedur penanggung jawab permintaan pelanggan sudah ditetapkan.

## Form evaluasi pelatihan

Instruktur dapat menggunakan pertanyaan berikut:

1. Apakah peserta dapat menambahkan produk tanpa bantuan?
2. Apakah peserta memahami perbedaan deskripsi, deskripsi singkat, kategori, dan atribut?
3. Apakah peserta dapat mengubah status permintaan pelanggan?
4. Apakah peserta mengetahui kapan harga harus ditampilkan atau dikosongkan?
5. Apakah peserta memahami risiko membagikan akun administrator?
6. Apakah peserta dapat melakukan pemeriksaan sebelum publikasi?
7. Bagian mana yang masih memerlukan pendampingan?

## Catatan pengembangan berikutnya

Fitur yang dapat dipertimbangkan setelah alur dasar berjalan stabil:

- pengaturan cabang dan nomor WhatsApp dari dashboard;
- pembagian permintaan otomatis berdasarkan cabang;
- ekspor laporan permintaan ke CSV;
- notifikasi email atau WhatsApp untuk permintaan baru;
- simulasi kredit dengan disclaimer yang sesuai;
- filter produk berdasarkan kegunaan, harga, tenaga, atau daya angkut;
- status stok per cabang;

- integrasi analitik dengan persetujuan privasi;
- halaman kebijakan privasi dan persetujuan data yang lebih lengkap.

Prioritaskan keakuratan data, kecepatan tindak lanjut, dan kemampuan operator sebelum menambahkan fitur yang lebih kompleks.

## Kontak dukungan internal

Isi bagian berikut sebelum panduan dibagikan kepada peserta:

| Kebutuhan | Penanggung jawab | Kontak |
|---|---|---|
| Persetujuan konten | Belum ditentukan | Belum diisi |
| Data produk dan harga | Belum ditentukan | Belum diisi |
| Tindak lanjut sales | Belum ditentukan | Belum diisi |
| Masalah teknis website | Belum ditentukan | Belum diisi |
| Backup dan keamanan | Belum ditentukan | Belum diisi |

---

Dokumen ini perlu diperbarui setiap kali terjadi perubahan besar pada menu, alur kerja, plugin, kebijakan perusahaan, atau tanggung jawab pengelola website.
