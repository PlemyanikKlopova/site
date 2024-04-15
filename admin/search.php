<?php

if(isset($_GET['search']) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "SELECT * FROM `posts` WHERE `title` LIKE '%$search%' ORDER BY `date_time` DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL . 'admin/index.php');
    die();
}
?>
