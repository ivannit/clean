<?php
require APPROOT . '/views/includes/head.php';
?>

<section class="full wall container">
<?php
include_once APPROOT . '/views/includes/navheader.php';
?>
    <div class="wrapper center">
        <h1>Registrierung</h1>

        <form action="<?php echo URLROOT; ?>/users/register" method ="POST">

            <input type="text" placeholder="Benutzername" name="username" 
                   value="<?php echo $data['username']; ?>">
            <div class="feedback-negative">
                <?php echo $data['usernameError']; ?>
            </div>
            
            <input type="email" placeholder="E-Mail-Adresse" name="email"
                   value="<?php echo $data['email']; ?>">
            <div class="feedback-negative">
                <?php echo $data['emailError']; ?>
            </div>

            <input type="password" placeholder="Passwort" name="password"
                   value="<?php echo $data['password']; ?>">
            <div class="feedback-negative">
                <?php echo $data['passwordError']; ?>
            </div>

            <input type="password" placeholder="Passwort bestätigen" name="confirmPassword"
                   value="<?php echo $data['confirmPassword']; ?>">
            <div class="feedback-negative">
                <?php echo $data['confirmPasswordError']; ?>
            </div>
            
            <input type="checkbox" name="consent" class="invert" value="yes">
            <span class="linking">mit
                <a target="_blank" href="<?php echo URLROOT; ?>/pages/datenschutz">Datenschutzerklärung</a>
                einverstanden
            </span>
            <div class="feedback-negative">
                <?php echo $data['consentError']; ?>
            </div>

            <button class="submit" type="submit" value="submit">Registrieren</button>

        </form>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';
