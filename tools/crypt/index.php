<?php

/* ============================================================= [Initialize] */

header("X-Robots-Tag: noindex, nofollow");

/* =================================================================== [Main] */

$type = isset($_GET['type']) ? $_GET['type'] : '';

/* If has request query then show request as text. */
if(! empty($type)){

    header('Content-Type: text/plain');

    $name_file_local    = $type . ".sh";
    $name_file_download = $type;

    if(! file_exists($name_file_local)){
        die('File not found.');
    }

    header("Content-Disposition: attachment; filename={$name_file_download}");
    readfile($name_file_local);
    
    die;
}


/* =========================================================== [Default Page] */

include('../../.includes/Parsedown.php.inc');

$Parsedown = new Parsedown();
$body      = $Parsedown->text(file_get_contents('README.md'));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<meta name="robots" content="noindex">
	<title>暗号化・復号スクリプト - Qithub.tk</title>
	<link href="/css/github-markdown.css" rel="stylesheet" type="text/css">
</head>
<body class="markdown-body">
<?=$body; ?>
</body>
</html>