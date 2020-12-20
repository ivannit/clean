<tr id="create">
    <td data-label="Text" onclick="expandCreate()">
        <textarea name="text" id="text" class="text" placeholder="Neue Notiz"></textarea>
    </td>
    <td data-label="Bilder">
        <form id="pix-form">
            <div id="pix" title="Bild-Datei auswählen" class="center"></div>
            <span onclick="inputPicture('pix')" id="add-pix"
                  title="<5MB jpg/png/jpeg/gif">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </span>
        </form>
    </td>
    <td data-label="Fällig">
        <input type="date" name="expiry-date" id="expiry-date" value="<?php echo date("Y-m-d"); ?>">
        <input type="time" name="expiry-time" id="expiry-time" value="<?php echo date("H:m"); ?>">
    </td>
    <td data-label="Intervall">
        <select name="repeat" id="repeat" onchange="customFrequency()">
            <option selected>Einmalig</option>
            <option>Täglich</option>
            <option>Wöchentlich</option>
            <option>Monatlich</option>
            <option>Jährlich</option>
            <option>Tagesintervall...</option>
        </select>
        <input type="number" min=2 id="days" value="2" class="hidden"
               title="Tage"/>
    </td>
    <td data-label="Alarm" class="center mid air" title="E-Mail bei Fälligkeit, 10-minutengenau">
        <input type="checkbox" name="alarm" id="alarm" class="invert">
    </td>
    <td data-label="" class="done mid" title="Speichern">
        <!-- svg id="collapse" onclick="collapseCreate()"
             xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg -->
        <svg onclick="saveNote('<?php echo URLROOT; ?>', 'pix-form')"
            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
    </td>
</tr>


