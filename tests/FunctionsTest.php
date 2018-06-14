<?php

include_once('vendor/autoload.php');
include_once('.includes/functions.php.inc');

use PHPUnit\Framework\TestCase;

/*  PHP >= ^7.0 は PHPUnit ^6.0 であるため PHP >= ^5.6 の対応バージョン
 *  である PHPUnit 5.7.x の上位互換用の親クラスを継承
 *   ref: https://qiita.com/tanakahisateru/items/ef9f4c9b8ca39e6d0ed8
 *   PHPUnit_Framework_TestCase -> TestCase
 * //class FunctionsTest extends PHPUnit_Framework_TestCase
 */

class FunctionsTest extends TestCase
{

    public function setUp()
    {
        // Warning も確実にエラーとして扱うようにする
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $msg  = 'Error #' . $errno . ': ';
            $msg .= $errstr . " on line " . $errline . " in file " . $errfile;
            throw new RuntimeException($msg);
        });
    }

    /**
     * @dataProvider provideDataTest
     */
    public function testEchoParrotry($arg1, $arg2)
    {
        $result = \Qithub\fn\echoParrotry($arg2, true);
        $this->assertSame($arg1, $result);
    }

    static function provideDataTest()
    {
        return [
            [ 'Hello World', 'Hello World' ],   // #0
            [ 'Hello World!', 'Hello World!' ],   // #1
            [ 'Hello World', 'Hello World' ],   // #2
        ];
    }


}
