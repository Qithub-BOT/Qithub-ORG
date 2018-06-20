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
        'path'    => 'which go',
        'version' => 'go version',
        'env'     => 'go env',
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
    'Perl' => [
        'path'    => 'which perl',
        'version' => 'perl -V',
        ],
];

$lists['program'] = [
    'Word2Vec' => [
        'path'    => 'which word2vec',
        'version' => 'word2vec',
    ],
    'MeCab' => [
        'version' => 'mecab --version',
    ],
    'MeCab dictionary info' => [
        'MeCab Directory info' => 'mecab --dictionary-info',
    ],
    'MeCab-config' => [
        'version' => 'mecab-config --version',
    ],
    'MeCab-config dictionary info' => [
        'dictionary path' => 'mecab-config --dicdir',
        'List of MeCab dictionaries list' => 'mecab-config --dicdir && ls -la $(echo `mecab-config --dicdir`)',
    ],
    'MeCab-ipadic-NEologd' => [
        'Path of mecab-ipadic-NEologd' => 'echo `mecab-config --dicdir`"/mecab-ipadic-neologd"',
    ],
    'Composer' => [
        'version' => 'composer --version',
    ],
    'npm' => [
        'version' => 'npm --version',
    ],
    'npm package' => [
        'version' => 'npm ls -g',
    ],
    'Perl module' => [
        'Perl module list' => 'find `perl -e \'print "@INC"\'` -name \'*.pm\' -print',
    ],
    'pip' => [
        'version' => 'pip --version',
    ],
    'pip package installed' => [
        'List of pip packages' => 'pip list',
    ],
    'Python package installed' => [
        'list of Python packages' => 'pip freeze',
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
    'Kusanagi' => [
        'version' => 'kusanagi --version',
    ],
    'ls' => [
        'version' => 'ls -la /backup',
    ],
    'RipGrep(rg)' => [
        'version' => 'rg --version',
    ],
    'yum' => [
        "version" => 'yum --version',
    ],
    'yum package installed' => [
        "List of yum packages installed" => 'yum list installed',
    ],
];
