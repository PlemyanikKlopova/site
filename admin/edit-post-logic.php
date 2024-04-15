<?php
require 'config/database.php';

//make sure edit button was clicked
if(isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    //set is_featured to 0 if it was uncheked
    $is_featured = $is_featured == 1 ?: 0;

    //check and validate input vaues
    if(!$title) {
        $_SESSION['edit-post'] = "Couldn`t update post. Enter post title";
    } elseif(!$category_id) {
        $_SESSION['edit-post'] = "Couldn`t update post. Select category";
    } elseif(!$body) {
    $_SESSION['edit-post'] = "Couldn`t update post. Enter post body";
    } else {
        //delete existing thumbnail if new thumbnail available
        if($thumbnail['name']) {
            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
            if($previous_thumbnail_path) {
                unlink($previous_thumbnail_path);
            }


            //work on new thumbnail
            //rename image
            $time = time();
            $thumbnail_name = $time . $thumbnail['name'];
            $thumbnail_tmp_name = $thumbnail['tmp_name'];
            $thumbnail_destination_path = '../images/' . $thumbnail_name;

            //make sure file is an imge
            //make sure file is image
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extention = explode('.', $thumbnail_name);
            $extention = end($extention);
            if(in_array($extention, $allowed_files)) {
                 // make sure image is not too big (2+mb)
                if($thumbnail['size'] < 2000000) {
                //uplaod thubmnail
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
            $_SESSION['edit-post'] = "File size to big (2mb+)";
            }
            } else {
                $_SESSION['add-post'] = "File should be png, jpg or jpeg.";
            }
        }
    }

    if($_SESSION['edit-post']) {
        //redirect to manage form page
        header('location: ' . ROOT_URL . 'admin/');
        die();
    } else {
        //set is_featured of all posts to 0 if is_featured for this post is 1
        if($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE `posts` SET `is_featured` = 0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }

        //set thumbnail name if a new one was uploaded, else keep old thumbnail name
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        $query = "UPDATE `posts` SET `title`='$title', `body`='$body', `thumbnail` = '$thumbnail_to_insert', `category_id` = '$category_id', `is_featured` = '$is_featured' WHERE `id`='$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
    }

    if(!mysqli_errno($connection)) {
        $_SESSION['edit-post-success'] = "Post updated successfully";
    }
}

header('location: ' . ROOT_URL . 'admin/');
die();