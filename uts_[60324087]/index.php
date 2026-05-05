<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php
    require_once 'config/database.php';

    // Query data kategori (prepared statement)
    $query = "SELECT * FROM kategori ORDER BY id_kategori DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>Kategori Buku</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="create.php" class="btn btn-primary"> Tambah Kategori
                </a>
            </div>
        </div>

        <!--Pesan sukses/error -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_GET['success']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Daftar Kategori Buku
                </h5>
            </div>
                    <div class="card-body">
                        <table class="table align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="100">Kode</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th width="100">Status</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $no = 1;
                                while ($row = $result->fetch_assoc()):
                                ?>

                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['kode_kategori']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>

                                        <td>
                                            <?php if ($row['status'] == 'Aktif'): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="edit.php?id=<?php echo $row['id_kategori']; ?>"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(<?php echo $row['id_kategori']; ?>)">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
            </div>
        </div>

        <script>
            function confirmDelete(id) {
                if (confirm('Yakin ingin menghapus kategori ini?')) {
                    window.location.href = 'delete.php?id=' + id;
                }
            }
        </script>

</body>

</html>