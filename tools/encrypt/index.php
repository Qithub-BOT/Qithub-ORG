<?php

if(file_exists('../../.includes/Parsedown.php.inc')){
    echo 'OK', PHP_EOL;
}

die;

/* ============================================================= [Initialize] */

header("X-Robots-Tag: noindex, nofollow");

/* =================================================================== [Main] */

$type = isset($_GET['type']) ? $_GET['type'] : '';

/* If has request query then show request as text. */
if(! empty($type)){

    header('Content-Type: text/plain');

    $name_file_local    = $type . ".sh.txt";
    $name_file_download = $type . ".sh";

    if(! file_exists($name_file_local)){
        die('File not found.');
    }

    header("Content-Disposition: attachment; filename={$name_file_download}");
    readfile($name_file_local);
    
    die;
}


/* =========================================================== [Default Page] */

include('../../../.includes/Parsedown.php.inc');

$Parsedown = new Parsedown();
$body      = $Parsedown->text(file_get_contents('README.md'));

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="noindex">
	<title>暗号化・復号スクリプト - Qithub.tk</title>
</head>
<body>
<?=$body; ?>
</body>
</html>