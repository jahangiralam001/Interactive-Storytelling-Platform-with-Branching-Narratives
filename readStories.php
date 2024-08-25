<?php
require 'constants/db_config.php'; 


$topStoriesSql = "
    SELECT s.story_id, s.title, s.image_url, s.story_type, COALESCE(ai.popularity_count, 0) AS popularity_count
    FROM Stories s
    LEFT JOIN author_insights ai ON s.story_id = ai.story_id
    ORDER BY s.created_at DESC
    LIMIT 10
";
$topStoriesResult = $conn->query($topStoriesSql);


$storyId = isset($_GET['story_id']) ? intval($_GET['story_id']) : null;


if ($storyId) {
    $storySql = "
        SELECT s.title, s.description, s.image_url, s.story_type, COALESCE(ai.popularity_count, 0) AS popularity_count
        FROM Stories s
        LEFT JOIN author_insights ai ON s.story_id = ai.story_id
        WHERE s.story_id = ?
    ";
    $stmt = $conn->prepare($storySql);
    $stmt->bind_param("i", $storyId);
    $stmt->execute();
    $storyResult = $stmt->get_result();
} else {

    $storySql = "
        SELECT s.title, s.description, s.image_url, s.story_type, COALESCE(ai.popularity_count, 0) AS popularity_count
        FROM Stories s
        LEFT JOIN author_insights ai ON s.story_id = ai.story_id
        ORDER BY s.created_at DESC
        LIMIT 1
    ";
    $storyResult = $conn->query($storySql);
}

$story = $storyResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Storyliner</title>
    <link rel="stylesheet" href="css/readStories.css" />
</head>
<body>
    <!-- Navbar -->
    <?php require 'dry_code/nav.php'; ?>
    <!-- Navbar End -->

    <!-- Story Section -->
    <section class="story">
        <div class="story-container">
            <?php if ($story): ?>
                <h1 class="story-title"><?php echo htmlspecialchars($story['title']); ?></h1>
                <p class="story-body"><?php echo nl2br(htmlspecialchars($story['description'])); ?></p>
                <div class="options">
                   
                    <button class="btn btn--outline btn--large">
                        Take the left path
                        <span class="view-count">Views: <?php echo htmlspecialchars($story['popularity_count']); ?></span>
                    </button>
                    <button class="btn btn--primary btn--large">
                        Take the right path
                        <span class="view-count">Views: <?php echo htmlspecialchars($story['popularity_count']); ?></span>
                    </button>
                </div>
            <?php else: ?>
                <p>No story found.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Top Stories -->
    <section class="stories">
        <h2>Check out these EPIC Stories!</h2>
        <div class="card-container">
            <?php
            if ($topStoriesResult->num_rows > 0) {
                while ($row = $topStoriesResult->fetch_assoc()) {
                   
                    $imageData = base64_encode($row['image_url']);
                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;

                    echo '<div class="card">';
                    echo '<img src="' . $imageSrc . '" alt="Story Image" />';
                    echo '<div class="card-content">';
                    echo '<span class="label">' . htmlspecialchars($row['story_type']) . '</span>';
                    echo '<h3><a href="?story_id=' . htmlspecialchars($row['story_id']) . '">' . htmlspecialchars($row['title']) . '</a></h3>';
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

    <!-- Footer -->
    <?php require 'dry_code/footer.php'; ?>
    <!-- Footer End -->

    <script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
