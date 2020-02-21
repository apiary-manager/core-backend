@php

use App\Services\Document\TemplateService;

$supply = \App\Services\SupplyService::fullLoadSupply(rand(1, 100));

$data['variables'] = [
    'ЗАКАЗЧИК' => $supply->customer->title,
    'ДАТА' => $supply->planned_date,
    'ПОСТ_НОМЕР' => $supply->id,
    'ОТПРАВИТЕЛЬ' => TemplateService::renderContragent($supply->supplier),
    'СЕРТ_НОМЕР' => rand(999999, 99999999),
    'СЕРТ_ДАТА_НАЧАЛА_ДЕЙСТВИЯ' => (new DateTime())->format('d.m.Y'),
    'СЕРТ_ДАТА_КОНЦА_ДЕЙСТВИЯ' => (new DateTime())->format('d.m.Y'),
    'СЕРТ_ТЕХНОЛОГ' => $supply->supplier->title,
];

$productData = [];
foreach ($supply->products as $key => $product) {
    $productData[] = [
        'ПРОД_Н' => $key + 1,
        'ПРОД_НАЗВАНИЕ' => $product->parent->name_for_document,
        'ПРОД_ТАРА' => 'ПВХ',
        'ПРОД_КОЛВО' => $product->quantity,
        'ПРОД_ВЕС' => $product->parent->size,
        'ПРОД_ГОСТ' => rand(99999, 9999999),
        'ПРОД_МАС_Н' => $product->parent->size,
        'ПРОД_ГОДНОСТЬ' => rand(1, 10) . ' лет',
        'ПРОД_ВЫРАБОТКА' => (new DateTime())->format('d.m.Y'),
        'ПРОД_ХРАНЕНИЕ' => "+ 20 град. С"
    ];
}

$data['tables'] = [
     $productData
];

@endphp

{!! json_encode($data) !!}