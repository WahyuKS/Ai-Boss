# 🤖 AI Boss - Smart Business Operating System & Command Center

Sistem Operasi Bisnis berbasis SaaS (Software as a Service) yang mengintegrasikan Kecerdasan Buatan (AI) terpusat dengan Panel Komando (Command Center) 3D interaktif berkeamanan tingkat tinggi untuk manajemen pelanggan.

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![OpenAI](https://img.shields.io/badge/AI_Powered-412991?style=for-the-badge&logo=openai&logoColor=white)

## 📌 Tentang Proyek

**AI Boss** adalah solusi perangkat lunak Full-Stack yang dirancang untuk mendigitalisasi dan mengotomatisasi operasional bisnis berskala UMKM hingga Enterprise. Proyek ini memisahkan secara ketat ekosistem antara **Client Area** (antarmuka pengguna untuk interaksi AI) dan **Master Admin Area** (Pusat Kendali berkeamanan ganda). Dibangun untuk memberikan *output* AI yang presisi bagi member, sekaligus menyediakan arsitektur *database management* yang solid dan futuristik bagi Pemilik Sistem.

## ✨ Fitur Unggulan (Premium Features)

🛡️ **Dual-Layer Security & Stealth Routing:** 
Sistem keamanan *backend* menggunakan *Middleware* kustom yang memisahkan jalur *routing* Admin dan Member. Dilengkapi dengan otentikasi ganda: Login kredensial standar yang dilanjutkan dengan pemindaian **6-Digit Master PIN** terenkripsi (`Hash`) khusus untuk otorisasi panel admin.

🛸 **3D Cybernetic UI & Glassmorphism:** 
Antarmuka Master Admin dibangun menggunakan algoritma manipulasi *Shadow & Gradient* pada Tailwind CSS. Menghadirkan desain *Floating Cards*, *Cyber Grid Background*, dan animasi *Laser Scanner* vertikal yang memberikan efek kedalaman (3D) layaknya aplikasi *native*.

📡 **Event-Driven Last Login Tracking:** 
Memanfaatkan arsitektur **Laravel Events & Listeners** (`Illuminate\Auth\Events\Login`) yang berjalan di latar belakang (*background process*). Sistem secara otomatis mencatat *timestamp* setiap kali pengguna berhasil login ke dalam database tanpa membebani performa memori di sisi klien.

⚙️ **Advanced Algorithmic Search & Filter:** 
Panel *Command Center* dilengkapi dengan fungsi *query builder* dinamis. Memungkinkan Master Admin untuk mengeksekusi pencarian data pelanggan secara presisi berdasarkan Nama, Email, atau Filter Tanggal Pendaftaran dengan pemuatan data termutakhir (*Pagination with Query String*).

🧠 **7+ Context-Aware AI Modules (Client Area):** 
Terintegrasi dengan mesin AI untuk menghasilkan konten bisnis. Modul cerdas membaca `BusinessProfile` pengguna (Platform & Kategori) secara otomatis untuk meracik *Live Script*, *Caption*, *SOP Playbook*, dan *Customer Service Response* yang sangat relevan dengan niche bisnis.

## 💻 Tech Stack (Teknologi yang Digunakan)

**Frontend (Client-Side & UI/UX)**
*   **Framework CSS:** Tailwind CSS (Custom 3D Utilities & Animations)
*   **Build Tool:** Vite (Ultra-fast HMR)
*   **Templating Engine:** Laravel Blade (Component-based architecture)
*   **Typography:** Space Grotesk, Inter, Fira Code (Monospace for Terminal aesthetic)

**Backend (Server-Side & Database)**
*   **Framework:** Laravel 11.x
*   **Database:** MySQL / MariaDB (Relational Database)
*   **Authentication:** Laravel Session & Custom Middleware (`IsAdmin`, `EnsurePinVerified`)
*   **Architecture:** MVC (Model-View-Controller) dengan Event-Driven Processing

## 📸 Tangkapan Layar (Screenshots)

*(Ganti tautan gambar di bawah ini dengan screenshot aslimu)*

*   [Tampilan 3D Command Center & Laser Scanner (Admin)](screenshots/dasboardadmin.png)
*   [Layar Keamanan Master PIN Lock (Admin)](screenshots/systemlockAdmin.png)
*   [Dashboard & Action Plan Member (Client)](screenshots/dasboardPelanggan.png)
*   [Modul AI Profit Studio (Client)](screenshots/hppMargin.png)

## 🚀 Panduan Instalasi Lokal (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan sistem ini secara lokal di komputer (localhost) Anda.

### 1. Persiapan Repositori & Dependensi

```bash
# Clone repositori proyek
git clone [https://github.com/WahyuKS/ai-boss.git](https://github.com/WahyuKS/ai-boss.git)

# Masuk ke direktori proyek
cd ai-boss

# Instal dependensi PHP (Backend)
composer install

# Instal dependensi Node.js (Frontend)
npm install
