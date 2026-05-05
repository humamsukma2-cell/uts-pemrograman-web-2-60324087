<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    require_once 'config/database.php';

    $errors = [];

    // TODO: Ambil ID dari GET
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: index.php?error=ID tidak valid");
        exit();
    }

    $id = (int)$_GET['id'];

    // TODO: Retrieve data berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: index.php?error=Data tidak ditemukan");
        exit();
    }

    $data = $result->fetch_assoc();

    $kode = $data['kode_kategori'];
    $nama = $data['nama_kategori'];
    $deskripsi = $data['deskripsi'];
    $status = $data['status'];

    // TODO: Jika POST, proses update
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $kode = htmlspecialchars(trim($_POST['kode']));
        $nama = htmlspecialchars(trim($_POST['nama']));
        $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
        $status = $_POST['status'];

        // memvalidasi kode (sama seperti create)
        if (empty($kode)) {
            $errors[] = "Kode wajib diisi";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors[] = "Kode 4-10 karakter";
        } elseif (strpos($kode, "KAT-") !== 0) {
            $errors[] = "Kode harus diawali KAT-";
        }

        
        if (empty($nama)) {
            $errors[] = "Nama wajib diisi";
        } elseif (strlen($nama) < 3 || strlen($nama) > 50) {
            $errors[] = "Nama 3-50 karakter";
        }


        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors[] = "Deskripsi maksimal 200 karakter";
        }


        if ($status != 'Aktif' && $status != 'Nonaktif') {
            $errors[] = "Status tidak valid";
        }

        // mengecek duplikasi kode (kecuali untuk data yang sedang diedit)
        if (count($errors) == 0) {
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?");
            $cek->bind_param("si", $kode, $id);
            $cek->execute();

            if ($cek->get_result()->num_rows > 0) {
                $errors[] = "Kode sudah digunakan";
            }
        }

        // update data
        if (count($errors) == 0) {
            $stmt = $conn->prepare("UPDATE kategori SET kode_kategori=?, nama_kategori=?, deskripsi=?, status=? WHERE id_kategori=?");
            $stmt->bind_param("ssssi", $kode, $nama, $deskripsi, $status, $id);

            if ($stmt->execute()) {
                header("Location: index.php?success=Data berhasil diupdate");
                exit();
            } else {
                $errors[] = "Gagal update data";
            }
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Edit Kategori</h4>
                    </div>

                    <div class="card-body">

                        <!--error-->
                        <?php if (count($errors) > 0): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $e): ?>
                                        <li><?php echo $e; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST">

                            <!--kode-->
                            <div class="mb-3">
                                <label>Kode Kategori *</label> 
                                <input type="text" name="kode" class="form-control"
                                    value="<?php echo htmlspecialchars($kode); ?>">
                            </div>

                            <!--nama-->
                            <div class="mb-3">
                                <label>Nama Kategori *</label>
                                <input type="text" name="nama" class="form-control"
                                    value="<?php echo htmlspecialchars($nama); ?>">
                            </div>

                            <!--deskripsi-->
                             <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                            </div>

                            <!--status-->
                            <div class="mb-3">
                                <label>Status</label><br>
                                <input type="radio" name="status" value="Aktif"
                                    <?php if ($status == 'Aktif') echo 'checked'; ?>> Aktif
                                <input type="radio" name="status" value="Nonaktif"
                                    <?php if ($status == 'Nonaktif') echo 'checked'; ?>> Nonaktif
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
