<?php

/* ============================================================== [Constants] */
const VER_APP_CUR  = 'v0.1 alpha'; //Current version of this app.
const VER_GIT_MIN  = '1.8.3';      //Git version tested.
const NAME_AUTHOR  = 'Qithub-BOT Organization @ GitHub';
const DIR_SEP      = DIRECTORY_SEPARATOR;
const DO_ECHO      = true;
const DO_NOT_ECHO  = false;
const MARK_OK      = '✅';
const MARK_NG      = '☑️ ';
const PATH_UNKNOWN = null;

/* =============================================================== [Settings] */

// Must change settings
$url_repo         = 'https://github.com/Qithub-BOT/Qithub-ORG.git';
$name_dir_target  = 'DocumentRoot'; //Root dir of the website

// Local settings
$name_dir_current = __DIR__;
$mode_dir_target  = 0777;
$name_dir_git    = '.git';

$path_dir_target = $name_dir_current . DIR_SEP . $name_dir_target;
$path_dir_git    = $path_dir_target  . DIR_SEP . $name_dir_git;

setPathDirGit($path_dir_git);
setPathDirRoot($path_dir_target);

/* =================================================================== [Main] */

clearScreen();
echoTitle();

if (! isAvailableCommandGit()) {
    dieMsg('Git command not available.');
}

// Fetch git from Origin
if (dir_exists($path_dir_target) && dir_exists($path_dir_git)) {
    echo MARK_OK . ' Found: Web document root.', PHP_EOL;
    fetchGit($url_repo, $name_dir_target);
    dieMsg( MARK_OK . ' Updated.');
}

// Clone git from Origin as DocumentRoot
if (! dir_exists($path_dir_git)) {
    $path_dir_git = (string) $path_dir_git;

    echo MARK_NG . " No '.git' dir found at: '{$path_dir_git}'", PHP_EOL;

    if (cloneGit($url_repo, $name_dir_target)) {
        //
    }

    if (dir_exists($path_dir_git)) {
        dieMsg(MARK_OK . ' Cloned.');
    }

    dieMsg(MARK_NG . ' Fail cloning. Check file permission.');
}

if (! dir_exists($path_dir_target)) {
    dieMsg('No web document root found: ' . $name_dir_target);
}

dieMsg('Unknown process error.');

/* ============================================================== [Functions] */

/* ---------------------------------------------------------------------- [C] */

function clearScreen()
{
    $cmd = 'clear';
    runCmd($cmd, DO_ECHO);
}

function cloneGit($url_repo, $name_dir_git)
{
    $cmd = "git clone {$url_repo} {$name_dir_git}";

    echo "\t" . '- Cloning git from GitHub ...', PHP_EOL;
    echo "\t" . '  URL: ', $url_repo, PHP_EOL;
    echo "\t" . '  Name dir to clone: ', $name_dir_git, PHP_EOL;
    echo "\t" . '  CMD: ', $cmd, PHP_EOL, PHP_EOL;

    echo runCmd($cmd), PHP_EOL;
}

/* ---------------------------------------------------------------------- [D] */

function dieMsg($msg)
{
    echo (string) $msg, PHP_EOL;
    die;
}

function dir_exists($path_dir)
{
    return is_dir((string) $path_dir);
}

/* ---------------------------------------------------------------------- [E] */

function echoHR($echo = DO_NOT_ECHO)
{
    $horizontal_line = '===========================' . PHP_EOL;

    if (DO_ECHO === $echo) {
        echo $horizontal_line;
    }

    return $horizontal_line;
}

function echoTitle()
{
    $title  = '';
    $title .= echoHR(DO_NOT_ECHO);
    $title .= "\t" . 'Qithub-ORG site cloner';
    $title .= ' (' . VER_APP_CUR . ')' . PHP_EOL . PHP_EOL;
    $title .= "\t" . 'By ' . NAME_AUTHOR . PHP_EOL;
    $title .= getIdGitCommit() . PHP_EOL;
    $title .= echoHR(DO_NOT_ECHO);

    echo $title, PHP_EOL;
}

/* ---------------------------------------------------------------------- [F] */

function fetchGit($url_repo, $name_dir_git)
{
    $path_dir_git  = getPathDirGit();
    $path_dir_root = getPathDirRoot();

    if (! dir_exists($path_dir_git) || (PATH_UNKNOWN === $path_dir_git)) {
        echo MARK_NG, ' Path to .git not found.', PHP_EOL;
        return false;
    }

    $cmd  = 'cd ' . $path_dir_root . ' && ';
    $cmd .= 'echo pwd && ';
    $cmd .= 'git fetch origin ';
    $cmd .= '&& git reset --hard origin/master';

    echo "\t" . '- Fetching git from Origin (GitHub) ...', PHP_EOL;
    echo "\t" . '  URL: ', $url_repo, PHP_EOL;
    echo "\t" . '  Name dir to clone: ', $name_dir_git, PHP_EOL;
    echo "\t" . '  Path dir to .git: ', $path_dir_git, PHP_EOL;
    echo "\t" . '  Path dir to DocRoot: ', $path_dir_root, PHP_EOL;
    echo "\t" . '  CMD: ', $cmd, PHP_EOL;
    echo "\t" . '  PWD: ', `pwd`, PHP_EOL;

    echo runCmd($cmd), PHP_EOL;
}

/* ---------------------------------------------------------------------- [G] */

function getIdGitCommit()
{
    $path = getPathDirGit();
    $cmd  = 'cd ' . $path . ' && ';
    $cmd .= 'git log -n 1 --format=%H';

    $ver  = runCmd($cmd);
    
    return $ver;
}

function getPathDirGit()
{
    if (defined('PATH_DIR_GIT')) {
        return realpath(PATH_DIR_GIT) ?: PATH_UNKNOWN;
    }
    return PATH_UNKNOWN;
}

function getPathDirRoot()
{
    if (defined('PATH_DIR_ROOT')) {
        return realpath(PATH_DIR_ROOT) ?: PATH_UNKNOWN;
    }
    return PATH_UNKNOWN;
}

function getVersionCmdGit()
{
    $cmd    = 'git --version';
    $result = trim(runCmd($cmd));

    if (empty($result)) {
        echo "Unable to detect Git command with '$ {$cmd}'.", PHP_EOL;
        echo "\t", 'Returned msg was empty.', PHP_EOL;
        return false;
    }

    if (false === strpos(strtolower($result), 'git version')) {
        echo "Unable to detect Git command with '$ {$cmd}'.", PHP_EOL;
        echo "\t", 'Returned msg was: ', $result, PHP_EOL;
        return false;
    }

    $result = explode(' ', $result);

    $result[0] = strtolower(trim($result[0]));
    $result[1] = strtolower(trim($result[1]));

    if ('git' !== $result[0] && 'version' !== $result[1]) {
        echo 'Invalid version info. It has no version info.', PHP_EOL;
        return false;
    }

    return $result[2];
}

/* ---------------------------------------------------------------------- [I] */

function isAvailableCommandGit()
{
    $ver_cmd_git = getVersionCmdGit();

    if (empty($ver_cmd_git)) {
        return false;
    }

    if (! isVersionHigherThan($ver_cmd_git, VER_GIT_MIN)) {
        echo 'Git version is too low. Version: ', $ver_cmd_git, '. Required ver: > ', VER_GIT_MIN, PHP_EOL;
        return false;
    }

    echo MARK_OK . ' Git Version: ', $ver_cmd_git, PHP_EOL;

    return $ver_cmd_git;
}

function isHashedValue($string)
{
    $true = is_string($string);
    
    
}

function isVersionHigherThan($ver_current, $ver_min)
{
    return version_compare($ver_current, $ver_min, '>=');
}

/* ---------------------------------------------------------------------- [R] */

function runCmd($cmd, $echo = DO_NOT_ECHO)
{
    $cmd = (string) $cmd . ' 2>&1';

    if (DO_ECHO === $echo) {
        echo exec($cmd);
        return true;
    }

    return exec($cmd, $output, $return_var);
}

/* ---------------------------------------------------------------------- [S] */

function setPathDirGit($path)
{
    $path = (string) $path;

    if (defined('PATH_DIR_GIT')) {
        echo MARK_NG, ' PATH_DIR_GIT already defined.', PHP_EOL;
        return false;
    }

    define('PATH_DIR_GIT', $path);
}

function setPathDirRoot($path)
{
    $path = (string) $path;

    if (defined('PATH_DIR_ROOT')) {
        echo MARK_NG, ' PATH_DIR_ROOT already defined.', PHP_EOL;
        return false;
    }

    define('PATH_DIR_ROOT', $path);
}
