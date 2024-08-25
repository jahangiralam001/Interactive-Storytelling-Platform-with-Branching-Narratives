<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "storyliner"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 

function uploadFileToDatabase($fileInputName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
        $fileData = file_get_contents($_FILES[$fileInputName]['tmp_name']); 
        return $fileData; 
    }
    return null;
}


if (isset($_POST['action']) && $_POST['action'] == 'publish') {
    
    $title = $_POST['title'];
    $storyBody = $_POST['story-body'];
    $storyImage = uploadFileToDatabase('story-image'); 
    $storyType = $_POST['story-type'];
    
   
    $stmt = $conn->prepare("INSERT INTO Stories (title, description, image_url, story_type, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssss", $title, $storyBody, $storyImage, $storyType);
    $stmt->send_long_data(2, $storyImage); 
    $stmt->execute();
    $storyId = $stmt->insert_id; 
    $stmt->close();
    
   
    if (isset($_POST['section-body'])) {
        $sectionCount = count($_POST['section-body']);
        for ($i = 0; $i < $sectionCount; $i++) {
            $sectionBody = $_POST['section-body'][$i];
            
            
            $stmt = $conn->prepare("INSERT INTO Story_Sections (story_id, section_title, section_text, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $sectionTitle = "Section " . ($i + 1);
            $stmt->bind_param("iss", $storyId, $sectionTitle, $sectionBody);
            $stmt->execute();
            $sectionId = $stmt->insert_id; 
            $stmt->close();
            
       
            if (isset($_POST['branch'][$i])) {
                foreach ($_POST['branch'][$i] as $key => $branchOption) {
                  
                    $branchBody = isset($_POST['branch_body'][$i][$key]) ? $_POST['branch_body'][$i][$key] : '';
                    if (!empty($branchOption)) {
                        $stmt = $conn->prepare("INSERT INTO Branches (section_id, branch_option, branch_body, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                        $stmt->bind_param("iss", $sectionId, $branchOption, $branchBody);
                        $stmt->execute();
                        $stmt->close();
                    }
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
