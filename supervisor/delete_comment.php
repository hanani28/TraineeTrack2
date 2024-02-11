<?php
// delete_comment.php

include("../connection.php");

// Check if the comment ID is provided in the query string
if (isset($_GET['id'])) {
    $commentId = $_GET['id'];

    // Perform the deletion
    $deleteQuery = $database->query("DELETE FROM comments WHERE comment_id = $commentId");

    if ($deleteQuery) {
        echo "Comment deleted successfully";
    } else {
        echo "Error deleting comment: " . $database->error;
    }
} else {
    echo "Comment ID not provided";
}

// Redirect back to the page with the comment history
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>
