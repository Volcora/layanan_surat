<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../koneksi.php'; 

$response = array();

$query = "SELECT id_user, username, role, nama_lengkap, nim, nip FROM users ORDER BY role ASC, nama_lengkap ASC";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $data_akun = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data_akun[] = array(
            "id_user" => (int)$row['id_user'],
            "username" => $row['username'],
            "role" => $row['role'],
            "nama_lengkap" => $row['nama_lengkap'],
            "nim" => !empty($row['nim']) ? $row['nim'] : "", 
            "nip" => !empty($row['nip']) ? $row['nip'] : ""
        );
    }
    
    $response['sukses'] = true;
    $response['pesan'] = "Berhasil memuat daftar akun";
    $response['data'] = $data_akun;
    
} else {
    $response['sukses'] = false;
    $response['pesan'] = "Gagal mengambil data database: " . mysqli_error($koneksi);
    $response['data'] = array();
}

echo json_encode($response);?>