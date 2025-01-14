<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kecamatan dan Layanan Kesehatan Banyumas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #map { height: 700px; }
    </style>
</head>
<body>
    <h1>Peta Kecamatan dan Layanan Kesehatan Kabupaten Banyumas</h1>
    <a href="crud.php" target="_blank"><button>Data</button></a>
    <div id="map"></div>

    <script>
        // Inisialisasi peta
        const map = L.map('map').setView([-7.450161992561026, 109.16218062235068], 11);

        // Tambahkan tile layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Load GeoJSON untuk batas kecamatan
        fetch('data/kecamatan.json')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function (feature) {
                        return {
                            color: 'blue', // Warna garis batas
                            weight: 2,     // Ketebalan garis
                            fillColor: 'lightblue', // Warna isi
                            fillOpacity: 0.5        // Transparansi isi
                        };
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Error loading GeoJSON:', error));

        // Tambahkan marker layanan kesehatan dari database
        <?php
        include 'db_connection.php';
        $sql = "SELECT name, latitude, longitude FROM health_services";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "L.marker([" . $row["latitude"] . ", " . $row["longitude"] . "]).addTo(map)
                    .bindPopup('<b>" . htmlspecialchars($row["name"]) . "</b>');\n";
            }
        }
        $conn->close();
        ?>
    </script>
</body>
</html>
