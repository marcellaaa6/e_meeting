<?php
require '../config/db.php';

$from = $_GET['from'] ?? '2000-01-01';
$to = $_GET['to'] ?? '2100-01-01';

$query = "SELECT * FROM meetings WHERE DATE(date_time) BETWEEN ? AND ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Meeting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">📅 Jadwal Meeting</h2>

    <!-- Notifikasi -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) === 'deleted' ? 'Meeting berhasil dihapus!' : 'Perubahan berhasil disimpan!' ?>
        </div>
    <?php endif; ?>

    <!-- Tombol Navigasi -->
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-outline-primary">⬅ Kembali ke Dashboard</a>
        <a href="add_meeting.php" class="btn btn-success">➕ Tambah Meeting</a>
    </div>

    <!-- Form Filter -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Dari</label>
            <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai</label>
            <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
        </div>
    </form>

    <!-- Tabel Meeting -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= date("d M Y H:i", strtotime($row['date_time'])) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td>
                            <a href="edit_meeting.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <a href="delete_meeting.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus meeting ini?')">🗑️ Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data meeting pada rentang tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
