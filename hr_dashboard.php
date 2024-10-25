<?php
// Database connection
$host = 'localhost'; // Replace with your host
$dbname = 'resl'; // Replace with your database name
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch HR reports
$hr_sql = "SELECT * FROM hr_table";
$result = $conn->query($hr_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
</head>

<body>
    <h1>HR Dashboard</h1>

    <h2>Loan Granting Report</h2>
    <table border="1">
        <tr>
            <th>District Name</th>
            <th>Branch Name</th>
            <th>Number of Employees</th>
            <th>Total Amount Granted</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['district_name']; ?></td>
                <td><?php echo $row['branch_name']; ?></td>
                <td><?php echo $row['number_of_employees']; ?></td>
                <td><?php echo $row['total_amount_granted']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>

<?php
$conn->close();
?>