<?php
include 'partials/header.php';

//fetch featured post from DB

$featured_query = "SELECT * FROM `posts` WHERE `is_featured`=1";
$featured_result = mysqli_query($connection, $featured_query);
$featured = mysqli_fetch_assoc($featured_result);

//fetch 9 posts from DB
$query = "SELECT * FROM `posts` ORDER BY `date_time` DESC LIMIT 9";
$posts = mysqli_query($connection, $query);
?>

<?php if(mysqli_num_rows($featured_result) == 1) :?>
    <section class="featured">
        <div class="container featured_container">
            <div class="post_thumbnail">
                <img src="./images/<?= $featured['thumbnail'] ?>">
            </div>
            <div class="post_info">
                <?php  
                //fetch category from DB using category_id of post
                $category_id = $featured['category_id'];
                $category_query = "SELECT * FROM `categories` WHERE `id`='$category_id'";
                $category_result = mysqli_query($connection, $category_query);
                $category = mysqli_fetch_assoc($category_result);
                ?>
                <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $featured['category_id'] ?>" class="category_button"><?= $category['title'] ?></a>
                <h2 class="post_title"><a href="<?= ROOT_URL ?>post.php?id=<?= $featured['id'] ?>"><?= $featured['title'] ?></a></h2>
                <p class="post_body">
                    <?=substr($featured['body'], 0, 300) ?>...    
                </p>
                <div class="post_author">
                    <?php 
                    //fetch author from users 
                    $author_id = $featured['author_id'];
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
                            <?= date("M d, Y - H:i", strtotime($featured['date_time'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>
<!-- ================================= end fetured ===============================-->

    <section class="posts <?= $featured ? '' : 'section_extra-margin' ?>">
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