<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Fetch student data for the logged-in user
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

// Check if there are any students in the table
$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();

// Handle Edit Student Submission
if (isset($_POST['editStudent'])) {
    include 'config.php';

    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $amount = $_POST['amount'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $roll_no = $_POST['roll_no'];

    $sql = "UPDATE students SET name=?, phone_number=?, address=?, amount=?, class=?, section=?, roll_no=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $phone_number, $address, $amount, $class, $section, $roll_no, $id);

    if ($stmt->execute()) {
        header("Location: welcome.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #2c3e50;
            padding: 15px;
            text-align: center;
            color: #fff;
            font-size: 1.5em;
        }

        /* Welcome Message */
        .welcome-message {
            text-align: center;
            margin-top: 30px;
            color: #2c3e50;
        }

        .student-table {
            margin: 50px auto;
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
        }

        .student-table th, .student-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .student-table th {
            background-color: #3498db;
            color: white;
        }

        .student-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .student-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-align: center;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Add Student Form (Modal) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
        }

        .modal input[type="text"],
        .modal input[type="number"],
        .modal button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .modal button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            font-size: 1.1em;
        }

        .modal button:hover {
            background-color: #2980b9;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        /* Print Bill Modal */
        #printBillModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        #printBillModal .modal-content {
            width: 500px;
            padding: 30px;
            background-color: white;
            border-radius: 5px;
            text-align: center;
        }

        .print-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
        }
        
        .print-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    Welcome, <?php echo $_SESSION['username']; ?>!
</div>

<!-- Welcome Message -->
<div class="welcome-message">
    <h2>Student Management</h2>
    <button class="btn" onclick="document.getElementById('addStudentForm').style.display='block'">Add New Student</button>
</div>

<!-- Student List -->
<h3 style="text-align:center;">Student List</h3>
<table class="student-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>Amount</th>
        <th>Class</th>
        <th>Section</th>
        <th>Roll No.</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($students as $student): ?>
    <tr>
        <td><?php echo $student['id']; ?></td>
        <td><?php echo $student['name']; ?></td>
        <td><?php echo $student['phone_number']; ?></td>
        <td><?php echo $student['address']; ?></td>
        <td><?php echo $student['amount']; ?></td>
        <td><?php echo $student['class']; ?></td>
        <td><?php echo $student['section']; ?></td>
        <td><?php echo $student['roll_no']; ?></td>
        <td>
            <!-- Edit Button -->
            <button class="btn" onclick="openEditModal(<?php echo $student['id']; ?>)">Edit</button>
            <!-- Print Bill Button -->
            <button class="btn" onclick="printBill(<?php echo $student['id']; ?>)">Print Bill</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Add Student Form (Modal) -->
<div id="addStudentForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addStudentForm').style.display='none'">&times;</span>
        <h3>Add New Student</h3>
        <form action="welcome.php" method="POST">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="text" name="phone_number" placeholder="Phone Number" required><br>
            <input type="text" name="address" placeholder="Address" required><br>
            <input type="number" name="amount" placeholder="Amount" required><br>
            <input type="text" name="class" placeholder="Class" required><br>
            <input type="text" name="section" placeholder="Section" required><br>
            <input type="number" name="roll_no" placeholder="Roll No." required><br>
            <button type="submit" name="addStudent">Add Student</button>
        </form>
        <button class="btn" type="button" onclick="document.getElementById('addStudentForm').style.display='none'">Cancel</button>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editStudentForm').style.display='none'">&times;</span>
        <h3>Edit Student</h3>
        <form action="welcome.php" method="POST">
            <input type="hidden" name="id" id="editId">
            <input type="text" name="name" id="editName" placeholder="Name" required><br>
            <input type="text" name="phone_number" id="editPhone" placeholder="Phone Number" required><br>
            <input type="text" name="address" id="editAddress" placeholder="Address" required><br>
            <input type="number" name="amount" id="editAmount" placeholder="Amount" required><br>
            <input type="text" name="class" id="editClass" placeholder="Class" required><br>
            <input type="text" name="section" id="editSection" placeholder="Section" required><br>
            <input type="number" name="roll_no" id="editRollNo" placeholder="Roll No." required><br>
            <button type="submit" name="editStudent">Save Changes</button>
        </form>
        <button class="btn" type="button" onclick="document.getElementById('editStudentForm').style.display='none'">Cancel</button>
    </div>
</div>

<!-- Print Bill Modal -->
<div id="printBillModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('printBillModal').style.display='none'">&times;</span>
        <h3>Print Bill</h3>
        <p id="billDetails"></p>
        <button class="print-btn" onclick="window.print()">Print Bill</button>
    </div>
</div>

<script>
    // Open the Edit Student modal and pre-fill values
    function openEditModal(studentId) {
        document.getElementById('editStudentForm').style.display = 'block';

        // Fetch student data from server using studentId (can be done via AJAX)
        // Here I'm just setting mock data for demonstration
        document.getElementById('editId').value = studentId;
        document.getElementById('editName').value = "John Doe";
        document.getElementById('editPhone').value = "1234567890";
        document.getElementById('editAddress').value = "Some Address";
        document.getElementById('editAmount').value = "5000";
        document.getElementById('editClass').value = "10th";
        document.getElementById('editSection').value = "A";
        document.getElementById('editRollNo').value = "12";
    }

    // Print Bill
    function printBill(studentId) {
        document.getElementById('printBillModal').style.display = 'block';
        
        // Fetch student details for the selected student (can be done via AJAX)
        // Here, mock data is being displayed
        var studentDetails = `
            Student ID: ${studentId}<br>
            Name: John Doe<br>
            Amount: ₹5000<br>
            Phone Number: 1234567890<br>
            Address: Some Address<br>
        `;
        
        document.getElementById('billDetails').innerHTML = studentDetails;
    }
</script>

</body>
</html>
