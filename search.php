<?php
require 'partials/header.php';

if(isset($_GET['search']) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "SELECT * FROM `posts` WHERE `title` LIKE '%$search%' ORDER BY `date_time` DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}
?>

<?php  
    if(mysqli_num_rows($posts)>0) :
?>


<section class="search_bar">
        <form class="container search_bar-container" action="<?= ROOT_URL ?>search.php" method="GET">
            <div>
                <i class="uil uil-search"></i>
                <input type="search" name="search" placeholder="Search">
            </div>
            <button type="submit" name="submit" class="btn">Go</button>
        </form>
    </section>
    <!-- ================================= end search ===============================-->

<section class="posts section_extra-margin">
        <div class="container post_container">
            <?php while($post = mysqli_fetch_assoc($posts)) : ?>
            <article class="post">
                <div class="post_thumbnail">
                    <img src="./images/<?= $post['thumbnail'] ?>">
                </div>
                <div class="post_info">
                    <?php  
                    //fetch category from DB using category_id of post
                    $category_id = $post['category_id'];
                    $category_query = "SELECT * FROM `categories` WHERE `id`='$category_id'";
                    $category_result = mysqli_query($connection, $category_query);
                    $category = mysqli_fetch_assoc($category_result);
                    ?>
                    <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category_button"><?= $category['title'] ?></a>
                    <h3 class="post_title"><a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h3>
                    <p class="post_body">
                        <?= substr($post['body'], 0, 150) ?>...
                    </p>
                    <div class="post_author">
                        <?php 
                        //fetch author from users 
                        $author_id = $post['author_id'];
                        $author_query = "SELECT * FROM `users` WHERE `id`='$author_id'";
                        $author_result = mysqli_query($connection, $author_query);
                        $author = mysqli_fetch_assoc($author_result);

                        ?>
                        <div class="post_author-avatar">
                            <img src="./images/<?= $author['avatar'] ?>">
                        </div>
                        <div class="post_author-info">
                            <h5><?="{$author['firstname']} {$author['lastname']}"?></h5>
                            <small>
                                <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </article>
            <?php endwhile ?>
        </div>
    </section>
    <?php else : ?>
        <div class="section_extra-margin alert_message error lg ">
            <p>No posts found for ths serch</p>
        </div>
    <?php endif ?>
<!-- ================================= end post ===============================-->

<section class="category_buttons">
        <div class="container category_button-container">
            <?php  
                $all_categories_query = "SELECT * FROM `categories`";
                $all_categories = mysqli_query($connection, $all_categories_query);

            ?>
            <?php while($category = mysqli_fetch_assoc($all_categories)) : ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category_button"><?= $category['title'] ?></a>
            <?php endwhile ?>
        </div>
    </section>

    <!-- ================================= end category buttons ===============================-->

<?php 
include 'partials/footer.php';
?>