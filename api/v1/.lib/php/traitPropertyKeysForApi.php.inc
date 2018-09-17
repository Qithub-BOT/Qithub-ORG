<?php

// 汎用のため明示的にグローバル名前空間に設置
namespace

{
    trait PropertyKeysForApi
    {
        // デフォルトのプロパティ値
        private $access_token = '';
        private $endpoint     = '';
        private $host         = '';
        private $method       = '';
        private $name_service = '';
        private $name_token   = '';
        private $schema       = DEFAULT_SCHEMA;
        private $spoiler_text = ''; // CW の警告文
        private $status       = ''; // トゥート本体
        private $visibility   = DEFAULT_VISIBILITY;

        // プロパティ値の汎用ゲッター
        public function __get($name_property)
        {
            return $this->$name_property;
        }
    }
}
