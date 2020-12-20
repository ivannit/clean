<html>
    <head>
        <meta charset="UTF-8">
        <meta htttp-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo SITENAME . ' - ' . $data['title']; ?></title>
        <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/public/img/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/public/img/favicon-16x16.png" sizes="16x16" />
        <link type="text/css" rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
        <script src="<?php echo URLROOT; ?>/public/javascript/functions.js"></script>
    </head>
    <body onload="setSizeSession('<?php echo URLROOT; ?>');
    <?php echo ($_GET['url'] == 'pages/quotes' ? 'initCanvas();' : ''); ?>">
        
        
