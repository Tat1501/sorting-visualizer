<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$host = "localhost";
$dbname = "sorting_visualizer";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO sorting_results (algorithm, array_size, swaps, execution_time_ms) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $input['algorithm'],
        $input['array_size'],
        $input['swaps'],
        $input['execution_time_ms']
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
