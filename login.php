<?php
header('Content-Type: application/json');
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $response = array(
            "sukses" => true,
            "pesan" => "Login Berhasil",
            "data" => array(
                "id_user" => $user['id_user'],
                "nama_lengkap" => $user['nama_lengkap'],
                "role" => $user['role'],
                "nama_lengkap" => $user['nama_lengkap'],
                "nim" => $user['nim'],
                "nip" => $user['nip']
            )
        );
    } else {
        $response = array(
            "sukses" => false,
            "pesan" => "Username atau Password salah!"
        );
    }

    echo json_encode($response);
} else {
    echo json_encode(array("sukses" => false, "pesan" => "Metode request salah"));
}?>