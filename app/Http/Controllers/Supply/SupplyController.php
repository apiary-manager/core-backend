<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplyRequest;
use App\Http\Resource\SupplyResource;
use App\Http\Responses\ActionResponse;
use App\Models\Supply\Supply;
use App\Services\SupplyService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplyController extends Controller
{
    /**
     * Получить список поставок
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $supplies = Supply::with(['customer', 'products'])->paginate(10);

        return SupplyResource::collection($supplies);
    }

    /**
     * Создать поставку
     *
     * @param SupplyRequest $request
     * @return ActionResponse
     */
    public function store(SupplyRequest $request)
    {
        $supply = new Supply();
        $supply->supplier_id = env('ONE_SUPPLIER_ID');
        $supply->fill($request->all());
        $supply->save();

        SupplyService::checkProductsInSupply($request, $supply->id);

        return new ActionResponse(true, $supply);
    }

    /**
     * Открыть поставку
     *
     * @param Supply $supply
     * @return SupplyResource
     */
    public function show(Supply $supply)
    {
        return new SupplyResource($supply->load([
            'products' => function ($query) {
                return $query->with('parent');
            }
        ]));
    }

    /**
     * Обновить данные о поставке
     *
     * @param SupplyRequest $request
     * @param Supply $supply
     * @return ActionResponse
     */
    public function update(SupplyRequest $request, Supply $supply)
    {
        $requestData = $request->all();
        $supply->update($requestData);

        SupplyService::checkProductsInSupply($requestData);

        return new ActionResponse(true, $supply);
    }

    /**
     * @param Supply $supply
     */
    public function destroy(Supply $supply)
    {
        $supply->delete();
    }
}