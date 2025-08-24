<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
    // MySQL ドライバの設定
    'development' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            'hostname'       => 'localhost',
            'port'           => '3306',
            'database'       => 'my_app_db',
            'username'       => 'root',
            'password'       => 'root',
            'persistent'     => false,
            'compress'       => false,
        ),
        'identifier'   => '`',
        'table_prefix'   => '',
        'charset'        => 'utf8',
        'enable_cache'   => true,
        'profiling'      => false,
    ),
);
