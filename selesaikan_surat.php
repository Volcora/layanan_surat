<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengajuan = isset($_POST['id_pengajuan']) ? $_POST['id_pengajuan'] : '';
    $nomor_surat = isset($_POST['nomor_surat']) ? $_POST['nomor_surat'] : '';

    if (empty($id_pengajuan) || empty($nomor_surat)) {
        echo json_encode(["sukses" => false, "pesan" => "Data tidak lengkap"]);
        exit;
    }

    $query_cek = "SELECT file_draf FROM riwayat_draf WHERE id_pengajuan = '$id_pengajuan' ORDER BY id_riwayat DESC LIMIT 1";
    $result_cek = $koneksi->query($query_cek);
    
    if ($result_cek->num_rows > 0) {
        $row = $result_cek->fetch_assoc();
        $file_lama = $row['file_draf'];

        $nomor_bersih = str_replace(['/', '\\', ' '], '_', $nomor_surat);
        $ekstensi = pathinfo($file_lama, PATHINFO_EXTENSION);

        $file_baru = "Surat_" . $nomor_bersih . "." . $ekstensi;

        $path_lama = "uploads/" . $file_lama;
        $path_baru = "uploads/" . $file_baru;

        if (file_exists($path_lama)) {
            rename($path_lama, $path_baru);
        } else {
            $file_baru = $file_lama;
        }

        $query_update = "UPDATE pengajuan_surat SET nomor_surat = '$nomor_surat', status = 'selesai' WHERE id_pengajuan = '$id_pengajuan'";
        
        $query_riwayat = "INSERT INTO riwayat_draf (id_pengajuan, file_draf, catatan_revisi) VALUES ('$id_pengajuan', '$file_baru', 'Surat Resmi Selesai')";

        if ($koneksi->query($query_update) === TRUE && $koneksi->query($query_riwayat) === TRUE) {
            echo json_encode(["sukses" => true, "pesan" => "Surat berhasil diselesaikan dengan nomor resmi!"]);
        } else {
            echo json_encode(["sukses" => false, "pesan" => "Gagal update database: " . $koneksi->error]);
        }
    } else {
        echo json_encode(["sukses" => false, "pesan" => "File ACC dari Kaprodi tidak ditemukan"]);
    }
}
?>