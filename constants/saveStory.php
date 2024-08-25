<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // replace with your database username
$password = " "; // replace with your database password
$dbname = "storyliner"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle file upload
function uploadFile($fileInputName) {
    if (isset($_FILES[$fileInputName])) {
        $file = $_FILES[$fileInputName];
        if ($file['error'] == 0) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($file["name"]);
            move_uploaded_file($file["tmp_name"], $targetFile);
            return $targetFile; // Return the path of the uploaded file
        }
    }
    return null;
}

// Check which button was clicked
if (isset($_POST['action']) && $_POST['action'] == 'publish') {
    // Sanitize and collect form data
    $title = $_POST['title'];
    $storyBody = $_POST['story-body'];
    $storyImage = uploadFile('story-image');
    
    // Insert story into the database
    $stmt = $conn->prepare("INSERT INTO Stories (title, description, image_url, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sss", $title, $storyBody, $storyImage);
    $stmt->execute();
    $storyId = $stmt->insert_id; // Get the ID of the inserted story
    $stmt->close();
    
    // Handle story sections and branches
    foreach ($_POST['section-body'] as $index => $sectionBody) {
        $stmt = $conn->prepare("INSERT INTO Story_Sections (story_id, section_title, section_text, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $sectionTitle = "Section " . ($index + 1);
        $stmt->bind_param("iss", $storyId, $sectionTitle, $sectionBody);
        $stmt->execute();
        $sectionId = $stmt->insert_id; // Get the ID of the inserted section
        $stmt->close();
        
        // Insert branches for each section
        if (isset($_POST['branch'][$index])) {
            foreach ($_POST['branch'][$index] as $branchText) {
                if (!empty($branchText)) {
                    $stmt = $conn->prepare("INSERT INTO Options (section_id, option_text, next_section_id, created_at, updated_at) VALUES (?, ?, NULL, NOW(), NOW())");
                    $stmt->bind_param("is", $sectionId, $branchText);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }

    echo "Story published successfully!";
} else {
    echo "Invalid action.";
}

$conn->close();
?>
