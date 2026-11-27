Sistem Informasi Pengecekan, Pelaporan, dan Pengelolaan Fasilitas Kampus berbasis **PHP Native OOP & MySQL**.

1. **Singleton Pattern (`classes/Database.php`)**: Menjamin hanya ada satu instance koneksi PDO database selama siklus eksekusi aplikasi.
2. **Encapsulation (`classes/User.php`, `Auth.php`, `LaporanRepository.php`)**: Menyembunyikan properti koneksi (`private`/`protected $db`) dan membungkus logika bisnis ke dalam method terstruktur.
3. **Inheritance (`classes/Admin.php` & `classes/Pengguna.php` extends `User`)**: Mewarisi atribut dan method dasar pengelolaan profil/password dari parent class `User`.
4. **Polymorphism (`getDashboardUrl()`)**: Method `getDashboardUrl()` pada `User` di-override oleh `Admin` dan `Pengguna` untuk mengarahkan ke halaman dashboard masing-masing role secara dinamis.
5. **Entity & Repository Pattern (`classes/Laporan.php` & `LaporanRepository.php`)**: Memisahkan representasi objek data laporan dari operasi query database.
6. **Static Utility Methods (`classes/Validasi.php` & `Auth::check()`)**: Menyediakan helper validasi input dan pengecekan sesi tanpa perlu instansiasi objek berulang.
