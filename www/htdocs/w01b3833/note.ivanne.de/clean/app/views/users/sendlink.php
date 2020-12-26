<?php
require APPROOT . '/views/includes/head.php';
?>

<section class="full wall container">
<?php
include_once APPROOT . '/views/includes/navheader.php';
?>
    <div class="wrapper center">
        <h1>Reset-Link</h1>
        
        <p><?php echo $data['linkSent']; ?></p>
        
        <p>Der Reset-Link wird benötigt, um das Passwort zurückzusetzen. Jeder Reset-Link läuft nach <?php echo RESETTIME; ?> Minuten ab. Ein neuer Reset-Link kann immer gesendet werden.</p>

        <form action="<?php echo URLROOT; ?>/users/sendlink" method ="POST">
            
            <input type="email" placeholder="E-Mail-Adresse" name="linkemail" required>

            <button title="Link zum Passwort Zurücksetzen per E-Mail senden"
                    class="submit" type="submit" value="submit">Anfordern</button>

        </form>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';