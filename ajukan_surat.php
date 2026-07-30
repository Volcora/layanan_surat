<?php
// (Opsional) Matikan error reporting agar peringatan PHP tidak merusak JSON jika ada kesalahan kecil
error_reporting(0); 

header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $jenis_surat = $_POST['jenis_surat'];
    $no_telepon = $_POST['no_telepon'];
    $keterangan = $_POST['keterangan'];
    $status = 'diajukan'; 

    // Query diperbaiki: Typo dihapus dan kolom 'keterangan' ditambahkan kembali dengan tanda kutip
    $query = "INSERT INTO pengajuan_surat (id_mahasiswa, jenis_surat, no_telepon, keterangan, status, tanggal_pengajuan) 
              VALUES ('$id_mahasiswa', '$jenis_surat', '$no_telepon', '$keterangan', '$status', NOW())";

    if ($koneksi->query($query) === TRUE) {
        echo json_encode(array("sukses" => true, "pesan" => "Pengajuan surat berhasil dikirim."));
    } else {
        echo json_encode(array("sukses" => false, "pesan" => "Gagal mengajukan surat."));
    }
}
?>