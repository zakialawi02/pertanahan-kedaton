<?php
// --- 1. Konfigurasi Koneksi Database ---
// Ganti nilai-nilai ini dengan informasi database Anda.
$host = 'localhost'; // atau alamat IP server PostgreSQL Anda
$port = '5432'; // port default untuk PostgreSQL
$dbname = 'project1'; // nama database Anda
$user = 'postgres'; // username untuk mengakses database
$password = 'root'; // password untuk username tersebut

// --- 2. Membuat String Koneksi ---
// String ini akan digunakan oleh pg_connect().
$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
// $conn_string = "postgresql://postgres.oajlqvypzeshwdhbtqom:svSD72zFdjWSxZod@aws-0-ap-northeast-1.pooler.supabase.com:6543/postgres";

// --- 3. Melakukan Koneksi ---
// Menggunakan @ untuk menekan pesan error default dari PHP,
// karena kita akan menangani error secara manual.
$dbconn = @pg_connect($conn_string);

// --- 4. Memeriksa Hasil Koneksi ---
if (!$dbconn) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error.
    echo "Database Connection Failed!...";
    die('Koneksi ke database gagal: ' . pg_last_error());
} else {
    // Jika koneksi berhasil, tampilkan pesan sukses.
    // echo 'Berhasil terhubung ke database PostgreSQL!';
}
