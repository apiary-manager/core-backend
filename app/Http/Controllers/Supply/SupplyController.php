<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplyRequest;
use App\Http\Resource\SupplyResource;
use App\Http\Responses\ActionResponse;
use App\Models\Supply\Supply;
use App\Models\User\Permission;
use App\Repositories\SupplyRepository;
use App\Services\SupplyService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplyController extends Controller
{
    public const PERMISSIONS = [
        'index' => Permission::SUPPLIES_VIEW,
        'show' => Permission::SUPPLIES_VIEW,
        'store' => Permission::SUPPLIES_CREATE,
        'update' => Permission::SUPPLIES_EDIT,
        'destroy' => Permission::SUPPLIES_DELETE,
    ];

    /**
     * Получить список поставок
     *
     * @param int $page
     * @return AnonymousResourceCollection
     */
    public function index(int $page = 1): AnonymousResourceCollection
    {
        return SupplyResource::collection(SupplyRepository::paginate($page));
    }

    /**
     * Создать поставку
     *
     * @param SupplyRequest $request
     * @return ActionResponse
     */
    public function store(SupplyRequest $request): ActionResponse
    {
        $supply = $this->makeModel($request, Supply::class);
        $supply->supplier_id = env('ONE_SUPPLIER_ID');
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
    public function show(Supply $supply): SupplyResource
    {
        return new SupplyResource(SupplyRepository::fullLoad($supply));
    }

    /**
     * Обновить данные о поставке
     *
     * @param SupplyRequest $request
     * @param Supply $supply
     * @return ActionResponse
     */
    public function update(SupplyRequest $request, Supply $supply): ActionResponse
    {
        $this->updateModel($request, $supply);

        SupplyService::checkProductsInSupply($request->all());

        return new ActionResponse(true, $supply);
    }

    /**
     * @param Supply $supply
     * @return ActionResponse
     * @throws \Exception
     */
    public function destroy(Supply $supply)
    {
        return $this->deleteModelAndResponse($supply);
    }

    /**
     * @param int $customerId
     * @return ActionResponse
     */
    public function findByCustomer(int $customerId): ActionResponse
    {
        $supplies = SupplyRepository::findByCustomer($customerId);

        return new ActionResponse($supplies->isNotEmpty(), $supplies);
    }
}
