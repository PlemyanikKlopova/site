<?php
require 'config/database.php';

IF(isset($_POST['submit'])) {
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // set is_featured to 0 if uncheked
    $is_featured = $is_featured == 1 ?: 0;

    //validate form data
    if(!$title) {
        $_SESSION['add-post'] = "Enter post title";
    } elseif(!$category_id) {
        $_SESSION['add-post'] = "Select post category";
    } elseif(!$body) {
        $_SESSION['add-post'] = "Enter post body";
    } elseif(!$thumbnail['name']) {
        $_SESSION['add-post'] = "Choose pos tthumbnail";
    } else 
    //work in thumbnail
    //rename the image
    $time = time(); // make each image name uniqe
    $thumbnail_name = $time . $thumbnail['name'];
    $thumbnail_tmp_name = $thumbnail['tmp_name'];
    $thumbnail_destination_path = '../images/' . $thumbnail_name;

    //make sure file is an image
    $alloweed_files = ['png', 'jpg', 'jpeg'];
    $extantion = explode('.', $thumbnail_name);
    $extantion = end($extantion);
    if(in_array($extantion, $alloweed_files)) {
        // make sure image is not too big (2+mb)
        if($thumbnail['size'] < 2000000) {
            //uplaod thubmnail
            move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
        } else {
            $_SESSION['add-post'] = "File size to big (2mb+)";
        }
    } else {
        $_SESSION['add-post'] = "File should be png, jpg or jpeg.";
    }
    // any error
    if(isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        // set is_fetured of all posts to 0 if is_featured for this post is 1
        if($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE `posts` SET `is_featured` = 0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }

        //insert post into DB
        $query = "INSERT INTO `posts` (`title`, `body`, `thumbnail`, `category_id`, `author_id`, `is_featured`) VALUES ('$title', '$body', '$thumbnail_name', '$category_id', '$author_id', '$is_featured')";
        $result = mysqli_query($connection, $query);

        if(!mysqli_errno($connection)) {
            $_SESSION['add-post-success'] = "New post added successfully";
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
    } 
}
header('location: ' . ROOT_URL . 'admin/add-post.php');
die();