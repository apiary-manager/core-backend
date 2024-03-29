<?php

/** @var Document $document */
use App\Bundles\Document\Models\Document;
use App\Bundles\Supply\Models\SupplyProduct;
use App\Services\Document\TemplateService;
use App\Bundles\Supply\Services\SupplyService;
use ArtARTs36\RuSpelling\Month;

$document = $document->load('productTransportWaybills');

/** @var \App\Bundles\Supply\Models\OneTForm $waybill */
$waybill = $document->getOneTForm();

$supply = $waybill->supply;

/** @var SupplyProduct[] $products */
$products = $supply->products;

$plannedDate = new \DateTime($supply->planned_date);

$fullTotalPrice = app(SupplyService::class)->bringTotalPrice($supply);

$data = [
    'ГРУЗОПОЛУЧАТЕЛЬ' => TemplateService::renderContragent($supply->customer),
    'ГРУЗООТПРАВИТЕЛЬ' => TemplateService::renderContragent($supply->supplier),
    'ДЕНЬ' => $plannedDate->format('d'),
    'МЕСЯЦ_Р' => mb_strtolower(Month::getGenitiveName($plannedDate)),
    'ГОД' => $plannedDate->format('Y'),
    'ДАТА' => $plannedDate->format('d.m.Y'),
    'ПОЛНАЯ_СУММА' => TemplateService::sum2words($fullTotalPrice),
    'КОЛВО_ПРОД' => count($products),
    'КОЛВО_ПРОДУКТОВ_ПРОПИСЬЮ' => TemplateService::numberToWord(count($products)),
];

$data['ПЛАТЕЛЬЩИК'] = $data['ГРУЗОПОЛУЧАТЕЛЬ'];
$data['ПОСТАВЩИК'] = $data['ГРУЗООТПРАВИТЕЛЬ'];

$totalQuantity = 0;

foreach ($products as $key => $product) {
    $data['items'][] = [
        'loop' => $key + 1,
        'name' => $product->parent->name,
        'price' => TemplateService::formatNetto($product->price),
        'quantity' => TemplateService::formatNetto($product->quantity),
        'totalPrice' => TemplateService::formatNetto($product->getTotalPrice()),
        'sou' => $product->quantityUnit->short_name,
        'okei' => $product->quantityUnit->okei,
    ];

    $totalQuantity += $product->quantity;
}

$data['ИТОГО_НЕТТО'] = TemplateService::formatNetto($totalQuantity);
$data['СУММА_БЕЗ_НДС'] = TemplateService::formatNetto($fullTotalPrice);
$data['СУММА_С_НДС'] = TemplateService::formatNetto($fullTotalPrice);

return $data;
