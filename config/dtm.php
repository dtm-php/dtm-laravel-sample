<?php

declare(strict_types=1);
/**
 * This file is part of DTM-PHP.
 *
 * @license  https://github.com/dtm-php/dtm-client/blob/master/LICENSE
 */
use DtmClient\Constants\Protocol;
use DtmClient\Constants\DbType;

return [
    'protocol' => Protocol::HTTP,
    'server' => '127.0.0.1',
    'barrier_db_type' => DbType::MySQL,
    'barrier_redis_expire' => 7 * 86400,
    'port' => [
        'http' => 36789,
        'grpc' => 36790,
    ],
    'guzzle' => [
        'options' => [
        ],
    ],
    'barrier' => [
        \App\Http\Controllers\TccController::class . '::transAConfirm',
        \App\Http\Controllers\TccController::class . '::transACancel',
        \App\Http\Controllers\TccController::class . '::transBConfirm',
        \App\Http\Controllers\TccController::class . '::transBCancel',
    ]
];
