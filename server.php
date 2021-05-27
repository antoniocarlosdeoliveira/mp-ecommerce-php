<?php
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// Configura credenciais
MercadoPago\SDK::setAccessToken('APP_USR-334491433003961-030821-12d7475807d694b645722c1946d5ce5a-725736327');

// Cria um objeto de preferência
$preference = new MercadoPago\Preference();

// Cria um item na preferência
$item = new MercadoPago\Item();
$item->id = "1234";
$item->title = "Meu produto";
$item->currency_id = "BRL";
$item->description = "Celular de Tienda e-commerce";
$item->category_id = "art";
$item->quantity = 1;
$item->unit_price = 75.76;
$preference->items = array($item);
$preference->save();
?>