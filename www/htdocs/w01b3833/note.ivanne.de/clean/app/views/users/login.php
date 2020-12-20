<?php
require APPROOT . '/views/includes/head.php';
?>


<section class="container">
    <?php
    include_once APPROOT . '/views/includes/navheader.php';
    ?>
    <div class="wrapper center">
        <h1>Login</h1>

        <form action="<?php echo URLROOT; ?>/users/login" method ="POST" id="login-form">

            <input type="email" placeholder="E-Mail-Adresse" name="email">
            <div class="feedback-negative">
                <?php echo $data['emailError']; ?>
            </div>

            <input type="password" placeholder="Passwort" name="password">
            <div class="feedback-negative">
                <?php echo $data['passwordError']; ?>
            </div>

            <button class="submit" type="submit" value="submit">Einloggen</button>

            <p class="linking">Passwort vergessen? <a href="<?php echo URLROOT; ?>/users/sendlink">Hier zurücksetzen</a></p>
            <p class="linking">Noch kein Konto? <a href="<?php echo URLROOT; ?>/users/register">Hier registrieren</a></p>

        </form>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';

