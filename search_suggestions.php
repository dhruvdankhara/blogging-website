<?php
include "connection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    exit(json_encode(["error" => "Unauthorized"]));
}

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query) || strlen($query) < 2) {
    exit(json_encode([]));
}

$search_query_safe = mysqli_real_escape_string($conn, $query);
$suggestions = [];

// Search for posts (limit to 5)
$post_query = "SELECT title, post_id FROM blog_posts 
               WHERE title LIKE '%$search_query_safe%' 
               ORDER BY created_at DESC LIMIT 5";
$post_result = mysqli_query($conn, $post_query);

while ($row = mysqli_fetch_assoc($post_result)) {
    $suggestions[] = [
        'type' => 'post',
        'title' => $row['title'],
        'url' => 'single_post.php?post_id=' . $row['post_id'],
        'icon' => 'bi-file-earmark-text'
    ];
}

// Search for categories (limit to 3)
$category_query = "SELECT name, category_id FROM categories 
                   WHERE name LIKE '%$search_query_safe%' 
                   LIMIT 3";
$category_result = mysqli_query($conn, $category_query);

while ($row = mysqli_fetch_assoc($category_result)) {
    $suggestions[] = [
        'type' => 'category',
        'title' => $row['name'],
        'url' => 'search.php?q=' . urlencode($row['name']),
        'icon' => 'bi-tag'
    ];
}

// Search for authors (limit to 3)
$author_query = "SELECT DISTINCT users.name, users.user_id FROM users 
                 JOIN blog_posts ON users.user_id = blog_posts.user_id
                 WHERE users.name LIKE '%$search_query_safe%' 
                 LIMIT 3";
$author_result = mysqli_query($conn, $author_query);

while ($row = mysqli_fetch_assoc($author_result)) {
    $suggestions[] = [
        'type' => 'author',
        'title' => $row['name'],
        'url' => 'search.php?q=' . urlencode($row['name']),
        'icon' => 'bi-person'
    ];
}

header('Content-Type: application/json');
echo json_encode(array_slice($suggestions, 0, 8)); // Limit total suggestions to 8
?>