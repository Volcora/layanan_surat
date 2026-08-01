<?php
// Set header agar output berupa JSON
header('Content-Type: application/json; charset=utf-8');

// Sertakan file koneksi database (Sesuaikan path file koneksimu, misal '../koneksi.php')
require_once '../koneksi.php'; 

$response = array();

// Query disesuaikan dengan struktur di image_168d5e.png (tanpa kolom email)
$query = "SELECT id_user, username, role, nama_lengkap, nim, nip FROM users ORDER BY role ASC, nama_lengkap ASC";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $data_akun = array();
    
    // Looping data dari database
    while ($row = mysqli_fetch_assoc($result)) {
        // Menangani nilai NULL dari database sesuai gambar (misal admin tidak punya nim, mhs tidak punya nip)
        $data_akun[] = array(
            "id_user" => (int)$row['id_user'],
            "username" => $row['username'],
            "role" => $row['role'],
            "nama_lengkap" => $row['nama_lengkap'],
            "nim" => !empty($row['nim']) ? $row['nim'] : "", 
            "nip" => !empty($row['nip']) ? $row['nip'] : ""
        );
    }
    
    // Format JSON respons yang sukses
    $response['sukses'] = true;
    $response['pesan'] = "Berhasil memuat daftar akun";
    $response['data'] = $data_akun;
    
} else {
    // Format JSON respons jika query gagal
    $response['sukses'] = false;
    $response['pesan'] = "Gagal mengambil data database: " . mysqli_error($koneksi);
    $response['data'] = array();
}

// Tampilkan output dalam format JSON
echo json_encode($response);?>