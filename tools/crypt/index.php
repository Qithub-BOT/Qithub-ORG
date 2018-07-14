<?php

/* ============================================================= [Initialize] */

header("X-Robots-Tag: noindex, nofollow");

const RAW_OUTPUT = true;

/* =================================================================== [Main] */

$type = isset($_GET['type']) ? $_GET['type'] : '';

/* If has request query then show request as text. */
if (! empty($type)) {
    header('Content-Type: text/plain; charset=UTF-8');

    $name_file_local    = $type; // 拡張子'.sh'は削除
    $name_file_download = $type;

    if (! file_exists($name_file_local)) {
        die('File not found.');
    }

    $content_md5   = base64_encode(md5($name_file_local, RAW_OUTPUT));
    $etag          = md5($name_file_local);
    $last_modified = gmdate("D, d M Y H:i:s T", filemtime($name_file_local));

    header("Last-Modified: {$last_modified}");
    header("Etag: {$etag}");
    header("Content-MD5:{$content_md5}");
    header("Content-Disposition: attachment; filename={$name_file_download}");
    readfile($name_file_local);

    die;
}


/* =========================================================== [Default Page] */

include('../../.includes/Parsedown.php.inc');

$Parsedown = new Parsedown();
$body      = $Parsedown->text(file_get_contents('README.md'));

header('Content-Type: text/html; charset=UTF-8');

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
