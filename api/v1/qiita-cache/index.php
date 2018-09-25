<?php
/**
 * このスクリプトはリクエストされた時間内に投稿された Qiita 記事を JSON で返します.
 *
 */

namespace Qithub\QiitaItems;

include_once('../.lib/php/tootlib.php.inc');

/* [Constants] ============================================================== */

// 内部環境定数
const FORMAT_THRESHOLD_FETCH = 'YmdHi'; // 201808302059 フォーマット
const FOTMAT_THRESHOLD_HOUR  = 'YmdH';  //
const REQUEST_LIMIT_PER_SEC  = 1;       // QiitaAPI は 1リクエスト/sec (60/hour)
const NUM_PAGES_TO_FETCH     = 2;       // 1リクエストで取得する記事数

// フラグ
const RETURN_AS_BOOL  = 0b00000000;
const RETURN_AS_ID    = 0b00000001;
const RETUEN_AS_ARRAY = 0b00000010;

define('TIME_NOW', time());
define('PATH_DIR_CURR', dirname(__FILE__));

/* [Settings] =============================================================== */

date_default_timezone_set(TIMEZONE); //TimeZone (Tokyo,Japan)

$settings = [
    'qiita' => [
        'schema'   => 'https',
        'host'     => 'qiita.com',
        'endpoint' => '/api/v2/items',
    ],
    'qiitime' => [
        'schema'   => 'https',
        'host'     => 'qithub.tk',
        'endpoint' => '/api/v1/qiitime',
    ],
    'per_page'       => NUM_PAGES_TO_FETCH, // 取得する記事数
    'name_dir_cache' => '.cache', // 設定やキャッシュの保存先
    'name_dir_data'  => '.data',  // RAW データ保存先
    'path_dir_spam'  => PATH_DIR_CURR . DIR_SEP . '.spam', //スパム記事墓場
    'path_dir_curr'  => PATH_DIR_CURR,
    'name_file_data' => 'settings.php.inc',
    'threshold'      => [
        'hour'   => getThresholdHour(),  // この時間を超えたらリセット
        'minute' => getThresholdFetch(), // この時間を超えたら記事をフェッチ
    ],
];

/* [Main] =================================================================== */

header('Content-type: text/plain; charset=utf-8');


$url     = "https://qiita.com/api/v2/items";
$json    = getContentsFromUrl($url);
$result  = json_encode( json_decode($json), JSON_PRETTY_PRINT);

echo $result;
die;

initializeDirectories($settings); //ディレクトリがなければ作成

// トゥート済み情報の保存先を同階層に作成
if (! hasSavedData($settings)) {
    // トゥート済み情報の初回保存（デフォルト値）
    if (! saveData($settings)) {
        dieMsg('Error: Can not save data.', __LINE__);
    }
}

include(getPathFileData($settings));

$result = (isUserOfItem404('ef30669417ff9862bb06')) ? '404' : 'ALIVE';

dieMsg($result);



// 記事IDの指定があるか確認
if (hasItemIdGiven()) {

    $id_item = getItemIdGiven();
    
    // 記事 is 404
    if (isItem404($id_item) && isUserOfItem404($id_item)) {
        // 消された記事をAPIから取得
    }

    $url     = "https://qiita.com/api/v2/items/{$id}";
    $json    = getContentsFromUrl($url);
    $result  = json_decode($json, JSON_OBJECT_AS_ARRAY);
    $id_user = getValue('user', $result)['id'];
    print_r($id_user);
}


/* ユーザーアカウントのチェック */
echo 'USER:', PHP_EOL;
$url    = "https://qiita.com/api/v2/users/{$id_user}";
$json   = getContentsFromUrl($url);
$result = json_decode($json, JSON_OBJECT_AS_ARRAY);
dieMsg($result);




// If 記事ID 指定*
    // Is in 記事キャッシュ
        // Is 404
            // スパムに移動
        // Else
            // 記事キャッシュを更新 if timeout & 表示 die;
    // Is in スパム
        // スパムキャッシュを表示 die;
    // 記事を取得 & キャッシュ
    // 記事を表示 die;
// 最後の状態をロード
// Threshold fetch が同じ時間: Yes -> トゥート済みキャッシュを表示
// 新着Qiita記事取得
// 差分を取得（前回との差分）
// 差分数 is 0: Yes -> $settings の time 更新&上書き then 表示
// トゥート先ID取得
// 差分をループ
    // 記事のユーザー取得: is not in 保存 -> 保存
    // 記事をトゥート is success: No -> Continue
    // If threshold hour が同じ: No -> トゥート済みリセット
    // トゥート済みに ID 追加
    // 記事を保存


$items = new Items($settings);

print_r($items->getItemsLatest());
//print_r(getInfoTootOfQiiTime($settings));
//print_r(getThresholdHour());

// トゥート済み情報の初回保存（デフォルト値）
if (! saveData($settings)) {
    dieMsg('Error: Can not save data.', __LINE__);
}


/* [Functions] ============================================================== */

/* ---------------------------------------------------------------------- [C] */

function convertArrayIncludable(array $array)
{
    $string  = '<?php' . PHP_EOL;
    $string .= '$settings = ' . var_export($array, true) . ';';

    return   $string . PHP_EOL;
}



/* ---------------------------------------------------------------------- [G] */

function generateUrl(array $array)
{
    $schema   = getValue('schema', $array);
    $host     = getValue('host', $array);
    $endpoint = getValue('endpoint', $array);

    return "{$schema}://{$host}{$endpoint}";
}

function getInfoTootOfQiiTime(array $settings)
{
    $url_info = getValue('qiitime', $settings);

    if (empty($url_info)) {
        $settings = PHP_EOL . print_r($settings, true);
        dieMsg('No valid url info given for QiiTime.' . $settings, __LINE__);
    }

    $json = getContentsFromUrl(generateUrl($url_info));

    return json_decode($json, JSON_OBJECT_AS_ARRAY);
}

function getItemIdGiven()
{
    static $result;

    if (isset($result)) {
        return $result;
    }

    $id = hasItemIdInArgs(RETURN_AS_ID);
    if (! empty($id)) {
        $result = $id;
        return $result;
    }

    $id = hasItemIdInGet(RETURN_AS_ID);
    if (! empty($id)) {
        $result = $id;
        return $result;
    }

    return false;
}

function getJsonItemFromApi($id_item)
{
    if(empty($id_item)){
        return false;
    }
    
    $url  = "https://qiita.com/api/v2/items/{$id_item}";
    $json = getContentsFromUrl($url);

    if( null === json_decode($json)){
        return false;
    }

    return $json;
}

function getPathDirCache(array $settings)
{
    $path_dir_curr  = getValue('path_dir_curr', $settings);
    $name_dir_cache = getValue('name_dir_cache', $settings);

    return $path_dir_curr . DIR_SEP . $name_dir_cache;
}

function getPathDirData(array $settings)
{
    $path_dir_curr  = getValue('path_dir_curr', $settings);
    $name_dir_data  = getValue('name_dir_data', $settings);

    return $path_dir_curr . DIR_SEP . $name_dir_data;
}

function getPathDirSpam(array $settings)
{
    return getValue('path_dir_spam', $settings);
}

function getPathFileData(array $settings)
{
    $name_file_data = getValue('name_file_data', $settings);
    $path_dir_data  = getPathDirData($settings);

    return $path_dir_data . DIR_SEP . $name_file_data;
}


function getThresholdHour($time = 0)
{
    $time = (int) $time;

    if (0===$time) {
        $time = TIME_NOW;
    }

    return date(FOTMAT_THRESHOLD_HOUR, $time);
}

function getThresholdFetch($time = 0)
{
    $time = (int) $time;

    if (0===$time) {
        $time = TIME_NOW;
    }

    return date(FORMAT_THRESHOLD_FETCH, $time);
}

function getUrlOfItem($id_item)
{
    if (empty($id_item)) {
        return false;
    }

    $url    = "https://qiita.com/items/{$id_item}";
    $status = getStatusCodeFromUrl($url);

    switch ($status) {
        case 404:
            return false;
        case 301: // リダイレクト
            return getUrlToRedirect($url);
        case 200:
            return $url;
        default:
            return false;
    }
}



/* ---------------------------------------------------------------------- [H] */

function hasItemIdGiven()
{
    return hasItemIdInArgs() || hasItemIdInGet();
}

function hasItemIdInArgs($return = RETURN_AS_BOOL)
{
    global $argv;

    if (! isset($argv)) {
        return false;
    }

    if (false === strpos(getValue(1, $argv), '-id')) {
        return false;
    }

    $id_item = getValue(2, $argv);

    if (! isValidFormatId('item_qiita', $id_item)) {
        return false;
    }

    return ($return === RETURN_AS_BOOL) ?: $id_item;
}

function hasItemIdInGet($return = RETURN_AS_BOOL)
{
    global $_GET;

    $id_item = getValue('id', $_GET);

    if (! isValidFormatId('item_qiita', $id_item)) {
        return false;
    }

    return ($return === RETURN_AS_BOOL) ?: $id_item;
}

function hasSavedData(array $settings)
{
    return file_exists(getPathFileData($settings));
}

/* ---------------------------------------------------------------------- [I] */

function isItem404($id_item)
{
    $url = getUrlOfItem($id_item);
    
    return false === $url;
}

function isUser404($id_user)
{
    $url = "https://qiita.com/{$id_user}";

    return 404 === getStatusCodeFromUrl($url);
}

function isUserOfItem404($id_item)
{
    $url    = "https://qiita.com/api/v2/items/{$id_item}";
    $json   = getContentsFromUrl($url);
    $result = json_decode($json);
    if(! isset($result->user->id)){
        return null;
    }

    return isUser404($result->user->id);
}



/* ---------------------------------------------------------------------- [S] */

function saveData(array $settings)
{
    $path_file_data = getPathFileData($settings);
    $data_to_save   = convertArrayIncludable($settings);

    if (file_put_contents($path_file_data, $data_to_save, LOCK_EX)) {
        $result = getContentsFromUrl($path_file_data);

        return ($result === $data_to_save);
    };

    return false;
}

/* [Class] ================================================================== */

class Items
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getItemsLatest()
    {
        $url   = $this->getUrlApi();
        $json  = getContentsFromUrl($url);
        $array = json_decode($json, JSON_OBJECT_AS_ARRAY);

        return $array;
    }

    private function getUrlApi()
    {
        $url      = generateUrl(getValue('qiita', $this->settings));
        $page     = 1; //１ページ目のみ取得
        $per_page = getValue('per_page', $this->settings); //取得数

        $query = "?page={$page}&per_page={$per_page}";

        return $url . $query;
    }
}
