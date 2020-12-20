<form id="filter-form">
    <table>
        <tr>
            <td title="Textsuche">
                <input type="text" name="searcht" id="searcht" oninput="filterList('<?php echo URLROOT; ?>')"
                       placeholder="Filter" value="<?php echo $_SESSION['searcht']; ?>" />
            </td>
            <td title="Früheste Fälligkeit">
                <input type="date" name="datemin" id="datemin" onchange="filterList('<?php echo URLROOT; ?>')"
                       value="<?php echo $_SESSION['datemin']; ?>" />
            </td>
            <td title="Späteste Fälligkeit">                    
                <input type="date" name="datemax" id="datemax" onchange="filterList('<?php echo URLROOT; ?>')"
                       value="<?php echo $_SESSION['datemax']; ?>" />
            </td>
            <td title="Sortierung nach">
                <select name="sortcol" id="sortcol" onchange="filterList('<?php echo URLROOT; ?>')">
                    <option value="text" <?php echo ($_SESSION['sortcol'] == 'text' ? 'selected' : ''); ?>>Text</option>
                    <option value="expiry" <?php echo ($_SESSION['sortcol'] == 'expiry' ? 'selected' : ''); ?>>Fälligkeit</option>
                    <option value="alarm" <?php echo ($_SESSION['sortcol'] == 'alarm' ? 'selected' : ''); ?>>Alarm</option>
                </select>
            </td>
            <td class="left">
                <input type="radio" value="asc" name="ascdesc" title="Aufsteigend" onchange="filterList('<?php echo URLROOT; ?>')"
                       <?php echo ($_SESSION['ascdesc'] == 'asc' ? 'checked' : ''); ?> class="invert">
                <input type="radio" value="desc" name="ascdesc" title="Absteigend" onchange="filterList('<?php echo URLROOT; ?>')"
                       <?php echo ($_SESSION['ascdesc'] == 'desc' ? 'checked' : ''); ?> class="invert">
            </td>
        </tr>
    </table>
</form>