<?php
function renderArticlesByCategory($categoryId, $limit = 4)
{
    $conn = new mysqli("localhost", "root", "", "debate_news");
    if ($conn->connect_error) {
        echo "<p>Error connecting to database.</p>";
        return;
    }

    $stmt = $conn->prepare("
            SELECT a.id, a.title, a.keyword, a.image_url, a.submitted, u.first_name, u.last_name
            FROM article a
            JOIN user u ON a.author_id = u.id
            WHERE a.category_id = ?
            ORDER BY a.submitted DESC
            LIMIT ?
        ");
    $stmt->bind_param("ii", $categoryId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect all articles into an array
    $articles = [];
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }

    // Shuffle the array in PHP
    shuffle($articles);

    // Display articles
    foreach ($articles as $row) {
        $title = htmlspecialchars($row['title']);
        $keyword = htmlspecialchars($row['keyword']);
        $image = htmlspecialchars($row['image_url']);
        $author = strtoupper("POR {$row['first_name']} {$row['last_name']}");
        $articleLink = "article.php?id=" . $row['id'];
        $title = htmlspecialchars($row['title']);
        $keyword = htmlspecialchars($row['keyword']);
        $image = htmlspecialchars($row['image_url']);
        $author = strtoupper("POR {$row['first_name']} {$row['last_name']}");
        $articleLink = "article.php?id=" . $row['id'];

        // Format time difference
        $submitted = new DateTime($row['submitted']);
        $now = new DateTime();
        $diff = $now->getTimestamp() - $submitted->getTimestamp();

        if ($diff < 3600) {
            $timeAgo = "HACE " . floor($diff / 60) . " MINUTOS";
        } elseif ($diff < 86400) {
            $timeAgo = "HACE " . floor($diff / 3600) . " HORAS";
        } else {
            $timeAgo = "HACE " . floor($diff / 86400) . " DÍAS";
        }

        echo <<<HTML
            
            <article>
                <a href="{$articleLink}" style="text-decoration: none; color: inherit;">
                    <div class="thumbnail">
                        <img src="img/thumbnails/{$image}" alt="thumbnail">
                    </div>
                    <div class="article-info">
                        <span class="topic-label">{$keyword}</span>
                        <h3 class="headline">{$title}</h3>
                        <div>
                            <span class="author">{$author}</span>
                            <time class="timestamp"> - {$timeAgo}</time>
                        </div>
                    </div>
                </a>
            </article>
           
        HTML;
    }

    $stmt->close();
    $conn->close();
}
