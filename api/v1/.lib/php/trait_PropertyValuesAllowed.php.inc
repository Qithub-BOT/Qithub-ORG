<?php

// 汎用のため明示的にグローバル名前空間に設置
namespace

{
    trait PropertyValuesAllowed
    {
        // 設定可能なプロパティ値
        private $allow = [
            'schema'     => ['http','https'],
            'method'     => ['GET','POST','PATCH','PUT','DELETE'],
            'host'       => ['qiitadon.com','qiita.com','github.com'],
            'visibility' => ['public', 'unlisted', 'private', 'direct'],
            'endpoint'   => [
                '/api/v1/statuses',
            ],
        ];
    }
}
