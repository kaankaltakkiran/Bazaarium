<?php
// CORS başlıkları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Veritabanı bağlantısı
$conn = new mysqli('localhost', '', '', '');

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// İlçeleri getirme
if (isset($_GET['il_id'])) {
    $il_id = intval($_GET['il_id']);

    $sql = "SELECT id, ilce_adi FROM ilceler WHERE il_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
        exit;
    }

    $stmt->bind_param('i', $il_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'ilce_adi' => $row['ilce_adi']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $data], JSON_UNESCAPED_UNICODE);
    $stmt->close();

    // Tüm illeri getirme
} else {
    $sql = "SELECT id, il_adi FROM iller";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch cities']);
        exit;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'il_adi' => $row['il_adi']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $data], JSON_UNESCAPED_UNICODE);
}

// Bağlantıyı kapat
$conn->close();
?>
