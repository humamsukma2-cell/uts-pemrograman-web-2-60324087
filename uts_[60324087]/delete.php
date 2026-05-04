<?php
require_once 'config/database.php';

// memvalidasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=ID tidak valid");
    exit();
}

$id = (int)$_GET['id'];

// mengecek apakah data dengan ID tersebut ada
$stmt = $conn->prepare("SELECT id_kategori FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// menghapus data
$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        header("Location: index.php?success=Data berhasil dihapus");
        exit();
    } else {
        header("Location: index.php?error=Gagal menghapus data");
        exit();
    }
} else {
    header("Location: index.php?error=Error database");
    exit();
}