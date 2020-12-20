<?php
include_once APPROOT . '/views/includes/head.php';
?>

<section>
    <?php
    include_once APPROOT . '/views/includes/navheader.php';
    ?>
    <div class="wrapper center">
        <h1>Impressum</h1>

        <p><?php echo ADDRESS; ?></p>
        <p class="linking">
            <a href="tel:<?php echo PHONE; ?>"><?php echo PHONE; ?></a><br/>
            <a href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a><br/>
        </p>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';
