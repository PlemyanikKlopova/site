<?php
include 'partials/header.php';

//get back form data

$title = $_SESSION['add-category-data']['title'] ?? null;
$description = $_SESSION['add-category-data']['description'] ?? null;

unset($_SESSION['add-category-data']);
?>

<section class="form_section">
    <div class="container form_section-container">
        <h2>Add category</h2>
        <?php if(isset($_SESSION['add-category'])) : ?>
            <div class="alert_message error" >
            <p>
                <?= $_SESSION['add-category'];
                unset($_SESSION['add-category']); ?>
            </p>
        </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/add-category-logic.php" method="POST">
            <input type="text" name="title" value="<?= $title ?>" placeholder="Title">
            <textarea rows="4" name="description" value="<?= $description ?>" placeholder="Description"></textarea>
            <button type="submit" name="submit" class="btn">Add category</button>
            
    </div>
</section>

<?php
include '../partials/footer.php';
?>