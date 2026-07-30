<?php
header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengajuan = $_POST['id_pengajuan'];
    $nomor_surat = $_POST['nomor_surat'];

    // Ubah status menjadi selesai dan simpan nomor surat
    $query_update = "UPDATE pengajuan_surat SET status = 'selesai', nomor_surat = '$nomor_surat' WHERE id_pengajuan = $id_pengajuan";
    
    if ($koneksi->query($query_update) === TRUE) {
        echo json_encode(array("sukses" => true, "pesan" => "Surat berhasil diselesaikan dan dikirim ke Mahasiswa."));
    } else {
        echo json_encode(array("sukses" => false, "pesan" => "Gagal menyelesaikan surat."));
    }
}?>