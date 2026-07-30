<?php
header('Content-Type: application/json');
require 'koneksi.php'; // Memanggil file koneksi yang dibuat sebelumnya

// Pastikan request yang masuk adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangkap data dari Android (gunakan isset untuk mencegah error jika kosong)
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Query untuk mencocokkan data
    // Catatan: Untuk tahap testing/belajar, password menggunakan plain text. 
    // Untuk tahap produksi, sangat disarankan menggunakan hashing (misal: password_hash).
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        // Jika data ditemukan
        $user = $result->fetch_assoc();
        
        $response = array(
            "sukses" => true,
            "pesan" => "Login Berhasil",
            "data" => array(
                "id_user" => $user['id_user'],
                "nama_lengkap" => $user['nama_lengkap'],
                "role" => $user['role'],
                "nama_lengkap" => $user['nama_lengkap'],
                "nim" => $user['nim'],
                "nip" => $user['nip']
            )
        );
    } else {
        // Jika data tidak ditemukan
        $response = array(
            "sukses" => false,
            "pesan" => "Username atau Password salah!"
        );
    }

    echo json_encode($response);
} else {
    echo json_encode(array("sukses" => false, "pesan" => "Metode request salah"));
}?>