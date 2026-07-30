<?php
header('Content-Type: application/json');
require 'koneksi.php';

// Kaprodi HANYA melihat pengajuan yang sudah diproses admin (status 'menunggu_kaprodi')
$query = "SELECT p.*, u.nama_lengkap, u.nim FROM pengajuan_surat p 
          JOIN users u ON p.id_mahasiswa = u.id_user 
          WHERE p.status = 'menunggu_kaprodi' 
          ORDER BY p.tanggal_pengajuan DESC";
          
$result = $koneksi->query($query);

$data = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(array("sukses" => true, "data" => $data));
} else {
    // Kembalikan array kosong jika tidak ada antrean
    echo json_encode(array("sukses" => true, "data" => array()));
}?>