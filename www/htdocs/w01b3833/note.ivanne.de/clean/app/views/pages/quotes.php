<?php
include_once APPROOT . '/views/includes/head.php';
?>

<section class="container">
    <?php
    include_once APPROOT . '/views/includes/navheader.php';
    ?>
    <div class="wrapper center">
        <h1>Skizzitate</h1>
        <div class="row quotes">
            <div class="column mouse-only">
                <?php
                include_once APPROOT . '/views/includes/canvas.php';
                ?>
            </div>
            <div class="column right">
                <div class="hidden mouse-only" id="apple-title">Skizze mit Mandelbrotmenge übermalen<br/>(dafür 10x klicken und abwarten)</div>
                <i class="mouse-only" id="apple" onclick="appleman();return false;" onmouseenter="showAppleWarning();" onmouseout="hideAppleWarning();">
                    <img alt="Mandelbrotmenge" src="<?php echo URLROOT; ?>/public/img/mandelbrot-icon.svg" width=40>
                </i>
                <br/><br/>
                <q cite="Why We Love Dogs, Eat Pigs, and Wear Cows">When 
                    an invisible ideology guides our beliefs and behaviors, 
                    we become casualties of a system that has stolen our freedom 
                    to think for ourselves and to act accordingly.</q>
                <p>― Melanie Joy</p>
                <br/>
                <q cite="Why We Love Dogs, Eat Pigs, and Wear Cows">If
                    you are not the victim, don't examine it entirely from 
                    your point of view because when YOU'RE not the victim, it 
                    becomes pretty easy to rationalize and excuse cruelty, 
                    injustice, inequality, slavery, and even murder. But when 
                    you're the victim, things look a lot differently from that 
                    angle.</q>
                <p>― Gary Yourofsky </p>
                <br/>
            </div>
        </div>
    </div>
</section>

<?php
include_once APPROOT . '/views/includes/footer.php';


