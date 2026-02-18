# UAS Parallel Programming - Laravel + Dask

Aplikasi monitoring konsumsi energi listrik (Smart Grid) dengan parallel processing menggunakan Laravel, Python Dask, dan PostgreSQL.

### NIM = 235510013
### NAMA MAHASISWA = ADIKA VEMMASH NUGROHO


### Youtube Link Video = [Youtube Video](https://youtube.com/shorts/4mKBSKM7_9Q?si=Qjc144K8wZKNYtLv)


## 📋 Daftar Isi
- [Arsitektur](#arsitektur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Cara Instalasi](#cara-instalasi)
- [Cara Menjalankan Aplikasi](#cara-menjalankan-aplikasi)
- [Cara Menggunakan](#cara-menggunakan)
- [Troubleshooting](#troubleshooting)
- [Endpoint API](#endpoint-api)s

## 🏗️ Arsitektur

### Stack Teknologi
- **Frontend**: Laravel 10 (PHP 8.2)
- **Parallel Processing**: Python Flask + Dask DataFrame
- **Database**: PostgreSQL 15
- **Containerization**: Docker & Docker Compose

### Komponen Aplikasi
1. **Laravel App** (Port 8123): Web interface untuk menampilkan data
2. **Dask Service** (Port 5000): Microservice Python untuk parallel processing
3. **PostgreSQL** (Port 5432): Database dengan tabel `energy_consumption`

### Alur Parallel Processing
```
Browser → Laravel (8123) → Dask Service (5000) → PostgreSQL (5432)
              ↓                      ↓
         HTML Response      Dask DataFrame (4 partitions)
```

## 💻 Persyaratan Sistem

### Software yang Diperlukan
- **Docker**: versi 20.10 atau lebih baru
- **Docker Compose**: versi 2.0 atau lebih baru
- **Git**: untuk clone repository (opsional)

### Spesifikasi Minimum
- RAM: 2 GB (disarankan 4 GB)
- Storage: 2 GB ruang kosong
- OS: Windows 10/11, macOS 10.15+, atau Linux (Ubuntu 20.04+)

### Cek Instalasi Docker
```bash
# Cek versi Docker
docker --version

# Cek versi Docker Compose
docker-compose --version

# Cek Docker berjalan
docker ps
```

## 📦 Cara Instalasi

### 1. Install Docker (jika belum ada)

**Windows & macOS:**
- Download dan install [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Jalankan Docker Desktop setelah instalasi

**Linux (Ubuntu/Debian):**
```bash
# Update package
sudo apt update

# Install Docker
sudo apt install docker.io docker-compose -y

# Start Docker service
sudo systemctl start docker
sudo systemctl enable docker

# Tambahkan user ke docker group (agar tidak perlu sudo)
sudo usermod -aG docker $USER
newgrp docker
```

### 2. Download Aplikasi

**Jika menggunakan Git:**
```bash
git clone <repository-url>
cd <nama-folder-project>
```

**Jika download ZIP:**
- Extract file ZIP
- Buka terminal/command prompt di folder project

## 🚀 Cara Menjalankan Aplikasi

### Langkah 1: Build dan Jalankan Container

Buka terminal di folder project, lalu jalankan:

```bash
docker-compose up --build
```

**Penjelasan:**
- `docker-compose up`: Menjalankan semua service
- `--build`: Membangun ulang image Docker (gunakan saat pertama kali atau ada perubahan)

### Langkah 2: Tunggu Hingga Selesai

Proses ini akan:
1. Download image PostgreSQL (±100 MB)
2. Build image Laravel dan Dask Service
3. Membuat database dan tabel
4. Insert data sample (20 records)
5. Menjalankan semua service

**Indikator berhasil:**
```
laravel-app_1    | Server running on http://0.0.0.0:8123
dask-service_1   | Running on http://0.0.0.0:5000
db_1             | database system is ready to accept connections
```

### Langkah 3: Akses Aplikasi

Buka browser dan akses:
- **Halaman Utama**: http://localhost:8123
- **Data Monitoring**: http://localhost:8123/list
- **API JSON**: http://localhost:8123/api/list

## 📱 Cara Menggunakan

### Melihat Data Monitoring Energi

1. Buka browser
2. Akses: http://localhost:8123/list
3. Anda akan melihat:
   - Statistik parallel processing (total records, partisi, dll)
   - Tabel data konsumsi energi dari 20 meter
   - Informasi voltage, current, power, energy, dll

### Endpoint yang Tersedia

| Endpoint | Method | Deskripsi |
|----------|--------|-----------|
| `/` | GET | Halaman welcome Laravel |
| `/list` | GET | Tampilan HTML data monitoring dengan statistik |
| `/api/list` | GET | Output JSON untuk integrasi API |

### Contoh Response API

```bash
curl http://localhost:8123/api/list
```

Response:
```json
{
  "status": "success",
  "parallel_stats": {
    "total_records": 20,
    "avg_power": 1089.45,
    "max_energy": 2100.3,
    "total_partitions": 4,
    "peak_consumption": 12
  },
  "data": [...]
}
```

## 🛑 Menghentikan Aplikasi

### Cara 1: Stop dengan Ctrl+C
Tekan `Ctrl+C` di terminal yang menjalankan aplikasi

### Cara 2: Stop dan Hapus Container
```bash
docker-compose down
```

### Cara 3: Stop dan Hapus Semua (termasuk data)
```bash
docker-compose down -v
```
**Peringatan**: Ini akan menghapus semua data di database!

## 🔄 Menjalankan Ulang Aplikasi

### Jika sudah pernah build sebelumnya:
```bash
docker-compose up
```

### Jika ada perubahan code:
```bash
docker-compose up --build
```

### Restart service tertentu:
```bash
# Restart Laravel saja
docker-compose restart laravel-app

# Restart Dask service saja
docker-compose restart dask-service
```

## 🔧 Troubleshooting

### 1. Port Sudah Digunakan

**Error:**
```
Error: bind: address already in use
```

**Solusi:**
```bash
# Cek port yang digunakan
# Windows
netstat -ano | findstr :8123

# Matikan aplikasi
# Atau ubah port di docker-compose.yml
```

### 2. Docker Tidak Berjalan

**Error:**
```
Cannot connect to the Docker daemon
```

**Solusi:**
- Windows/macOS: Buka Docker Desktop
- Linux: `sudo systemctl start docker`

### 3. Database Connection Error

**Error:**
```
SQLSTATE[08006] Connection refused
```

**Solusi:**
```bash
# Restart semua container
docker-compose down
docker-compose up --build

# Cek log database
docker-compose logs db
```

### 4. Permission Denied (Linux)

**Error:**
```
permission denied while trying to connect to Docker daemon
```

**Solusi:**
```bash
# Tambahkan user ke docker group
sudo usermod -aG docker $USER
newgrp docker

# Atau jalankan dengan sudo
sudo docker-compose up --build
```

### 5. Build Error

**Solusi:**
```bash
# Hapus semua container dan image lama
docker-compose down -v
docker system prune -a

# Build ulang dari awal
docker-compose up --build
```

## 📊 Melihat Log Aplikasi

```bash
# Log semua service
docker-compose logs

# Log service tertentu
docker-compose logs laravel-app
docker-compose logs dask-service
docker-compose logs db

# Follow log real-time
docker-compose logs -f
```

## 🗄️ Akses Database

```bash
# Masuk ke PostgreSQL container
docker-compose exec db psql -U postgres -d uas_db

# Query data
SELECT * FROM energy_consumption LIMIT 5;

# Keluar
\q
```



## 🎯 Fitur Parallel Processing

Aplikasi ini menggunakan Dask DataFrame untuk:
- Membagi data menjadi 4 partisi
- Memproses data secara paralel
- Menghitung statistik (average, max, count) dengan efisien
- Menangani dataset besar dengan performa optimal
