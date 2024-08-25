<?php
require 'constants/db_config.php'; 


$sql = "
    SELECT s.title, s.image_url, s.story_type, COALESCE(ai.popularity_count, 0) AS popularity_count
    FROM Stories s
    LEFT JOIN author_insights ai ON s.story_id = ai.story_id
    ORDER BY s.created_at DESC
    LIMIT 10
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Storyliner</title>

    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
    <!-- Navbar -->
    <?php require 'dry_code/nav.php'; ?>
    <!-- Navbar End -->

    <!-- Hero Section -->
    <section class="hero">
      <video src="videos/video-1.mp4" autoplay loop muted></video>
      <h1>READ STORIES NOW</h1>
      <p>What are you waiting for?</p>
      <div class="buttons">
        <a href="readStories.php"><button class="btn btn--outline btn--large">
          Start Your Adventure
        </button></a>
        <a href="createStory.php">
          <button class="btn btn--primary btn--large">
            Create Your Story <i class="far fa-play-circle"></i>
          </button>
        </a>
      </div>
    </section>

    <!-- Stories Section -->
    <section class="stories">
      <h2>Check out these EPIC Stories!</h2>
      <div class="card-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              
                if (isset($row['image_url']) && filter_var($row['image_url'], FILTER_VALIDATE_URL)) {
                    $imageSrc = htmlspecialchars($row['image_url']);
                } else {
                   
                    $imageData = base64_encode($row['image_url']);
                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                }

                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="Story Image" />';
                echo '<div class="card-content">';
                echo '<span class="label">' . htmlspecialchars($row['story_type']) . '</span>';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p>Popularity: ' . htmlspecialchars($row['popularity_count']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No stories available at the moment.</p>";
        }
        ?>
      </div>
    </section>

    <!-- Footer Section -->
    <?php require 'dry_code/footer.php'; ?>
    <!-- Footer End -->

    <script src="script.js"></script>
  </body>
</html>

<?php $conn->close(); ?>
