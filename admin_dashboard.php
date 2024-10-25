<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost"; // Change if needed
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "resl"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($employee = $result->fetch_assoc()) {
        echo "ID: " . $employee['id'] . "<br>";
        echo "Name: " . $employee['name'] . "<br>";
        echo "Salary: " . $employee['salary'] . "<br>";
        echo "Working Unit: " . $employee['working_unit'] . "<br>";
        echo "District: " . $employee['district'] . "<br>";
        echo "Age: " . $employee['age'] . "<br>";
        echo "Permanent: " . ($employee['is_permanent'] ? 'Yes' : 'No') . "<br>";
        echo "Existing Loans: " . $employee['existing_loans'] . "<br>";
        echo "National ID Upload: <a href='" . $employee['national_id_upload'] . "'>View</a><br>";
        echo "Date of Employment: " . $employee['date_of_employment'] . "<br>";
        echo "<hr>"; // Divider between employees
    }
} else {
    echo "No employees found.";
}

// Close connection
$conn->close();
