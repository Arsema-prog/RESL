<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Get the branch_id from the POST request
$branch_id = $_POST['branch_id'] ?? null;
$loan_action = $_POST['loan_action'] ?? null;
$request_id = $_POST['request_id'] ?? null;

// Database connection
$host = 'localhost'; // Replace with your host
$dbname = 'resl'; // Replace with your database name
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Initialize data array
$data = [];

// Check if branch_id is provided
if ($branch_id) {
    // Fetch the branch name based on branch_id
    $branch_name_sql = "SELECT branch_name FROM hr_table WHERE branch_id = ? LIMIT 1";
    $stmt = $conn->prepare($branch_name_sql);
    $stmt->bind_param('i', $branch_id);
    $stmt->execute();
    $stmt->bind_result($branch_name);
    $stmt->fetch();
    $stmt->close();

    // Fetch granted amount for the branch from hr_table
    $granted_amount_sql = "SELECT total_amount_granted FROM hr_table WHERE branch_name = ?";
    $stmt = $conn->prepare($granted_amount_sql);
    $stmt->bind_param('s', $branch_name);
    $stmt->execute();
    $stmt->bind_result($branch_granted_amount);
    $stmt->fetch();
    $stmt->close();

    // Store branch details in the data array
    $data['branch_name'] = $branch_name;
    $data['branch_granted_amount'] = $branch_granted_amount;

    // Fetch pending requests for the branch
    $pending_sql = "SELECT * FROM branch_table WHERE loan_status = 'pending' AND branch_id = ?";
    $stmt = $conn->prepare($pending_sql);
    $stmt->bind_param('i', $branch_id);
    $stmt->execute();
    $pending_result = $stmt->get_result();

    $pending_requests = [];
    while ($row = $pending_result->fetch_assoc()) {
        $pending_requests[] = $row;
    }
    $data['pending_requests'] = $pending_requests;

    // Fetch granted loans for the branch
    $granted_sql = "SELECT * FROM branch_table WHERE loan_status = 'granted' AND branch_id = ?";
    $stmt = $conn->prepare($granted_sql);
    $stmt->bind_param('i', $branch_id);
    $stmt->execute();
    $granted_result = $stmt->get_result();

    $granted_loans = [];
    while ($row = $granted_result->fetch_assoc()) {
        $granted_loans[] = $row;
    }
    $data['granted_loans'] = $granted_loans;

    // If a loan action is provided (grant/deny), update the loan status
    if ($loan_action && $request_id) {
        $update_sql = "UPDATE branch_table SET loan_status = ? WHERE pending_request_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('si', $loan_action, $request_id);
        if ($stmt->execute()) {
            $data['loan_action_message'] = "Loan status updated successfully!";
        } else {
            $data['loan_action_message'] = "Error updating loan status: " . $conn->error;
        }
        $stmt->close();
    }
    
    // Return the final result as JSON
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No branch_id provided']);
}

// Close the database connection
$conn->close();
?>

