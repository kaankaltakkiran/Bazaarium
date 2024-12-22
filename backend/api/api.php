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

            case 'POST':
                switch ($action) {
                    case 'register':
                        try {
                            // Enable error reporting
                            error_log("Starting registration process");

                            // Get JSON data from request body
                            $jsonData = file_get_contents('php://input');
                            error_log("Received data: " . $jsonData);

                            $data = json_decode($jsonData, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                error_log("JSON decode error: " . json_last_error_msg());
                                throw new Exception("Invalid JSON data");
                            }

                            // Debug log the received data
                            error_log("Processed data: " . print_r($data, true));

                            // Validate required fields
                            $required_fields = [
                                'first_name',
                                'last_name',
                                'username',
                                'email',
                                'password',
                                'user_type',
                                'city',
                                'district',
                                'birth_of_date'
                            ];

                            foreach ($required_fields as $field) {
                                if (!isset($data[$field]) || empty($data[$field])) {
                                    throw new Exception("Missing required field: $field");
                                }
                            }

                            // Check if username or email already exists
                            $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
                            $check_stmt = $conn->prepare($check_sql);
                            if (!$check_stmt) {
                                error_log("Prepare Error (check): " . $conn->error);
                                throw new Exception("Database error during user check");
                            }

                            $check_stmt->bind_param("ss", $data['username'], $data['email']);
                            if (!$check_stmt->execute()) {
                                error_log("Execute Error (check): " . $check_stmt->error);
                                throw new Exception("Error checking existing user");
                            }

                            $result = $check_stmt->get_result();
                            if ($result->num_rows > 0) {
                                throw new Exception("Username or email already exists");
                            }

                            // Hash password
                            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

                            // Insert user data
                            $sql = "INSERT INTO users (
                                first_name,
                                last_name,
                                username,
                                email,
                                password,
                                phone_number,
                                user_type,
                                city_id,
                                district_id,
                                birth_of_date
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                            error_log("Preparing insert statement");
                            $stmt = $conn->prepare($sql);
                            if (!$stmt) {
                                error_log("Prepare Error (insert): " . $conn->error);
                                throw new Exception("Database error during preparation");
                            }

                            error_log("Binding parameters");
                            $bind_result = $stmt->bind_param("sssssssiis",
                                                             $data['first_name'],
                                                             $data['last_name'],
                                                             $data['username'],
                                                             $data['email'],
                                                             $hashed_password,
                                                             $data['phone_number'],
                                                             $data['user_type'],
                                                             $data['city'],
                                                             $data['district'],
                                                             $data['birth_of_date']
                            );

                            if (!$bind_result) {
                                error_log("Bind Error: " . $stmt->error);
                                throw new Exception("Error binding parameters");
                            }

                            error_log("Executing insert");
                            if (!$stmt->execute()) {
                                error_log("Execute Error (insert): " . $stmt->error);
                                throw new Exception("Error creating user: " . $stmt->error);
                            }

                            http_response_code(201);
                            echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);

                        } catch (Exception $e) {
                            error_log("Registration Error: " . $e->getMessage());
                            http_response_code(500);
                            echo json_encode([
                                'status' => 'error',
                                'message' => $e->getMessage(),
                                             'debug_message' => 'Check server logs for more details'
                            ]);
                        }
                        break;

                    case 'login':
                        try {
                            $jsonData = file_get_contents('php://input');
                            $data = json_decode($jsonData, true);

                            if (!isset($data['email']) || !isset($data['password'])) {
                                throw new Exception('Email and password are required');
                            }

                            $sql = "SELECT id, first_name, last_name, username, email, password, user_type FROM users WHERE email = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $data['email']);
                            
                            if (!$stmt->execute()) {
                                throw new Exception('Login failed');
                            }

                            $result = $stmt->get_result();
                            $user = $result->fetch_assoc();

                            if (!$user || !password_verify($data['password'], $user['password'])) {
                                throw new Exception('Invalid email or password');
                            }

                            // Remove password from response
                            unset($user['password']);
                            
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Login successful',
                                'user' => $user
                            ]);

                        } catch (Exception $e) {
                            http_response_code(401);
                            echo json_encode([
                                'status' => 'error',
                                'message' => $e->getMessage()
                            ]);
                        }
                        break;

                        // ... existing cases ...
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
