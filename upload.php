<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost"; // Change this if your database is hosted elsewhere
    $username = "your_username"; // Change this to your MySQL username
    $password = "your_password"; // Change this to your MySQL password
    $dbname = "your_database"; // Change this to your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO properties (title, description, price, location, images) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $title, $description, $price, $location, $images);

    // Set parameters
    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = floatval($_POST["price"]); // Assuming price is stored as a float
    $location = $_POST["location"];

    // Array to store image paths
    $imagePaths = array();

    // Loop through each uploaded file
    foreach ($_FILES["images"]["name"] as $key => $name) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($name);
        move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file);
        $imagePaths[] = $target_file;
    }

    // Combine image paths into a single string
    $images = implode(",", $imagePaths);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Property uploaded successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
