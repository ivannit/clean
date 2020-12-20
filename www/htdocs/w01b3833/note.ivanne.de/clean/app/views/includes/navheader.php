<header>
    <nav class="top-nav">
        <ul>
            <li>
                <a <?php currentActive($this->view, 'index'); ?> 
                    href="<?php echo URLROOT; ?>/pages/index"> &nbsp; Home</a>
            </li>
            <li>
                <a <?php currentActive($this->view, 'info'); ?>
                    href="<?php echo URLROOT; ?>/pages/info"> &nbsp; Info</a>
            </li>
            <li>
                <a <?php currentActive($this->view, 'quotes'); ?> 
                    href="<?php echo URLROOT; ?>/pages/quotes"> &nbsp; Zitate</a>
            </li>
            <?php if (isLoggedIn()) : ?>
                <li>
                    <a <?php currentActive($this->view, 'list'); ?> 
                        href="<?php echo URLROOT; ?>/notes/list"> &nbsp; Notizen</a>
                </li>
                <li>
                    <a <?php currentActive($this->view, 'logout'); ?> 
                        href="<?php echo URLROOT; ?>/users/logout"> &nbsp; Logout</a>
                </li>
            <?php else : ?>
                <li id="logout">
                    <a <?php currentActive($this->view, 'login'); ?>
                        href="<?php echo URLROOT; ?>/users/login"> &nbsp; Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

