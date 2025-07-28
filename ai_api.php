<?php
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

// Validate and sanitize inputs
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$budget = isset($_POST['budget']) ? floatval($_POST['budget']) : 0;
$rooms = isset($_POST['rooms']) ? intval($_POST['rooms']) : 0;

if (empty($location) || $budget <= 0 || $rooms <= 0) {
    echo json_encode(['error' => 'Please provide valid location, budget, and number of rooms']);
    exit;
}

// Compose the query string for the AI agent
$query = "Find properties in $location with a budget of $budget and $rooms bedrooms";
$safe_query = escapeshellarg($query);

// Set Python interpreter and script path (✅ update paths accordingly)
$python_path = "C:\\Users\\YourUsername\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";
$python_script = "C:\\wamp64\\www\\real_estate_ai_agent_project\\ai_agent.py";

// Check if Python script exists
if (!file_exists($python_script)) {
    echo json_encode(['error' => 'Python script not found at path: ' . $python_script]);
    exit;
}

// Execute Python script
$command = "\"$python_path\" \"$python_script\" $safe_query";
$output = shell_exec($command);

// Handle empty output
if (!$output) {
    echo json_encode(['error' => 'No response from AI agent or script execution failed']);
    exit;
}

// Decode JSON response
$response = json_decode($output, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'error' => 'Invalid JSON returned from AI agent',
        'raw_output' => $output
    ]);
    exit;
}

// Return the response
echo json_encode($response);
?>
