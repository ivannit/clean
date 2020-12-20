<fieldset id="sketchpad"><legend>Frei―Maus</legend>
    <table>
        <tr>
            <td>
                <label for="linecolor">
                    <img src="<?php echo URLROOT; ?>/public/img/palette-24px.svg" alt="Farbpalette">
                </label>
            </td>
            <td>
                <input type="color" id="linecolor" />
            </td>
        </tr>
        <tr>
            <td><label for="linewidth">
                    <img src="<?php echo URLROOT; ?>/public/img/gesture-24px.svg" alt="Freihand">
                </label>
            </td>
            <td>
                <input type="number" id="linewidth" value="2" min="1" />
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <canvas id="canvas" height="400" width="400">
                </canvas>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="center">
                <button onclick="downloadCanvas();return false;" class="submit">
                    Herunterladen
                </button>
                <button onclick="clearCanvas();return false;"  class="submit">
                    Löschen
                </button>
            </td>
        </tr>
    </table>
</fieldset>
