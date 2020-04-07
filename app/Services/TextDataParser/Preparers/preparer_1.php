<?php

use App\Models\Contragent;
use App\Models\VariableDefinition;
use App\Services\ScoreForPaymentService;
use App\Services\Supply\SupplyProductService;
use App\Services\SupplyService;
use App\Services\VariableDefinitionService;

/** @var array $items */

$result = [];
$supplier = Contragent::find(env('ONE_SUPPLIER_ID'));

/** @var array $item */
foreach ($items as $item) {
    $customer = Contragent::where('title', $item[1])->first();
    $supply = SupplyService::create($customer, $supplier);

    /** @var \App\Models\Product\Product $product */
    $product = VariableDefinitionService::getModel(VariableDefinition::PRODUCT_ID);
    $supplyProduct = SupplyProductService::makeSupplyProductOfParent($product);
    $supplyProduct->supply_id = $supply->id;
    $supplyProduct->quantity = $item[5];
    $supplyProduct->save();

    $score = ScoreForPaymentService::create($supply->id);

    $result[] = [
        'customer' => $customer,
        'supply' => $supply,
        'supplier' => $supplier,
        'supplyProduct' => $supplyProduct,
        'score' => $score,
    ];
}

return $result;
