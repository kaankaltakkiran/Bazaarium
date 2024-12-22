<?php
// Veritabanı bağlantısını dahil ediyoruz
$conn = new mysqli('localhost', 'root', 'root', 'Bazarrium');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// CORS başlıkları
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'districts': // İlçeleri getirme
                if (!isset($_GET['id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'City ID is required']);
                    exit;
                }

                $il_id = $_GET['id'];
                $sql = "SELECT id, ilce_adi FROM ilceler WHERE il_id = ?";
                
                // Add debug logging
                error_log("Fetching districts for il_id: " . $il_id);
                
                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    error_log("MySQL Error: " . $conn->error);
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
                    exit;
                }

                $stmt->bind_param('i', $il_id);
                $success = $stmt->execute();
                
                if (!$success) {
                    error_log("Execute Error: " . $stmt->error);
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Query execution failed']);
                    exit;
                }
                
                $result = $stmt->get_result();
                
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'id' => $row['id'],
                        'ilce_adi' => $row['ilce_adi']
                    ];
                }

                // Add debug logging
                error_log("Found " . count($data) . " districts");
                
                echo json_encode(['status' => 'success', 'data' => $data], JSON_UNESCAPED_UNICODE);
                break;

            case 'provinces': // İlleri getirme
                $sql = "SELECT id, il_adi FROM iller";
                $result = $conn->query($sql);

                if ($result) {
                    $provinces = [];

                    while ($row = $result->fetch_assoc()) {
                        $provinces[] = $row;
                    }

                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'data' => $provinces], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch provinces']);
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
                break;
        }
        break;

            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                break;
}

// Bağlantıyı kapatıyoruz
$conn->close();
?>
