<?php
header("Content-Type: application/json");
include 'koneksi.php'; // Sesuaikan dengan file koneksi database kamu

// Tangkap data dari Android
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$nim = isset($_POST['nim']) && trim($_POST['nim']) !== '' ? $_POST['nim'] : NULL;
$nip = isset($_POST['nip']) && trim($_POST['nip']) !== '' ? $_POST['nip'] : NULL;

// Validasi input wajib
if (empty($username) || empty($password) || empty($role) || empty($nama_lengkap)) {
    echo json_encode(['sukses' => false, 'pesan' => 'Formulir wajib (Username, Password, Role, Nama Lengkap) tidak boleh kosong!']);
    exit;
}

// Cek apakah username sudah digunakan
$cekUser = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
$cekUser->bind_param("s", $username);
$cekUser->execute();
$cekUser->store_result();
if ($cekUser->num_rows > 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'Username sudah terdaftar! Gunakan username lain.']);
    $cekUser->close();
    exit;
}
$cekUser->close();

// Insert data baru ke tabel users
$stmt = $conn->prepare("INSERT INTO users (username, password, role, nama_lengkap, nim, nip) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $username, $password, $role, $nama_lengkap, $nim, $nip);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Akun berhasil ditambahkan']);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal menambah akun: ' . $conn->error]);
}

$stmt->close();
$conn->close();?>