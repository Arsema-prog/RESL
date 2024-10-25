<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *'); // Allows requests from any origin
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Specifies allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Specifies allowed headers


header('Content-Type: application/json'); // Set content type to JSON

// Database connection
$mysqli = new mysqli("localhost", "root", "", "resl");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $mysqli->connect_error]);
    exit;
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    if (isset($_POST['employee_id'], $_FILES['employee_id_pic'], $_POST['guarantee_id1'], $_FILES['guarantee_id_pic1'])) {
        $employee_id = $_POST['employee_id'];
        $guarantee_id1 = $_POST['guarantee_id1'];
        $guarantee_id2 = $_POST['guarantee_id2'] ?? null; // Optional

        // Validate file uploads
        $employee_id_pic = $_FILES['employee_id_pic'];
        $guarantee_id_pic1 = $_FILES['guarantee_id_pic1'];
        $guarantee_id_pic2 = $_FILES['guarantee_id_pic2'] ?? null; // Optional

        if ($employee_id_pic['error'] === UPLOAD_ERR_OK && $guarantee_id_pic1['error'] === UPLOAD_ERR_OK) {
            if ($guarantee_id_pic2 && $guarantee_id_pic2['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Error uploading Guarantee 2 ID picture."]);
                exit;
            }

            // Save uploaded files
            $employee_id_pic_path = 'uploads/' . basename($employee_id_pic['name']);
            move_uploaded_file($employee_id_pic['tmp_name'], $employee_id_pic_path);

            $guarantee_id_pic1_path = 'uploads/' . basename($guarantee_id_pic1['name']);
            move_uploaded_file($guarantee_id_pic1['tmp_name'], $guarantee_id_pic1_path);

            if ($guarantee_id_pic2) {
                $guarantee_id_pic2_path = 'uploads/' . basename($guarantee_id_pic2['name']);
                move_uploaded_file($guarantee_id_pic2['tmp_name'], $guarantee_id_pic2_path);
            }

            // Fetch employee data
            $employee_query = "SELECT salary, working_unit, district, age, date_of_employment, existing_loans FROM general_employees WHERE id = ?";
            $employee_stmt = $mysqli->prepare($employee_query);
            $employee_stmt->bind_param("i", $employee_id);
            $employee_stmt->execute();
            $employee_result = $employee_stmt->get_result();

            if ($employee_result->num_rows > 0) {
                $employee_data = $employee_result->fetch_assoc();

                // Fetch guarantee data for guarantee ID 1
                $guarantee_query = "SELECT salary, age, is_permanent FROM general_employees WHERE id = ?";
                $guarantee_stmt1 = $mysqli->prepare($guarantee_query);
                $guarantee_stmt1->bind_param("i", $guarantee_id1);
                $guarantee_stmt1->execute();
                $guarantee_result1 = $guarantee_stmt1->get_result();

                if ($guarantee_result1->num_rows > 0) {
                    $guarantee_data1 = $guarantee_result1->fetch_assoc();

                    if ($guarantee_id2) {
                        $guarantee_stmt2 = $mysqli->prepare($guarantee_query);
                        $guarantee_stmt2->bind_param("i", $guarantee_id2);
                        $guarantee_stmt2->execute();
                        $guarantee_result2 = $guarantee_stmt2->get_result();
                        $guarantee_data2 = ($guarantee_result2->num_rows > 0) ? $guarantee_result2->fetch_assoc() : null;
                    }

                    // Perform checks and add appropriate messages to $response
                    if ($employee_data['age'] >= 60) {
                        echo json_encode(["error" => "Requester must be under 60 years old."]);
                        exit;
                    }

                    if ($guarantee_data1['age'] >= 60) {
                        echo json_encode(["error" => "Guarantee 1 must be under 60 years old."]);
                        exit;
                    }

                    if ($guarantee_id2 && $guarantee_data2 && $guarantee_data2['age'] >= 60) {
                        echo json_encode(["error" => "Guarantee 2 must be under 60 years old."]);
                        exit;
                    }

                    if ($guarantee_data1['is_permanent']) {
                        // Ensure the guarantee has not guaranteed for more than two employees
                        $guarantee_check_query = "SELECT COUNT(*) AS guarantee_count FROM employees WHERE guarantee_id = ?";
                        $guarantee_check_stmt = $mysqli->prepare($guarantee_check_query);
                        $guarantee_check_stmt->bind_param("i", $guarantee_id1);
                        $guarantee_check_stmt->execute();
                        $guarantee_check_result = $guarantee_check_stmt->get_result();
                        $guarantee_count = $guarantee_check_result->fetch_assoc()['guarantee_count'];

                        if ($guarantee_count < 2) {
                            if ($guarantee_data1['salary'] >= $employee_data['salary']) {
                                $date_of_employment = $employee_data['date_of_employment'];
                                $current_date = date("Y-m-d");
                                $work_period_months = (strtotime($current_date) - strtotime($date_of_employment)) / (60 * 60 * 24 * 30);

                                if ($work_period_months >= 3) {
                                    // Insert data into employees table
                                    $insert_stmt = $mysqli->prepare("INSERT INTO employees (employee_id, guarantee_id, salary, working_unit, district, age, existing_loans, date_of_employment, employee_id_picture, guarantee_id_picture1, guarantee_id_picture2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    $insert_stmt->bind_param("iisississss", $employee_id, $guarantee_id1, $employee_data['salary'], $employee_data['working_unit'], $employee_data['district'], $employee_data['age'], $employee_data['existing_loans'], $date_of_employment, $employee_id_pic_path, $guarantee_id_pic1_path, $guarantee_id_pic2 ? $guarantee_id_pic2_path : null);
                                    $insert_stmt->execute();
                                    $response["message"] = "Employee added successfully!";
                                } else {
                                    $response["error"] = "The requester must have been employed for at least 3 months.";
                                }
                            } else {
                                $response["error"] = "The guarantee's salary must be equal to or greater than the requester's salary.";
                            }
                        } else {
                            $response["error"] = "The guarantee has already guaranteed for two employees.";
                        }
                    } else {
                        $response["error"] = "The guarantee must be a permanent employee.";
                    }
                } else {
                    $response["error"] = "No guarantee found with the given ID.";
                }
            } else {
                $response["error"] = "No employee found with the given ID.";
            }

            // Close statements
            $employee_stmt->close();
            $guarantee_stmt1->close();
            if (isset($guarantee_stmt2)) {
                $guarantee_stmt2->close();
            }
            if (isset($insert_stmt)) {
                $insert_stmt->close();
            }
        } else {
            $response["error"] = "Error uploading ID pictures.";
        }
    } else {
        $response["error"] = "Please fill in all required fields.";
    }
    http_response_code(200);
    exit;
}

$mysqli->close();

// Return the JSON response
echo json_encode($response);
