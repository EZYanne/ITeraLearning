<?php
session_start();

// Include database configuration
require_once 'config.php'; // Ensure this path is correct

// Check if the user is logged in and is a student
if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'Student') {
    // If not logged in or not a student, redirect to login page
    header("Location: login_form.php");
    exit;
}

// Define the user's id
$student_id = $_SESSION['id'];

// Prepare a SQL statement to fetch student-specific data from the user_form table
$sql = "SELECT first_name, last_name, email FROM user_form WHERE id = ? AND user_type = 'Student'";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param("i", $student_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Fetch the result
        $result = $stmt->get_result();

        // Check if a student record was found
        if ($result->num_rows == 1) {
            // Fetch the student data
            $student = $result->fetch_assoc();
        } else {
            // No student found with this ID
            echo "No student found.";
            exit; // Stop script execution
        }
    } else {
        // Error executing the statement
        echo "Error executing query: " . htmlspecialchars($conn->error);
        exit; // Stop script execution
    }

    // Close the statement
    $stmt->close();
} else {
    // If the query preparation failed, output the error
    echo "Error preparing statement: " . htmlspecialchars($conn->error);
    exit; // Stop script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link href="img/logo-ptc.ico" rel="icon">

    <meta charset="UTF-8">
    <title>Student Dashboard - Pateros Technological College</title>
    <link rel="stylesheet" href="css/studentstyle.css"> <!-- Link to the new CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Link to Font Awesome -->
</head>
<body>
    <div class="header">
        <div class="header-content">
            <i class="fas fa-bars" id="overlayTrigger" style="color: white; font-size: 2rem; margin-right: 15px; cursor: pointer;"></i> <!-- Trigger icon -->
            <img src="img/logo-ptc.png" alt="ITeraLearning Logo" class="logo">
            <h5>IT</h5><h6>eraLearning</h6>
        </div>
    </div>

    <div class="container">
        <h1>Welcome, Student <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>!</h1>
        <h2>Course Structure</h2>

        <div class="container">
    <div class="row">

<!-- Category Start -->
<div class="category-container">
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="cat-item position-relative overflow-hidden rounded mb-2">
            <img class="img-fluid" src="img/1ST.jpg" alt="1st Year College">
            <a class="cat-overlay text-white text-decoration-none" href="#">
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="cat-item position-relative overflow-hidden rounded mb-2">
            <img class="img-fluid" src="img/2ND.jpg" alt="2nd Year College">
            <a class="cat-overlay text-white text-decoration-none" href="#">
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="cat-item position-relative overflow-hidden rounded mb-2">
            <img class="img-fluid" src="img/3RD.jpg" alt="3rd Year College">
            <a class="cat-overlay text-white text-decoration-none" href="#">
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="cat-item position-relative overflow-hidden rounded mb-2">
            <img class="img-fluid" src="img/4TH.jpg" alt="4th Year College">
            <a class="cat-overlay text-white text-decoration-none" href="#">
            </a>
        </div>
    </div>
<!-- Category End -->


<!-- Overlay -->
<div class="overlay" id="overlay">
    <div class="overlay-content">
        <span class="close-btn" id="closeOverlay">&times;</span>
        <h2>BSIT Courses Structure</h2>
        <div class="year">
            <h3>1st Year:</h3>
            <div class="semester">
                <h4>1st Semester:</h4>
                <ul>
                    <li><a href="#">CC 101</a></li>
                    <li><a href="#">CC 102</a></li>
                    <li><a href="#">OP 1</a></li>
                </ul>
            </div>
            <div class="semester">
                <h4>2nd Semester:</h4>
                <ul>
                    <li><a href="#">CC 103</a></li>
                    <li><a href="#">OP 2</a></li>
                </ul>
            </div>
        </div>
        <div class="year">
            <h3>2nd Year:</h3>
            <div class="semester">
                <h4>1st Semester:</h4>
                <ul>
                    <li><a href="#">CC 104</a></li>
                    <li><a href="#">DLD 1</a></li>
                    <li><a href="#">OM 101</a></li>
                    <li><a href="#">OP 3</a></li>
                </ul>
            </div>
            <div class="semester">
                <h4>2nd Semester:</h4>
                <ul>
                    <li><a href="#">CC 106</a></li>
                    <li><a href="#">CC 105</a></li>
                    <li><a href="#">HC 101</a></li>
                    <li><a href="#">OS</a></li>
                    <li><a href="#">OP 4</a></li>
                    <li><a href="#">CHS 101</a></li>
                </ul>
            </div>
        </div>
        <div class="year">
            <h3>3rd Year:</h3>
            <div class="semester">
                <h4>1st Semester:</h4>
                <ul>
                    <li><a href="#">WS 101</a></li>
                    <li><a href="#">SAD 1</a></li>
                    <li><a href="#">DBA 1</a></li>
                    <li><a href="#">MS 102</a></li>
                </ul>
            </div>
            <div class="semester">
                <h4>2nd Semester:</h4>
                <ul>
                    <li><a href="#">WS 102</a></li>
                    <li><a href="#">NET 102</a></li>
                    <li><a href="#">CAP 101</a></li>
                </ul>
            </div>
        </div>
        <div class="year">
            <h3>4th Year:</h3>
            <div class="semester">
                <h4>1st Semester:</h4>
                <ul>
                    <li><a href="#">IPT 101</a></li>
                    <li><a href="#">CAP 102</a></li>
                    <li><a href="#">FDW 1</a></li>
                    <li><a href="#">SAM 41</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <script>
        // JavaScript to handle the overlay
        const overlay = document.getElementById('overlay');
        const overlayTrigger = document.getElementById('overlayTrigger');
        const closeOverlay = document.getElementById('closeOverlay');

        overlayTrigger.addEventListener('click', function() {
            overlay.style.display = 'block';
        });

        closeOverlay.addEventListener('click', function() {
            overlay.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    </script>
</body>
</html>


<?php
// Close the connection
$conn->close();
?>
