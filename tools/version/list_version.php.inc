<?php
/*
    List of versions to show.

    category => [
        title => command
    ]

*/

$lists['language'] = [
    'PHP5' => [
        'path'    => 'which php',
        'version' => 'php -v',
        ],
    'HHVM' => [
        'path'    => 'which hhvm',
        'version' => 'hhvm --version',
        ],
    'PHP7' => [
        'path'    => 'which php7',
        'version' => 'php7 -v',
        ],
    'Golang' => [
        'path'    => 'echo /usr/local/go/bin/go',
        'version' => '/usr/local/go/bin/go version',
        ],
    'Node.js' => [
        'path'    => 'which node',
        'version' => 'node --version',
        ],
    'Python2' => [
        'path'    => 'which python2.7',
        'version' => '/usr/bin/python2.7 --version',
        ],
    'Python3' => [
        'path'    => 'which python3.6',
        'version' => 'python3.6 --version',
        ],
    'Ruby' => [
        'path'    => 'which ruby',
        'version' => 'ruby -v',
        ],
];

$lists['program'] = [
    'MeCab' => [
        'version' => 'mecab --version',
    ],
    'MeCab dictionary info' => [
        'MeCab Directory info' => 'mecab --dictionary-info',
    ],
    'MeCab-config' => [
        'version' => 'mecab-config --version',
    ],
    'MeCab-config dictionary path' => [
        'dictionary path' => 'mecab-config --dicdir',
    ],
    'MeCab-config dictionaries list' => [
        'List of MeCab dictionaries list' => 'mecab-config --dicdir && ls -la $(echo `mecab-config --dicdir`)',
    ],
    'MeCab-ipadic-NEologd' => [
        'Path of mecab-ipadic-NEologd' => 'echo `mecab-config --dicdir`"/mecab-ipadic-neologd"',
    ],
    'npm' => [
        'version' => 'npm --version',
    ],
    'npm package' => [
        'version' => 'npm ls -g',
    ],
    'pip' => [
        'version' => 'pip --version',
    ],
    'pip package installed' => [
        'version' => 'pip list',
    ],
    'Python package' => [
        'list of Python package' => 'pip freeze',
    ],
    'phpinfo() - PHP5 cli' => [
        'info PHP5' => 'php -r \'phpinfo();\'',
    ],
    'phpinfo() - PHP7 cli' => [
        'info PHP7' => 'php7 -r \'phpinfo();\'',
    ],
    'phpinfo() - HHVM' => [
        'info HHVM' => 'hhvm --info',
    ],
    'Git' => [
        'version' => 'git --version',
    ],
    'RipGrep(rg)' => [
        'version' => 'rg --version',
    ],
    'Go env' => [
        "env value" => '/usr/local/go/bin/go env',
    ],
    'yum' => [
        "version" => 'yum --version',
    ],
    'yum package' => [
        "Version list of installed package" => 'yum list installed',
    ],
];
