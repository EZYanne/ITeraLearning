<?php
session_start();

// Include database configuration
require_once 'config.php'; // Ensure this path is correct

// Check if the user is logged in and is a professor
if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'Professor') {
    // If not logged in or not a professor, redirect to login page
    header("Location: login_form.php");
    exit;
}

// Define the user's id
$professor_id = $_SESSION['id'];

// Prepare a SQL statement to fetch professor-specific data from the user_form table
$sql = "SELECT first_name, last_name, email FROM user_form WHERE id = ? AND user_type = 'Professor'";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param("i", $professor_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Fetch the result
        $result = $stmt->get_result();

        // Check if a professor record was found
        if ($result->num_rows == 1) {
            // Fetch the professor data
            $professor = $result->fetch_assoc();
        } else {
            // No professor found with this ID
            echo "No professor found.";
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

// Fetch professor's courses
$professor_email = $_SESSION['email'];
$sql_courses = "SELECT course_name FROM courses WHERE professor_email = ?"; 

if ($stmt_courses = $conn->prepare($sql_courses)) {
    // Bind parameters for the courses statement
    $stmt_courses->bind_param("s", $professor_email);
    
    // Execute the courses statement
    if ($stmt_courses->execute()) {
        $result_courses = $stmt_courses->get_result();
    } else {
        // Error executing the courses statement
        echo "Error executing course query: " . htmlspecialchars($conn->error);
        exit; // Stop script execution
    }
} else {
    // If the query preparation failed, output the error
    echo "Error preparing course statement: " . htmlspecialchars($conn->error);
    exit; // Stop script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Dashboard - Pateros Technological College</title>
    <link rel="stylesheet" href="css/professorstyle.css"> <!-- Link to the new CSS file -->
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="img/logo-ptc.png" alt="Logo" class="logo"> <!-- Optional logo -->
            <h1>ITeraLearning</h1>
        </div>
    </div>

    <div class="container">
        <h1>Welcome, Professor <?php echo htmlspecialchars($professor['first_name'] . ' ' . $professor['last_name']); ?>!</h1>
        <h2>Your Courses</h2>

        <div class="year">
            <h3>Assigned Courses:</h3>
            <ul>
                <?php
                // Assuming $result_courses contains the courses for the professor
                if ($result_courses->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result_courses->fetch_assoc()) {
                        echo "<li><a href='#'>" . htmlspecialchars($row['course_name']) . "</a></li>";
                    }
                } else {
                    echo "<li>No courses found.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="year">
            <h3>Student Management</h3>
            <div class="semester">
                <ul>
                    <li><a href="student_grades.php">Manage Student Grades</a></li>
                    <li><a href="attendance.php">Attendance</a></li>
                </ul>
            </div>
        </div>

        <div class="year">
            <h3>Upload Your Files</h3>
            <form id="upload-form">
                <input type="file" id="fileInput" accept=".doc,.docx,.ppt,.pptx,.xls,.xlsx,.pdf,.txt,.png,.jpg,.jpeg" multiple>
                <button type="button" id="uploadBtn">Upload</button>
            </form>

            <h3>Uploaded Files:</h3>
            <ul id="fileList"></ul>

            <h3>Preview:</h3>
            <div id="filePreview">
                <p>No file preview available.</p>
            </div>

            <!-- Share link input -->
            <div>
                <input type="text" id="shareLinkInput" placeholder="Paste your Google Drive share link here">
                <button id="shareLinkBtn">Share Link</button>
            </div>
        </div>

        <a class="button" href="logout.php">Log Out</a>
    </div>

    <script>
    document.getElementById('uploadBtn').addEventListener('click', function(event) {
        const files = document.getElementById('fileInput').files;
        const fileList = document.getElementById('fileList');
        const filePreview = document.getElementById('filePreview');

        fileList.innerHTML = '';  
        filePreview.innerHTML = '<p>No file preview available.</p>';

        if (files.length === 0) {
            alert("No files selected");
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const li = document.createElement('li');

            const downloadLink = document.createElement('a');
            downloadLink.textContent = "Download " + file.name;
            downloadLink.href = URL.createObjectURL(file);
            downloadLink.download = file.name;
            downloadLink.style.marginRight = "10px";

            li.appendChild(downloadLink);
            fileList.appendChild(li);

            const fileUrl = URL.createObjectURL(file);
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (fileExtension === 'pdf') {
                filePreview.innerHTML = `<iframe src="${fileUrl}" width="100%" height="500px"></iframe>`;
            } else if (['png', 'jpg', 'jpeg'].includes(fileExtension)) {
                filePreview.innerHTML = `<img src="${fileUrl}" alt="${file.name}" width="100%" />`;
            } else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileExtension)) {
                alert("To preview this document, please upload the file to Google Drive.");
                filePreview.innerHTML = `<p>Preview not available. Please upload to Google Drive.</p>`;
            } else {
                filePreview.innerHTML = `<p>Preview not available. <a href="${fileUrl}" target="_blank">Download</a></p>`;
            }
        }
    });

    document.getElementById('shareLinkBtn').addEventListener('click', function() {
        const shareLink = document.getElementById('shareLinkInput').value;
        const filePreview = document.getElementById('filePreview');
        const fileList = document.getElementById('fileList');
        
        if (shareLink) {
            fileList.innerHTML = '';
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = shareLink;
            link.textContent = "Shared Link: " + shareLink;
            link.target = "_blank";
            li.appendChild(link);
            fileList.appendChild(li);

            filePreview.innerHTML = `<iframe src="${shareLink.replace(/\/edit.*/, '/preview')}" width="100%" height="500px"></iframe>`;

            alert("Shareable link added: " + shareLink);
            document.getElementById('shareLinkInput').value = '';
        } else {
            alert("Please enter a valid shareable link.");
        }
    });
    </script>
</body>
</html>



<?php
// Close the statements and connection
if (isset($stmt_courses)) {
    $stmt_courses->close();
}
$conn->close();
?>
