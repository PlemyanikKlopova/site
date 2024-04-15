<?php
require 'config/database.php';

if(isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    //fetch user from DB
    $query = "SELECT * FROM `users` WHERE `ID` = $id";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    //make sure we got back only one user
    if(mysqli_num_rows($result) == 1) {
        $avatar_name = $user['avatar'];
        $avatar_path = '../images/' . $avatar_name;
        // delete image 
        if($avatar_path) {
            unlink($avatar_path);
        }
    }
    
    // for later 
    //fetch thumbnails of users`s post and delete them
    $thubmnails_query = "SELECT `thumbnail` FROM `posts` WHERE `author_id` = '$id'";
    $thubmnails_result = mysqli_query($connection, $thubmnails_query);
    if(mysqli_num_rows($thubmnails_result) > 0) {
        while($thubmnail = mysqli_fetch_assoc($thubmnails_result)) {
            $thubmnails_path = '../images/' . $thubmnail['thumbnail'];
            //delete thumbnail from images
            if($thubmnails_path) {
                unlink($thubmnails_path);
            }
        }
    }


    //delete user from DB
    $delete_user_query = "DELETE FROM `users` WHERE `ID`=$id";
    $delete_user_result = mysqli_query($connection, $delete_user_query);
    if(mysqli_errno($connection)) {
        $_SESSION['delete-user'] = "Couldn`t delete '{$user['firstname']} '{$user['lastname']}'";
    } else {
        $_SESSION['delete-user-success'] = "{$user['firstname']} '{$user['lastname']} deleted successfully";
    } 

}
header('location: ' .ROOT_URL . 'admin/manage-users.php');
die();