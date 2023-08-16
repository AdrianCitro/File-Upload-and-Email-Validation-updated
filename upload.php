<?php
$servername = "your_host";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $role = $_POST["role"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $validExtensions = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $validExtensions)) {
        die("Restricted Area! JPG, JPEG, and PNG files ONLY!");
    }

    if ($role === "A") {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO uploaded_files (email, file_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $targetFile);

            if ($stmt->execute()) {
                echo "File uploaded and data stored successfully.";
            } else {
                echo "Error broo..";
            }

            $stmt->close();
        } else {
            echo "Error uploading file.. :(";
        }
    } elseif ($role === "B") {
        echo "You are not authorized.";
    } else {
        echo "Invalid role selection.";
    }
}
?>
