<?php
header('Content-Type: application/json');
require 'koneksi.php';

$response = array();

$query = "SELECT * FROM pengajuan_surat 
          WHERE status IN ('diajukan', 'diproses', 'revisi_admin', 'acc_kaprodi') 
          ORDER BY tanggal_pengajuan DESC";

$result = $koneksi->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $response['sukses'] = true;
        $response['pesan'] = "Data antrean berhasil diambil.";
        $response['data'] = array();

        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    } else {

        $response['sukses'] = true; 
        $response['pesan'] = "Tidak ada antrean surat saat ini.";
        $response['data'] = array();
    }
} else {
    $response['sukses'] = false;
    $response['pesan'] = "Gagal mengambil data: " . $koneksi->error;
}

echo json_encode($response);?>