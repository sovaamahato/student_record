<?php
include 'config.php';

// Get student ID from URL parameter
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Fetch student details from the database
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    // Return the student data as JSON
    echo json_encode($student);
}

$conn->close();
