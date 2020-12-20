<?php
require APPROOT . '/views/includes/head.php';
?>

<section class="container">
<?php
include_once APPROOT . '/views/includes/navheader.php';
?>
    <div class="wrapper center">
        <h1>Zurücksetzen</h1>

        <form action="<?php echo URLROOT; ?>/users/reset" method ="POST">
            
            <input type="hidden" name="resetCode" value="<?php echo $data['code']; ?>">
            <input type="hidden" name="resetEmail" value="<?php echo $data['email']; ?>">
            
            <input type="password" placeholder="Neues Passwort" name="newPassword">
            <div class="feedback-negative">
                <?php echo $data['newPasswordError']; ?>
            </div>

            <input type="password" placeholder="Passwort bestätigen" name="newconfirm">
            <div class="feedback-negative">
                <?php echo $data['newConfirmPasswordError']; ?>
            </div>

            <button class="submit" type="submit" value="submit"
                    title="Neues Passwort speichern">Speichern</button>

        </form>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';