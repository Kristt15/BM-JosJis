# 📌 Progress Report: Sprint 2 (People) & Sprint 3 (Program) — COMPLETED

- **Tanggal Pelaporan:** 23 November 2026
- **Status:** ✅ Sprint 2 & 3 SELESAI & TERVERIFIKASI
- **Koordinator Proyek:** Andy Grove Persona
- **Tech Stack:** Laravel 13.29.0 | PHP 8.5.0 | SQLite Local | Tailwind CSS (CDN)

---

## 🎯 Capaian Sprint 2 & 3

### 1. Modul People Domain (Sprint 2)
Telah dibangun modul pengelolaan entitas People secara menyeluruh (Clean Architecture: Controller -> Service -> Form Request -> Model):

#### A. CRUD Volunteer (`/people/volunteers`)
- **Tabel Relawan**: Menampilkan daftar relawan lengkap dengan avatar inisial, NIM, jurusan, angkatan, badge status (Aktif/Alumni), dan tombol aksi.
- **Pencarian & Filter**: Form pencarian berdasarkan nama, NIM, jurusan, serta dropdown filter status.
- **Tambah Relawan Baru (`create` & `store`)**: Transaksi atomik (`DB::transaction`) yang otomatis membuat akun `User` (email + password) sekaligus profil `Volunteer`.
- **Detail Relawan (`show`)**: Menampilkan profil lengkap, kontak, riwayat batch/event yang pernah diikuti, serta statistik mengajar.
- **Edit & Update**: Form edit data diri, jurusan, angkatan, dan perubahan status (Aktif <-> Alumni).
- **Hapus (`destroy`)**: Menggunakan `SoftDeletes` sehingga rekam jejak mengajar relawan di masa lalu tetap terjaga.

#### B. CRUD Anak Asuh SRK (`/people/children`)
- **Tabel Anak SRK**: Daftar anak binaan Sekolah Rakyat Kejawan dengan kelas, jenis kelamin, dan batch saat ini.
- **Pencarian**: Filter cepat berdasarkan nama anak atau tingkatan kelas SD.
- **Pendaftaran Anak Baru**: Form input data anak, tanggal lahir, nama orang tua/wali, nomor kontak, dan alamat di Surabaya.
- **Detail Profil Anak (`show`)**: Rekam data anak, informasi wali, dan riwayat batch bimbingan.
- **Edit & Hapus**: Form update data serta proteksi `SoftDeletes`.

---

### 2. Modul Program Hierarchy (Sprint 3)
- **Daftar Program (`/programs`)**: Menampilkan daftar tahun penyelenggaraan BM, badge status (*Perencanaan*, *Aktif*, *Selesai*), dan tombol telusuri.
- **Detail Program (`/programs/{id}`)**: Halaman komprehensif menampilkan:
  - Kartu statistik (Total Fase, Total Batch, Relawan Terlibat, Anak Terdaftar, Total Event).
  - Linimasa Fase (Teaching Phase, Closing Phase, dll).
  - Kartu Batch beserta jumlah peserta dan daftar kegiatan/event per batch.

---

### 🧪 Status Pengujian Otomatis
- **Total Tests:** **50 tests (100% PASSED)**
- **Total Assertions:** **202 assertions**
- **Durasi Eksekusi:** ~3.2 detik
- **Status:** **0 Errors, 0 Failures**.

---

## ⏭️ Langkah Selanjutnya (Sprint 4: Presensi & Aktivitas)
Sesuai kebutuhan yang ditanyakan sebelumnya:
1. **Modul Presensi Volunteer & Anak**: Antarmuka checklist kehadiran pada setiap Event/Pertemuan.
2. **Jurnal Catatan Mengajar (Teaching Notes)**: Form input catatan materi dan evaluasi harian setelah mengajar.
