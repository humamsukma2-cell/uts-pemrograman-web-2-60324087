<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php
    require_once 'config/database.php';

    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status = 'Aktif';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // TODO: Ambil dan sanitasi data dari form
        $kode = trim(htmlspecialchars($_POST['kode']));
        $nama = trim(htmlspecialchars($_POST['nama']));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi']));
        $status = $_POST['status'];

        // TODO: Validasi kode kategori
        if (empty($kode)) {
            $errors[] = "Kode kategori wajib diisi";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors[] = "Kode kategori harus 4-10 karakter";
        } elseif (!preg_match('/^KAT-/', $kode)) {
            $errors[] = "Kode harus diawali KAT-";
        }

        // TODO: Validasi nama kategori
        if (empty($nama)) {
            $errors[] = "Nama kategori wajib diisi";
        } elseif (strlen($nama) < 3 || strlen($nama) > 50) {
            $errors[] = "Nama kategori 3-50 karakter";
        }

        // TODO: Validasi deskripsi
        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors[] = "Deskripsi maksimal 200 karakter";
        }

        // memvalidasi status
        if ($status != 'Aktif' && $status != 'Nonaktif') {
            $errors[] = "Status tidak valid";
        }

        // TODO: Cek duplikasi kode
        if (empty($errors)) {
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ?");
            $cek->bind_param("s", $kode);
            $cek->execute();
            $result = $cek->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Kode kategori sudah digunakan";
            }

            $cek->close();
        }

        // TODO: Jika tidak ada error, insert data
        if (count($errors) == 0) {
            $stmt = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $kode, $nama, $deskripsi, $status);

        // TODO: Redirect jika berhasil
            if ($stmt->execute()) {
                header("Location: index.php?success=Kategori berhasil ditambahkan");
                exit();
            } else {
                $errors[] = "Gagal menyimpan data";
            }

            $stmt->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">

                        <!-- TODO: Tampilkan error jika ada -->
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
                            <div class="mb-3">
                                <label>Kode Kategori *</label>
                                <input type="text" name="kode" class="form-control"
                                    value="<?php echo $kode; ?>" placeholder="KAT-001" required> 
                            </div>
                             <div class="mb-3">
                                <label>Nama Kategori *</label>
                                <input type="text" name="nama" class="form-control"
                                    value="<?php echo $nama; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"><?php echo $deskripsi; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Status</label><br>
                                <input type="radio" name="status" value="Aktif" <?php if ($status == 'Aktif') echo 'checked'; ?>> Aktif
                                <input type="radio" name="status" value="Nonaktif" <?php if ($status == 'Nonaktif') echo 'checked'; ?>> Nonaktif
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
