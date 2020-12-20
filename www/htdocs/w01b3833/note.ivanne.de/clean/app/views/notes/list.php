<?php
include_once APPROOT . '/views/includes/head.php';
?>

<section class="container-scroll">
    <?php
    include_once APPROOT . '/views/includes/navheader.php';
    ?>
    <div class="wrapper-scroll">
        <h1>Notizen</h1>

        <?php
        require_once APPROOT . '/views/notes/filter.php';
        $noteModel = $this->noteModel;
        ?>
        <div id="list">
            <?php
            require_once APPROOT . '/views/notes/table.php';
            ?>
        </div>
        <div id="maxpix-wrapper" class="hidden">
            <svg onclick="closePix('<?php echo URLROOT; ?>/public/img/animated-running.gif')" 
                 xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
            <a id="maxpixa" href="<?php echo URLROOT; ?>/public/img/man-running.PNG" target="_blank">
                <img id="maxpix" src="<?php echo URLROOT; ?>/public/img/animated-running.gif" alt="Notepix"></a>
        </div>

        <div id="xml" class="linking center">
            <p>
                <a href="<?php echo URLROOT; ?>/scripts/xml.php" target="_blank">
                    Notizliste ohne Bilder im XML-Format öffnen
                </a>
            </p>
            <br/>
            <form action="<?php echo URLROOT; ?>/notes/upload" method ="POST" enctype="multipart/form-data">
                <label for="upload" title="Durchsuchen">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    <input type="file" name="upload" id="upload" title="XML-Datei auswählen"/>
                </label>
                <button title="Links (< >) ausgewählte XML-Datei hochladen"
                        class="submit" type="submit" value="submit">Hochladen</button>
            </form>
        </div>

    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';
?>
