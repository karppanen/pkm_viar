# MotoShop Theme

Tema klasik WordPress dan WooCommerce untuk PT Mahkota Inti Sejahtera. Frontend dirancang sebagai dealer catalog commerce: pelanggan memilih kendaraan dan mengirim permintaan pembelian, lalu sales melanjutkan konsultasi tunai atau kredit.

## Alur sistem

1. Admin menambahkan produk melalui **Produk > Tambah baru**.
2. Pelanggan membuka katalog, memfilter kategori, dan membaca detail kendaraan.
3. Pelanggan memilih metode tunai atau kredit serta cabang terdekat.
4. Formulir disimpan ke **Permintaan Motor** pada dashboard WordPress.
5. Admin atau sales menghubungi pelanggan melalui WhatsApp.
6. Status diubah dari **Baru** menjadi **Dihubungi**, **Janji Kunjungan**, lalu **Selesai** atau **Batal**.
7. Jika kelak transaksi aksesori atau pembayaran tanda jadi dilakukan online, alur Cart dan Checkout WooCommerce tetap dapat digunakan.

## Struktur menu frontend

- **Beranda**: proposisi utama, kategori kendaraan, produk pilihan, layanan, alur pembelian, profil singkat, formulir, dan artikel.
- **Motor**: katalog WooCommerce untuk motor roda dua, motor niaga, ATV, dan sepeda listrik.
- **Layanan**: ringkasan Sales, Service, dan Sparepart pada beranda. Dapat dikembangkan menjadi halaman terpisah.
- **Tentang Kami**: profil PT Mahkota Inti Sejahtera, visi misi, jaringan cabang, dan tim.
- **Artikel**: panduan memilih kendaraan, perawatan, promo, dan kabar dealer.
- **Kontak**: nomor WhatsApp, Instagram, alamat cabang, jam operasional, dan peta.

## Data yang perlu disiapkan

Untuk setiap produk:

- nama dan kategori kendaraan;
- foto utama serta galeri dari beberapa sudut;
- harga tunai atau label hubungi sales;
- ringkasan kegunaan kendaraan;
- deskripsi lengkap;
- atribut WooCommerce: tipe mesin atau daya motor, tenaga, torsi, transmisi, kapasitas tangki atau baterai, dimensi, dan berat;
- warna, status stok, promo, dan cabang yang menyediakan unit.

Untuk informasi perusahaan:

- logo resmi beresolusi baik;
- alamat dan jam operasional setiap cabang;
- nomor WhatsApp tujuan per cabang;
- ketentuan kredit dan daftar dokumen dasar;
- foto showroom, bengkel, tim, dan aktivitas layanan;
- tautan marketplace serta media sosial;
- kebijakan privasi untuk pengumpulan data calon pelanggan.

## Setup awal WordPress

1. Aktifkan tema **MotoShop**.
2. Pastikan plugin WooCommerce aktif dan halaman Shop sudah dibuat.
3. Buat kategori produk: Motor Roda Dua, Motor Niaga, ATV, dan Sepeda Listrik.
4. Tambahkan produk beserta foto, harga, stok, deskripsi, dan atribut spesifikasi.
5. Buat menu di **Tampilan > Menu**, lalu pasang pada lokasi **Menu utama** dan **Menu footer**. Jika belum dibuat, tema memakai menu bawaan.
6. Unggah logo melalui **Tampilan > Sesuaikan > Identitas Situs**.
7. Buat halaman Tentang Kami, Layanan, Artikel, dan Kontak bila setiap bagian ingin memiliki halaman sendiri.
8. Uji formulir permintaan dan pastikan data muncul di **Permintaan Motor**.

## Catatan pengembangan

- Formulir memakai nonce WordPress, sanitasi input, dan penyimpanan sebagai custom post type nonpublik.
- Jangan mengubah file inti WordPress atau WooCommerce.
- Nomor WhatsApp, alamat, dan konten perusahaan saat ini mengikuti company profile yang diberikan dan sebaiknya dikonfirmasi sebelum website dipublikasikan.
- Katalog akan menampilkan produk WooCommerce secara otomatis. Bila produk masih kosong, admin melihat petunjuk pengisian dan pengunjung melihat keadaan katalog kosong yang terarah.
