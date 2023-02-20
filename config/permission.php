<?php

return [
    'admin' => [
        'master' => ['outlet', 'pelanggan', 'jenis_produk','produk', 'users'],
        'feature' => ['transaksi', 'report']
    ],
    'kasir' => [
        'master' => ['pelanggan'],
        'feature' => ['transaksi', 'report']
    ],
    'owner' => [
        'feature' => ['report']
    ]
];