# Tugas Praktikum 9 DPBO 2526 C2 (TP9DPBO2526C2)
Aplikasi **Sistem Rekrutmen** berbasis **PHP Native** dengan arsitektur **Model-View-Presenter (MVP)** pola **Strict Passive View** dan basis data MySQL melalui PDO (*Prepared Statements*) untuk pengelolaan data **Kandidat** dan **Lowongan Kerja**.

---

## Janji

> Saya Muhammad Zidan Mirza Fedrieka dengan NIM 2507692 mengerjakan Tugas Praktikum 9 dalam mata kuliah Desain Pemrograman Berorientasi Objek untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## 1. Desain Program & Arsitektur MVP (*Strict Passive View*)

### A. Pemisahan Tanggung Jawab (*Model-View-Presenter*)
1. **Model (`project/models/`)**:
   - Mengelola koneksi database (`DB.php`) dan seluruh kueri SQL dengan *Prepared Statements* PDO.
   - Menggunakan kontrak antarmuka `KontrakModel` dan `KontrakModelLowongan`.
   - Terdiri atas entity (`Kandidat`, `Lowongan`) dan pengelola tabel (`TabelKandidat`, `TabelLowongan`).
2. **View (`project/views/` & `project/template/`)**:
   - Menerapkan prinsip **Strict Passive View**: View hanya membaca template HTML (`skin.html`, `form.html`, `lowongan_skin.html`, `lowongan_form.html`) dan mengganti *placeholder token* (`{{...}}`).
   - View tidak mengakses Model/Entity secara langsung, tidak memiliki `foreach`, dan tidak melakukan perhitungan `count()`.
   - Menggunakan kontrak `KontrakView` dan `KontrakViewLowongan`.
3. **Presenter (`project/presenters/`)**:
   - Menjadi pusat komunikasi antara Model dan View melalui kontrak `KontrakPresenter` dan `KontrakPresenterLowongan`.
   - Mengambil data dari Model, menghitung total data, merakit baris HTML tabel (`<tr>...</tr>`), dan meneruskannya ke View.

### B. Skema Database (`mvp_kandidat_db`)

1. **Tabel `kandidat`**
   - `id` (`INT`, `AUTO_INCREMENT`, `PRIMARY KEY`)
   - `nama` (`VARCHAR(100)`)
   - `posisi` (`VARCHAR(100)`)
   - `domisili` (`VARCHAR(100)`)
   - `skorTes` (`INT`)
   - `pengalamanTahun` (`INT`)

2. **Tabel `lowongan`**
   - `id` (`INT`, `AUTO_INCREMENT`, `PRIMARY KEY`)
   - `judul` (`VARCHAR(100)`)
   - `departemen` (`VARCHAR(100)`)
   - `lokasi` (`VARCHAR(100)`)
   - `kuota` (`INT`)
   - `status` (`VARCHAR(30)`)

---

## 2. Alur Program

```mermaid
flowchart TD
    User([Pengguna / Browser]) -->|Request HTTP| Entry[project/index.php]
    Entry -->|Pilih Modul: kandidat / lowongan| Presenter[Presenter]
    Presenter -->|Panggil Query CRUD| Model[Model + PDO]
    Model <-->|Prepared Statement| DB[(MySQL: mvp_kandidat_db)]
    Model -->|Kembalikan Array Entity| Presenter
    Presenter -->|Rakit Baris HTML & Hitung Total| View[Passive View]
    View -->|Replace Token Template HTML| User
    Presenter -->|Redirect setelah POST| Entry
```

1. **Tampil Data (`screen=list`)**: `index.php` mengarahkan request ke `tampilkanKandidat()` atau `tampilkanLowongan()`. Presenter mengambil seluruh data dari Model, menyusun baris tabel HTML, lalu View mengganti token `{{TABLE_ROWS}}` dan `{{TOTAL_DATA}}` pada template.
2. **Tambah Data (`screen=add` & `action=tambah`)**: Presenter meminta View merender form kosong. Saat form dikirim via `POST`, `index.php` memanggil `tambahKandidat()` atau `tambahLowongan()` pada Presenter untuk menyimpan data melalui Model lalu mengarahkan kembali ke halaman daftar.
3. **Ubah Data (`screen=edit&id=...` & `action=ubah`)**: Presenter mengambil satu data berdasarkan ID melalui Model untuk mengisi nilai awal form. Saat form dikirim via `POST`, Presenter memperbarui data melalui Model lalu mengarahkan kembali ke halaman daftar.
4. **Hapus Data (`action=hapus`)**: Tombol hapus mengirim `POST` berisi `id` ke `index.php`, lalu Presenter memanggil `hapusKandidat()` atau `hapusLowongan()` pada Model dan mengarahkan kembali ke halaman daftar.

---

## 3. Panduan Menjalankan Program

### Prasyarat
- **PHP**: Versi 8.0 atau lebih baru (dengan ekstensi `pdo_mysql`).
- **Database**: MySQL / MariaDB (port default `3306`).

### Langkah Menjalankan
1. Nyalakan layanan MySQL, lalu impor berkas `mvp_db.sql`:
   ```bash
   mysql -u root < mvp_db.sql
   ```
2. Jalankan server PHP dari dalam direktori `project/`:
   ```bash
   cd project
   php -S localhost:8000
   ```
3. Buka browser dan akses `http://localhost:8000`.

---

## 4. Dokumentasi Program Saat Dijalankan

### A. Modul Kandidat (`kandidat`)
1. **Daftar Kandidat Awal**
   ![Daftar Kandidat](dokumentasi/01_daftar_kandidat.png)
2. **Form Tambah Kandidat**
   ![Form Tambah Kandidat](dokumentasi/02_form_tambah_kandidat.png)
3. **Hasil Tambah Kandidat**
   ![Hasil Tambah Kandidat](dokumentasi/03_hasil_tambah_kandidat.png)
4. **Form Ubah Data Kandidat**
   ![Form Ubah Data Kandidat](dokumentasi/04_form_ubah_kandidat.png)
5. **Hasil Ubah Data Kandidat**
   ![Hasil Ubah Data Kandidat](dokumentasi/05_hasil_ubah_kandidat.png)
6. **Konfirmasi Hapus Data Kandidat**
   ![Konfirmasi Hapus Kandidat](dokumentasi/13_hapus_kandidat.png)
7. **Hasil Hapus Data Kandidat**
   ![Hasil Hapus Data Kandidat](dokumentasi/15_hasil_hapus_kandidat.png)

### B. Modul Lowongan (`lowongan`)
1. **Daftar Lowongan Awal**
   ![Daftar Lowongan](dokumentasi/07_daftar_lowongan.png)
2. **Form Tambah Lowongan**
   ![Form Tambah Lowongan](dokumentasi/08_form_tambah_lowongan.png)
3. **Hasil Tambah Lowongan**
   ![Hasil Tambah Lowongan](dokumentasi/09_hasil_tambah_lowongan.png)
4. **Form Ubah Data Lowongan**
   ![Form Ubah Data Lowongan](dokumentasi/10_form_ubah_lowongan.png)
5. **Hasil Ubah Data Lowongan**
   ![Hasil Ubah Data Lowongan](dokumentasi/11_hasil_ubah_lowongan.png)
6. **Konfirmasi Hapus Data Lowongan**
   ![Konfirmasi Hapus Lowongan](dokumentasi/14_hapus_lowongan.png)
7. **Hasil Hapus Data Lowongan**
   ![Hasil Hapus Data Lowongan](dokumentasi/16_hasil_hapus_lowongan.png)
