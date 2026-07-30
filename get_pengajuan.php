<?php
header('Content-Type: application/json');
require 'koneksi.php'; // Pastikan nama file koneksi ke databasemu benar

$response = array();

// Tangkap parameter 'role'
$role = isset($_GET['role']) ? $_GET['role'] : 'semua';

// QUERY YANG BENAR:
$base_query = "SELECT p.*, u.nama_lengkap, u.nim, 
               (SELECT r.file_draf FROM riwayat_draf r WHERE r.id_pengajuan = p.id_pengajuan ORDER BY r.id_riwayat DESC LIMIT 1) AS file_draf 
               FROM pengajuan_surat p 
               LEFT JOIN users u ON p.id_mahasiswa = u.id_user";

$kondisi = "";

// Filter berdasarkan role
if ($role == 'admin') {
    // Admin melihat antrean yang relevan
    $kondisi = " WHERE p.status IN ('diajukan', 'diproses', 'revisi_admin', 'acc_kaprodi')";
    
} else if ($role == 'kaprodi') {
    // Kaprodi HANYA melihat yang butuh validasi
    $kondisi = " WHERE p.status = 'menunggu_kaprodi'";
    
} else if ($role == 'mahasiswa') {
    // Mahasiswa HANYA melihat surat miliknya sendiri
    if (isset($_GET['id_mahasiswa'])) {
        $id_mhs = $koneksi->real_escape_string($_GET['id_mahasiswa']);
        $kondisi = " WHERE p.id_mahasiswa = '$id_mhs'";
    } else {
        // Jika parameter id_mahasiswa terlewat di Android, hentikan eksekusi
        $response['sukses'] = false;
        $response['pesan'] = "Parameter ID Mahasiswa tidak dikirim.";
        echo json_encode($response);
        exit;
    }
}

// Gabungkan query dasar dan urutkan dari yang paling baru
$query = $base_query . $kondisi . " ORDER BY p.tanggal_pengajuan DESC";

$result = $koneksi->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $response['sukses'] = true;
        $response['pesan'] = "Data pengajuan berhasil diambil.";
        $response['data'] = array();

        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    } else {
        $response['sukses'] = true; 
        $response['pesan'] = "Tidak ada antrean surat.";
        $response['data'] = array(); // Kirim array kosong agar RecyclerView di Android tidak error
    }
} else {
    $response['sukses'] = false;
    $response['pesan'] = "Gagal mengambil data: " . $koneksi->error;
}

echo json_encode($response);
?>