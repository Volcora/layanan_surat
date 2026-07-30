<?php
header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_word'])) {
    
    // Cek ekstra apakah id_pengajuan benar-benar sampai ke PHP
    if (!isset($_POST['id_pengajuan']) || empty($_POST['id_pengajuan'])) {
        echo json_encode(array("sukses" => false, "pesan" => "ID Pengajuan kosong dari Android!"));
        exit;
    }

    $id_pengajuan = $_POST['id_pengajuan'];
    
    // Mencari versi terakhir
    $query_versi = "SELECT MAX(versi) as last_versi FROM riwayat_draf WHERE id_pengajuan = $id_pengajuan";
    $result_versi = $koneksi->query($query_versi);
    $row_versi = $result_versi->fetch_assoc();
    $versi_baru = ($row_versi['last_versi'] != null) ? $row_versi['last_versi'] + 1 : 1;

    $nama_file = time() . "_" . basename($_FILES["file_word"]["name"]);
    $target_dir = "uploads/draf/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES["file_word"]["tmp_name"], $target_file)) {
        $query_insert = "INSERT INTO riwayat_draf (id_pengajuan, versi, file_draf) VALUES ($id_pengajuan, $versi_baru, '$nama_file')";
        
        if ($koneksi->query($query_insert) === TRUE) {
            
            // Lakukan Update Status
            $query_update = "UPDATE pengajuan_surat SET status = 'menunggu_kaprodi' WHERE id_pengajuan = $id_pengajuan";
            
            // PERBAIKAN: Tangkap penolakan dari Database
            if ($koneksi->query($query_update) === TRUE) {
                echo json_encode(array("sukses" => true, "pesan" => "Draf V$versi_baru berhasil dikirim ke Kaprodi!"));
            } else {
                // Pesan ini akan muncul di Toast Android jika ENUM salah
                echo json_encode(array("sukses" => false, "pesan" => "Gagal update status: " . $koneksi->error));
            }
            
        } else {
            echo json_encode(array("sukses" => false, "pesan" => "Gagal simpan riwayat: " . $koneksi->error));
        }
    } else {
        echo json_encode(array("sukses" => false, "pesan" => "Gagal mengunggah file."));
    }
} else {
    echo json_encode(array("sukses" => false, "pesan" => "Data atau file tidak valid."));
}
?>