<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Story</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/createStory.css">
</head>
<body>
   
      <!-- Navbar -->
      <?php require 'dry_code/nav.php'; ?>
    <!-- Navbar End -->

<!-- Create Story Section -->
<section class="create-story">
    <div class="container">
        <h1>Create Your Story</h1>
        <form action="./constants/saveStory.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Story Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="story-body">Story Body:</label>
                <textarea id="story-body" name="story-body" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="story-image">Story Image:</label>
                <input type="file" id="story-image" name="story-image" accept="image/*">
            </div>
            <div class="form-group">
                <label for="story-type">Story Type:</label>
                <select id="story-type" name="story-type" required>
                    <option value="">Select Type</option>
                    <option value="adventure">Adventure</option>
                    <option value="sci-fi">Sci-Fi</option>
                    <option value="horror">Horror</option>
                    <option value="fantasy">Fantasy</option>
                    <option value="mystery">Mystery</option>
                    
                </select>
            </div>
            <div id="sections-container">
                <h2>Branches</h2>
                <div class="section" id="section-1">
                    <div class="form-group">
                        <label>Branches:</label>
                        <div class="branch">
                            <input type="text" name="branch[0][]" placeholder="Option A">
                        </div>
                        <label for="section-body-1">Branch Body:</label>
                        <textarea id="section-body-1" name="section-body[]" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Branches:</label>
                        <div class="branch">
                            <input type="text" name="branch[0][]" placeholder="Option B">
                        </div>
                        <label for="section-body-1">Branch Body:</label>
                        <textarea id="section-body-1" name="section-body[]" rows="5" required></textarea>
                    </div>
                    <button type="button" class="remove-section">Remove Section</button>
                </div>
            </div>
            <button type="button" id="add-section">Add Section</button>
            <div class="form-actions">
                <button type="submit" name="action" value="save-draft" class="btn btn--outline">Save Draft</button>
                <button type="submit" name="action" value="preview" class="btn btn--primary">Preview</button>
                <button type="submit" name="action" value="publish" class="btn btn--publish">Publish</button>
            </div>
        </form>
    </div>
</section>


   <!-- Footer -->
   <?php require 'dry_code/footer.php'; ?>
    <!-- Footer End -->

    <script src="createStory.js"></script>
</body>
</html>
