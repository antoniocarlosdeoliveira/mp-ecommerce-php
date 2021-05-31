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
$item->description = "Certificação Checkout-Pro";
$item->category_id = "art";
$item->quantity = 1;
$item->unit_price = 75.76;
$preference->items = array($item);
$preference->save();


// Cria um payer na proferência
$payer = new MercadoPago\Payer();
$payer->name = "Lalo";
$payer->surname = "Landa";
$payer->email = "test_user_92801501@testuser.com";
$payer->phone = array(
    "area_code" => "55",
    "number" => "98529-8743"
);
$payer->identification = array(
    "type" => "CPF",
    "number" => "19119119100"
 );

$payer->address = array(
    "street_name" => "Insurgentes Sur",
    "street_number" => 1602,
    "zip_code" => "78134-190"
);

$preference->back_urls = array(
    "success" => "https://mercadolivre.com",
    "failure" => "https://checkout-pro.herokuapp.com",
    "pending" => "https://checkout-pro.herokuapp.com"
);
$preference->auto_return = "approved";

$preference->payment_methods = array(
	"excluded_payment_methods" = [
		{
			"id" => "amex"
		}
	],
	"excluded_payment_types" = [
		{
			"id" = "ticket"
		}
	],
	"installments" = 6
);

$preference->notification_url = "https://webhook.site/cc8077f9-4185-471e-85c0-734f744298c7";
$preference->statement_descriptor = "MEUNEGOCIO";
$preference->external_reference = "livegames2011@hotmail.com";

MercadoPago\SDK::setIntegratorId("dev_24c65fb163bf11ea96500242ac130004");

$merchant_order = null;

	switch($_GET["topic"]) {
		case "payment":
			$payment = MercadoPago\Payment::find_by_id($_GET["id"]);
			// Get the payment and the corresponding merchant_order reported by the IPN.
			$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
			break;
		case "merchant_order":
			$merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
			break;
	}

	$paid_amount = 0;
	foreach ($merchant_order->payments as $payment) {	
		if ($payment['status'] == 'approved'){
			$paid_amount += $payment['transaction_amount'];
		}
	}
	
	// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
	if($paid_amount >= $merchant_order->total_amount){
		if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
			if($merchant_order->shipments[0]->status == "ready_to_ship") {
				print_r("Totally paid. Print the label and release your item.");
			}
		} else { // The merchant_order don't has any shipments
			print_r("Totally paid. Release your item.");
		}
	} else {
		print_r("Not paid yet. Do not release your item.");
	}

?>