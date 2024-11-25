<?php
include 'db_connection.php';

// Tangani penambahan, pembaruan, dan penghapusan data jika formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $latitude = $conn->real_escape_string($_POST['latitude']);
        $longitude = $conn->real_escape_string($_POST['longitude']);
        
        $sql = "INSERT INTO health_services (name, address, latitude, longitude) 
                VALUES ('$name', '$address', $latitude, $longitude)";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $latitude = $conn->real_escape_string($_POST['latitude']);
        $longitude = $conn->real_escape_string($_POST['longitude']);
        
        $sql = "UPDATE health_services 
                SET name='$name', address='$address', latitude=$latitude, longitude=$longitude 
                WHERE id=$id";
                
        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil diperbarui";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM health_services WHERE id=$id";
        $conn->query($sql);
    }
}

// Ambil data dari database
$result = $conn->query("SELECT * FROM health_services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Layanan Kesehatan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Manajemen Data Layanan Kesehatan</h1>

    <!-- Form Tambah Data -->
    <form method="POST">
        <h2>Tambah Layanan Kesehatan</h2>
        <input type="text" name="name" placeholder="Nama" required>
        <input type="text" name="address" placeholder="Alamat" required>
        <input type="number" step="any" name="latitude" placeholder="Latitude" required>
        <input type="number" step="any" name="longitude" placeholder="Longitude" required>
        <button type="submit" name="add">Tambah</button>
    </form>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= $row['latitude'] ?></td>
                    <td><?= $row['longitude'] ?></td>
                    <td>
                        <!-- Form Update -->
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                            <input type="text" name="address" value="<?= htmlspecialchars($row['address']) ?>" required>
                            <input type="number" step="any" name="latitude" value="<?= $row['latitude'] ?>" required>
                            <input type="number" step="any" name="longitude" value="<?= $row['longitude'] ?>" required>
                            <button type="submit" name="update">Update</button>
                        </form>

                        <!-- Form Delete -->
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
