<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include_once 'config.php';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$success = false;
$selectedArticle = null;

// Fetch articles and categories
$articles = $conn->query("SELECT id, title FROM article");
$categories = $conn->query("SELECT id, name FROM category");

// Initialize $articleData with DB column keys
$articleData = [
  'title' => '',
  'keyword' => '',
  'content' => '',
  'image_url' => '',
  'category_id' => '',
  'archived' => 0
];

// Load article data on dropdown change (if not submitting save form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article-id']) && $_POST['article-id'] !== 'new' && !isset($_POST['title'])) {
  $selectedArticle = intval($_POST['article-id']);
  $stmt = $conn->prepare("SELECT title, keyword, content, image_url, category_id, archived FROM article WHERE id = ?");
  $stmt->bind_param("i", $selectedArticle);
  $stmt->execute();
  $stmt->bind_result($title, $keyword, $content, $image_url, $category_id, $archived);
  if ($stmt->fetch()) {
    $articleData = compact('title', 'keyword', 'content', 'image_url', 'category_id', 'archived');
  }
  $stmt->close();
}

// On save or delete submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
  $selectedArticle = $_POST['id'] ?? null;

  $articleData = [
    'title' => trim($_POST['title'] ?? ''),
    'keyword' => trim($_POST['keyword'] ?? ''),
    'content' => trim($_POST['content'] ?? ''),
    'category_id' => $_POST['category'] ?? '',
    'archived' => isset($_POST['archive']) ? 1 : 0,
    'image_url' => '' // will be set later
  ];

  // Validate required fields
  if (!$articleData['title']) $errors[] = 'Naslov je obavezan.';
  if (!$articleData['keyword']) $errors[] = 'Ključna riječ je obavezna.';
  if (!$articleData['content']) $errors[] = 'Sadržaj je obavezan.';
  if (!$articleData['category_id']) $errors[] = 'Kategorija je obavezna.';

  // Delete logic
  if (isset($_POST['delete']) && $selectedArticle) {
    $stmt = $conn->prepare("SELECT image_url FROM article WHERE id = ?");
    $stmt->bind_param("i", $selectedArticle);
    $stmt->execute();
    $stmt->bind_result($imageFile);
    $stmt->fetch();
    $stmt->close();

    // Delete image and thumbnail
    if ($imageFile) {
        $mainPath = __DIR__ . "/img/" . $imageFile;
        $thumbPath = __DIR__ . "/img/thumbnails/" . $imageFile;
        if (file_exists($mainPath)) unlink($mainPath);
        if (file_exists($thumbPath)) unlink($thumbPath);
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
    $stmt->bind_param("i", $selectedArticle);
    if ($stmt->execute()) {
      $success = "Članak je uspješno obrisan.";
      $selectedArticle = null;
      $articleData = [
        'title' => '',
        'keyword' => '',
        'content' => '',
        'image_url' => '',
        'category_id' => '',
        'archived' => 0
      ];
    } else {
      $errors[] = "Greška prilikom brisanja članka.";
    }
    $stmt->close();
  } else {
    // Image upload handling here (same as before)
    $imageFileName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $fileTmp = $_FILES['image']['tmp_name'];
      $fileName = basename($_FILES['image']['name']);
      $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      // Validate image extension
      $allowed = ['jpg', 'jpeg', 'png'];
      if (!in_array($ext, $allowed)) {
        $errors[] = "Dozvoljeni su samo JPG i PNG formati slika.";
      } else {
        // Validate image dimensions
        [$width, $height] = getimagesize($fileTmp);
        if ($width < 600 || $height < 400) {
          $errors[] = "Slika mora biti najmanje 600x400 piksela.";
        } else {
          // Generate unique filename
          $imageFileName = uniqid('img_', true) . '.' . $ext;

          // Move original to img/
          $targetPath = __DIR__ . "/img/" . $imageFileName;
          if (!move_uploaded_file($fileTmp, $targetPath)) {
            $errors[] = "Greška pri spremanju slike.";
          } else {
            // Create thumbnail (300x200)
            $thumbPath = __DIR__ . "/img/thumbnails/" . $imageFileName;
            $thumbWidth = 300;
            $thumbHeight = 200;

            // Create source image
            switch ($ext) {
              case 'jpg':
              case 'jpeg':
                $sourceImage = imagecreatefromjpeg($targetPath);
                break;
              case 'png':
                $sourceImage = imagecreatefrompng($targetPath);
                break;
            }

            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);

            // Preserve transparency for PNG
            if ($ext === 'png') {
              imagealphablending($thumbnail, false);
              imagesavealpha($thumbnail, true);
            }

            // Resize
            imagecopyresampled(
              $thumbnail,
              $sourceImage,
              0,
              0,
              0,
              0,
              $thumbWidth,
              $thumbHeight,
              $width,
              $height
            );

            // Save thumbnail
            switch ($ext) {
              case 'jpg':
              case 'jpeg':
                imagejpeg($thumbnail, $thumbPath, 90);
                break;
              case 'png':
                imagepng($thumbnail, $thumbPath);
                break;
            }

            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);
          }
        }
      }
    }
    else {
    // Only require image on new article
    if (!$selectedArticle) {
        $errors[] = "Slika je obavezna za novi članak.";
    }
}

    // If image uploaded, set image_url, else keep old if exists
    if ($imageFileName) {
      $articleData['image_url'] = $imageFileName;
    } elseif ($selectedArticle) {
      $stmt = $conn->prepare("SELECT image_url FROM article WHERE id = ?");
      $stmt->bind_param("i", $selectedArticle);
      $stmt->execute();
      $stmt->bind_result($oldImage);
      if ($stmt->fetch()) {
        $articleData['image_url'] = $oldImage;
      }
      $stmt->close();
    }

    // Save to DB if no errors
    if (!$errors) {
      if ($selectedArticle) {
        $stmt = $conn->prepare("UPDATE article SET title=?, keyword=?, content=?, image_url=?, author_id=?, archived=?, category_id=? WHERE id=?");
        $stmt->bind_param(
          "ssssiiii",
          $articleData['title'],
          $articleData['keyword'],
          $articleData['content'],
          $articleData['image_url'],
          $_SESSION['user_id'],
          $articleData['archived'],
          $articleData['category_id'],
          $selectedArticle
        );
      } else {
        $stmt = $conn->prepare("INSERT INTO article (title, keyword, content, image_url, author_id, archived, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
          "ssssiis",
          $articleData['title'],
          $articleData['keyword'],
          $articleData['content'],
          $articleData['image_url'],
          $_SESSION['user_id'],
          $articleData['archived'],
          $articleData['category_id']
        );
      }

      if ($stmt->execute()) {
        $success = "Članak je uspješno spremljen.";
        if (!$selectedArticle) {
          $selectedArticle = $stmt->insert_id;
        }
      } else {
        $errors[] = "Greška prilikom spremanja članka: " . $stmt->error;
      }
      $articles = $conn->query("SELECT id, title FROM article");
      $stmt->close();
    }
  }
}
