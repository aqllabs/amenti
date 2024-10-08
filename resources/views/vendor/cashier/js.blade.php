<?php

$seller = array_filter([
    'pwAuth' => (int) config('cashier.retain_key'),
]);

if (config('cashier.client_side_token')) {
    $seller['token'] = config('cashier.client_side_token');
} elseif (config('cashier.seller_id')) {
    $seller['seller'] = (int) config('cashier.seller_id');
}?>
