<?php
return [
    'vcc_flush_cache' => [
        'path' => '/vcc/flush-cache',
        'target' => \CPSIT\Vcc\Controller\VccBackendController::class . '::flushCacheAction',
    ],
    'vcc_ban_cache' => [
        'path' => '/vcc/ban-cache',
        'target' => \CPSIT\Vcc\Controller\VccBackendController::class . '::banCacheAction',
    ],
];