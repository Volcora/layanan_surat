<?php
header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengajuan = $_POST['id_pengajuan'];
    $catatan = $_POST['catatan_revisi'];

    $query_update = "UPDATE pengajuan_surat SET status = 'revisi_admin' WHERE id_pengajuan = $id_pengajuan";
    
    if ($koneksi->query($query_update) === TRUE) {
        $query_catatan = "UPDATE riwayat_draf SET catatan_revisi = '$catatan' WHERE id_pengajuan = $id_pengajuan ORDER BY versi DESC LIMIT 1";
        $koneksi->query($query_catatan);

        echo json_encode(array("sukses" => true, "pesan" => "Surat dikembalikan ke Admin untuk direvisi."));
    } else {
        echo json_encode(array("sukses" => false, "pesan" => "Gagal mengirim revisi."));
    }
}?>