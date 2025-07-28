<?php
header('Content-Type: application/json');

// Check for user query
if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    echo json_encode(['error' => 'No input provided.']);
    exit;
}

// Escape and sanitize input
$user_input = escapeshellarg(trim($_GET['query']));

// Full path to Python script
$python_script = 'C:\\wamp64\\www\\real_estate_ai_agent_project\\ai_agent.py';

// Validate Python script file
if (!file_exists($python_script)) {
    echo json_encode(['error' => 'Python script not found at: ' . $python_script]);
    exit;
}

// Command to run the script (use 'python3' or full path if needed)
$command = "python $python_script $user_input";

// Execute and capture output
$output = shell_exec($command);

// Check execution result
if ($output === null || trim($output) === '') {
    echo json_encode(['error' => 'No output received from AI agent or it crashed.']);
    exit;
}

// Try to decode JSON output
$response = json_decode($output, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'error' => 'Invalid JSON output from Python script.',
        'raw_output' => $output,
        'json_error' => json_last_error_msg()
    ]);
    exit;
}

// Return the decoded response
echo json_encode($response);
