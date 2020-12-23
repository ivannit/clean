<table>
    <thead>
        <tr>
            <th>Text</th><th>Bilder</th><th>Fälligkeit</th><th>Intervall</th><th>Alarm</th><th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        require APPROOT . '/views/notes/create.php';
        ?>
        <?php foreach ($data['notes'] as $note): ?>
            <?php
            $due = false;
            if ($note->repeat != 'Einmalig') {
                $datemin = $_SESSION['datemin'];
                while ($datemin <= $_SESSION['datemax']) {
                    if ($noteModel->todoIsDue($note->repeat, $note->expiry, $datemin)) {
                        $due = true;
                        break;
                    }
                    $datemin = date('Y-m-d', strtotime($datemin . ' +1 day'));
                }
            }
            ?>
            <?php if ($due == true || $note->repeat == 'Einmalig'): ?>
                <tr id="note<?php echo $note->noteid; ?>" class="<?php echo URLROOT; ?>">
                    <td data-label="Text" class="note<?php echo $note->noteid; ?> newtext">
                        <input type="text" value="<?php echo $note->text; ?>" onclick="transformToArea(this)">
                    </td>
                    <td data-label="Bilder" class="pix center note<?php echo $note->noteid; ?>">
                        <?php foreach (explode(' ', $note->pixids) as $id): ?>
                            <?php if ($id > 0): ?>
                                <img src="<?php echo URLROOT; ?>/notes/minpix/<?php echo $id ?>"
                                     onclick="maximizePix('<?php echo URLROOT; ?>', <?php echo $id ?>)"
                                     alt="Notiz <?php echo $note->noteid; ?>">
                                     <?php endif; ?>
                                 <?php endforeach; ?>
                    </td>
                    <td data-label="Fällig" class="note<?php echo $note->noteid; ?> newexpiry">
                        <input type="text" value="<?php echo $note->expiry; ?>" onchange="updateNote(this)">
                    </td>
                    <td data-label="Intervall" class="note<?php echo $note->noteid; ?> newrepeat">
                        <input type="text" value="<?php echo $note->repeat; ?>" onchange="updateNote(this)">
                    </td>
                    <td data-label="Alarm" class="note<?php echo $note->noteid; ?> newalarm center mid air">
                        <input type="checkbox" class="invert" 
                               <?php echo ($note->alarm == 'true' ? 'checked' : '') ?> onchange="updateNote(this)">
                    </td>
                    <td data-label="" class="del mid">
                        <a href="<?php echo URLROOT; ?>/notes/delete/<?php echo $note->noteid; ?>"
                           onmouseenter="highlightRow('note<?php echo $note->noteid; ?>',.4)"
                           onmouseout="highlightRow('note<?php echo $note->noteid; ?>', 1)" title="Notiz endgültig löschen">
                            <svg onmouseover="highlightRow('note<?php echo $note->noteid; ?>', .2)"
                                 xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
