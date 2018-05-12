**Sistem Rekomendasi Bidang Studi**
# Spesifikasi Kebutuhan Perangkat Lunak

## Abstract
saat ini perkembangan teknologi informasi sudah berkembang pesat, salah satunya ialah pada sistem informasi. sistem informasi saat ini telah digunakan dalam berbagai bidang kehidupan untuk membantu mempermudah kegiatan serta pekerjaan banyak orang. salah satunya pada bidang pendidikan. di indonesia saat ini, masih banyak sekali siswa yang tidak menyadari atau salah menentukan bakat dan minat yang mereka miliki. hal ini menyebabkan potensi diri dari siswa tidak dapat berkembang. hal ini pula yang mempengaruhi kualitas lulusan siswa sekolah di Indonesia yag terkadang membuat siswa-siswa ini salah memilih bidang pada jenjang pendidikan selanjutnya. sistem informasi ini merupakan sistem informasi yang dapat membantu siswa dalam menentukan bidang pada jenjang pendidikan sebelumnya berdasarkan bakat dan minta yang dimiliki oleh siswa yang bersangkutan. diman pada sistem informasi ini akan diperlihatkan bidang bakat dan minat siswa yang paling menonjol sesuai dengan nilai akademik mauapun hasil tes psikologi yang didapatkan oleh siswa yang bersangkutan di sekolah. 

## Pendahuluan
sistem informasi merupakan salah satu contoh dari teknologi informasi yang sedang populer saat ini, dimana sistem informasi merupakan kombinasi dari teknologi informasi serta aktivitas orang pada suatu bidang untuk menunjang pada berbagai pengoperasian serta manajemen sistem. teknologi sistem informasi dapat menghubungangkan juataan informasi individual maupun organisasi di seluruh penjuru dunia. sehingga dengan aanya sistem informasi ini, dapat mengatasi semua keterbatasan waktu, jarak serta sarana dalam melakukan transaksi informasi dengan sangat mudah. salah satunya ialah lembaga pendidikan yang menggunakan sistem informasi untuk memudahkan dalam mengarahkan siswa serta orang tua untuk menentukan bidang minat dan bakat dari siswa di sekolah.

### Tujuan
Tujuan dari dokumen ini dibuat sebagai dokumentasi semua aktivitas yang dilakukan dalam pembuatan sistem informasi perkembangan siswa mulai dari perencanaan, analisis, desain dan implementasi. Tujuan dari sistem informasi ini dibuat untuk memudahkan siswa dalam memilih jenjang pendidikan berikutnya yang sesuai dengan bakat dan minatnya. Serta orang tua juga dapat melihat hasil perkembangan siswa selama di sekolah dan orang tua siswa dapat membuat janji dengan wali murid mengenai perkembangan siswa. Tujuan lain dari sistem informasi ini, sistem dapat merekomendasikan bidang studi yang sesuai dengan siswa bakat dan minat yang diambil melalui tes psikologi yang dilakukan oleh siswa selama kurun waktu tertentu.

### Ruang Lingkup
Sistem informasi perkembangan siswa merupakan sebuah informasi berbasis web yang menyediakan perkembangan siswa. Dalam sistem informasi tersebut dapat diakses oleh beberapa pengguna, yaitu siswa, orang tua, wali murid, kepala sekolah, dan lembaga psikologi. Pengguna sistem informasi dapat melakukan login, melihat perkembangan siswa, menampilkan jadwal janji temu guru dengan wali murid, membuat janji temu, menginputkan  hasil tes psikologi, melihat grafik perkembangan siswa dan sekolah. Sistem ini dikelola oleh admin yang bertugas untuk membuat source code yang dapat merekomendasikan bidang studi siswa. Data-data diambil dari Lembaga Psikologi yang kemudian diolah oleh admin sebagai bahan untuk rekomendasi bidang studi yang sesuai dengan mahasiswa.

### Definisi Istilah dan Singkatan
1. DFD : Data FLow Diagram, suatu diagram yang memungkinkan untuk menggambarkan suatu sistem sebagai suatu proses yang dihubungkan satu sama sama lain bertujuan untuk memahami sistem secara logika, terstuktur dan jelas.
2. Interface : Antar muka anatara perangkat lunak dan pengguna
3. Data Storage : Tempat penyimpanan data
4. API : Application Programming Interface, sekumpulan perintah, fungsi, serta protokol yang dapat digunakan oleh programmer saat membangun perangkat lunak untuk sistem operasi tertentu.
5. HTML : Hypertext Mark Language, bahasa yang paling banyak digunakan untuk menulis halaman web.

### Target Audience
Target audience yang tepat untuk sistem inforasi perkembangan siswa ini adalah sekolah-sekolah yang ingin meningkatkan hasil belajar siswa dalam bidang akademik.

## Deskripsi Umum Perangkat Lunak
### Deskripsi Umum Sistem
Sistem Informasi perkembangan siswa merupakan sebuah web yang dapat memperlihatkan perkembangan peserta didik dan dapat merekomendasikan bidang studi yang sesuai dengan bakat dan minat siswa. Pengguna aplikasi disini terbagi menjadi beberapa pengguna, siswa, orang tua, guru, kepala sekolah, dan lembaga psikologi. Pengguna pertama yaitu siswa dapat melakukan login, login sudah terdaftar secara otomatis. Data dari siswa sudah ada pada database yang disediakan oleh sistem. Siswa dapat melihat perkembangan belajarnya selama ini, test IQ yang dilakukan beberapa bulan sekali, dan dapat melihat rekomendasi dari sistem mengenai bakat dan minatnya. Pengguna kedua yaitu orang tua siswa, untuk masalah login sama dengan siswa. Akun dari orang tua sudah terdaftar dalam sistem yang tersimpan dalam database. DAlam sistem informasi perkembangan siswa, rang tua dapat melihat hal yang sama dengan siswa dan terdapat jadwal temu janji dengan guru yang disediakan oleh sistem untuk mengkonsultasikan siswa secara langsung dengan guru. Pengguna ketiga yaitu guru, dalam hal login sama dengan siswa dan orang tua. Dalam sistem informasi ini guru dapat menginputkan data dan nilai siswa pada sistem dan dapat menerima atau menolak jadwal temu janji yang dibuat oleh orang tua siswa. Pengguna keempat yaitu kepala sekolah, untuk hal login masih sama seperti siswa,orang tua, dan guru. Kepala sekolah dapat melihat grafik perkembangan siswa dan sekolah. 

### Karakteristik Pengguna
1. Siswa
Tugas : Melihat rekomendasi bidang studi
Hak : Mendapat hasil perkembangan dan rekomendasi dari sistem
2. Orang tua
Tugas :Melihat perkembangan anak 
Hak : Membuat janji temu denggan guru
3. Guru
Tugas : Menginput data dan nilai siswa
Hak : Menerima dan menolak janji temu yang dibuat orang tua
4. Kepala Sekolah
Tugas : Mengontrol perkembangan siswa dan sekolah
Hak : Melihat hasil perkembangan siswea dan sekolah
5. Lembaga Psikologi
Tugas : Memasukkan data hasil test psikologi
Hak : Tidak ada

### Batasan
dalam pembuatan sistem informasi perkembangan siswa ini, terdapat beberapa batasan yang dibuat. batasan-batasan tersebut adalah :
1. Pengecekan dapat dilakukan apabila pengguna memiliki akun yang tersimpan dalam sistem
2. orang tua dapat melakukan janji temu apabila guru menerima jadwal janji temu yang dibuat oleh orang tua.
3. sistem informasi hanya bisa diakses melalui web.

### Lingkungan Operasi
Komputer dengan spesifikasi hardware minimal sebagai berikut :
- Processor Intel Pentium 4 3 Ghz
- Motherboard support internet connection
- 2 GB RAM
- 500 GB Hard Disk Drive utama
- 500 GB Hard Disk Drive backup
- VGA Card 256 MB
- NIC FastEthernet atau Gigabit Ethernet
- Mouse
- Keyboard
- Monitor
- Power Supply
Komputer minimal telah terinstall software sebagai berikut
- Browser
- Apache HTTP Server
- Mysql Server

## Deskripsi Kebutuhan
### Antar Muka Pemakai
Antar muka yang digunakan untuk pengoperasian sistem informasi ini antara lain:
1. Tamppilan awal web
2. Tampilan rekomendasi bidang studi
3. Tampilan perkembangan siswa 
4. Tampilan pembuatan janji temu
5. Tampilan grafik siswa dan sekolah

### Fungsi-fungsi Perangkat Lunak
 - Rekomendasi bidang studi dari hasil perkembangan siswa
 - Management hasil perkembangan psikologi siswa
 - Informasi perkembangan siswa
 - Management konsultasi antara guru dan orang tua

### Batasan Desain dan Implementasi
Batasan desain yang ada pada pengembangan sistem informasi perkembangan siswa antara lain:
- Tampilan web dibuat dengan menggunakan CSS dan HTML
- Bahasa pemrograman yang digunakan adalah PHP

### Kebutuhan Fungsional
Kebutuhan Fungsional
1.	Management Perkembangan Psikologi
1.1.	System harus dapat menerima hasil test psikologi yang dimasukan oleh lembaga psikologi
2.	Informasi Perkembangan Siswa
2.1.	Harus dapat memberikan rekomendasi bidang minat & rekomendasi jenjang selanjutnya siswa kepada orang tua siswa dan guru
2.2.	System harus bisa memberikan informasi tentang perkembangan siswa kepada orang tua
2.3.	System harus bisa menampilkan laporan tentang perkembangan dan minat seluruh siswa kepada kepala sekolah
3.	Management Konsultasi
3.1.	System harus dapat memberikan akses kepada orang tua agar dapat meminta konsultasi kepada guru
3.2.	System harus bisa memungkinkan orang tua agar dapat membuat jadwal konsultasi kepada guru
3.3.	System harus dapat memungkinkan guru untuk membalas/memberikan balasan konsultasi dari orang tua
3.4.	System harus dapat memberi akses kepada guru untuk membuat janji konsultasi dari orang tua

### Kebutuhan Non-Fungsional
- Operasional
Sistem harus dapat digunakan pada web responsif
Sistem harus bisa terintegrasi dengan nilai rapot siswa
- Performa
Sistem harus dapat mmebrikan rekomendasi tidak lebih dari 1 menit
Sistem harus dapat mengirimkan pesan dengan cepat, tidak boleh lebih dari 30 detik
- Keamanan
Wali murid hanya bisa mengakses perkembangan anaknya
Hanya kepala sekolah yang dapat melihat seluruh perkembangan siswa
