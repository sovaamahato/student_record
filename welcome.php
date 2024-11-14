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
if (isset($_POST['updateStudent'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $amount = $_POST['amount'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $roll_no = $_POST['roll_no'];

    // Update the student in the database
    $sql = "UPDATE students SET name='$name', phone_number='$phone_number', address='$address', amount='$amount', class='$class', section='$section', roll_no='$roll_no' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student details updated successfully!'); window.location.href = 'welcome.php';</script>";
    } else {
        echo "<script>alert('Error updating student: " . $conn->error . "');</script>";
    }
}

$conn->close();
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

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #e74c3c;
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


<!-- Print Bill Modal -->
<div id="printBillModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('printBillModal').style.display='none'">&times;</span>
        <h3>Print Bill</h3>
        <div id="billDetails"></div>
        <button class="print-btn" onclick="window.print()">Print Bill</button>
        <!-- Cancel Button -->
        <button class="btn" onclick="document.getElementById('printBillModal').style.display='none'">Cancel</button>
    </div>
</div>
<!-- Edit Student Form (Modal) -->
<div id="editStudentForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editStudentForm').style.display='none'">&times;</span>
        <h3>Edit Student</h3>
        <form action="welcome.php" method="POST">
            <input type="hidden" id="editId" name="id"><br>
            <input type="text" id="editName" name="name" placeholder="Name" required><br>
            <input type="text" id="editPhone" name="phone_number" placeholder="Phone Number" required><br>
            <input type="text" id="editAddress" name="address" placeholder="Address" required><br>
            <input type="number" id="editAmount" name="amount" placeholder="Amount" required><br>
            <input type="text" id="editClass" name="class" placeholder="Class" required><br>
            <input type="text" id="editSection" name="section" placeholder="Section" required><br>
            <input type="number" id="editRollNo" name="roll_no" placeholder="Roll No." required><br>
            <button type="submit" name="updateStudent">Save Changes</button>
        </form>
        <button class="btn" type="button" onclick="document.getElementById('editStudentForm').style.display='none'">Cancel</button>
    </div>
</div>


<script>
    // Open the Edit Student modal and pre-fill values
    function openEditModal(studentId) {
    document.getElementById('editStudentForm').style.display = 'block';

    // Fetch student data using AJAX (or PHP if preferred)
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_student_details.php?id=' + studentId, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const student = JSON.parse(xhr.responseText);
            // Populate the form with student data
            document.getElementById('editId').value = student.id;
            document.getElementById('editName').value = student.name;
            document.getElementById('editPhone').value = student.phone_number;
            document.getElementById('editAddress').value = student.address;
            document.getElementById('editAmount').value = student.amount;
            document.getElementById('editClass').value = student.class;
            document.getElementById('editSection').value = student.section;
            document.getElementById('editRollNo').value = student.roll_no;
        } else {
            alert("Failed to fetch student details.");
        }
    };

    xhr.send();
}



    // Print Bill
    function printBill(studentId) {
        // Send AJAX request to fetch student details
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_student_details.php?id=' + studentId, true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Get student details from the response
                const student = JSON.parse(xhr.responseText);

                // Prepare the bill details with dynamic data from the database
                const billDetails = `
                    <p><strong>Student ID:</strong> ${student.id}</p>
                    <p><strong>Name:</strong> ${student.name}</p>
                    <p><strong>Phone Number:</strong> ${student.phone_number}</p>
                    <p><strong>Address:</strong> ${student.address}</p>
                    <p><strong>Amount:</strong> ₹${student.amount}</p>
                    <p><strong>Class:</strong> ${student.class}</p>
                    <p><strong>Section:</strong> ${student.section}</p>
                    <p><strong>Roll No:</strong> ${student.roll_no}</p>
                `;

                // Display the bill details in the modal
                document.getElementById('billDetails').innerHTML = billDetails;
                document.getElementById('printBillModal').style.display = 'block';
            } else {
                alert("Failed to load student details.");
            }
        };

        xhr.send();
    

    }
</script>

</body>
</html>
