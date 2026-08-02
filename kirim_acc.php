<?php
header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_final'])) {
$id_pengajuan = intval($_POST['id_pengajuan']);
    
    $nama_file = time() . "_ACC_" . basename($_FILES["file_final"]["name"]);
    $target_dir = "uploads/final/";
    
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $target_file = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES["file_final"]["tmp_name"], $target_file)) {
        $query_update = "UPDATE pengajuan_surat SET status = 'acc_kaprodi' WHERE id_pengajuan = $id_pengajuan";
        $koneksi->query($query_update);
        
        $query_versi = "SELECT MAX(versi) as last_versi FROM riwayat_draf WHERE id_pengajuan = $id_pengajuan";
        $result_versi = $koneksi->query($query_versi);
        $row_versi = $result_versi->fetch_assoc();
        $versi_baru = ($row_versi['last_versi'] != null) ? $row_versi['last_versi'] + 1 : 1;

        $koneksi->query("INSERT INTO riwayat_draf (id_pengajuan, versi, file_draf, catatan_revisi) VALUES ($id_pengajuan, $versi_baru, '$nama_file', 'Telah di-ACC Kaprodi')");

        echo json_encode(array("sukses" => true, "pesan" => "Surat berhasil di-ACC dan dikirim ke Admin."));
    } else {
        echo json_encode(array("sukses" => false, "pesan" => "Gagal mengunggah surat."));
    }
}?>