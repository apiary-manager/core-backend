<?php

namespace App\Bundles\Contragent\Http\Controllers;

use App\Bundles\Contragent\Support\Finder;
use App\Models\Contragent\ContragentManager;
use App\Http\Requests\ContragentRequest;
use App\Http\Responses\ActionResponse;
use App\Models\Contragent;
use App\Http\Controllers\Controller;
use App\Models\Sync\SyncWithExternalSystemType;
use App\Models\User\Permission;
use App\Bundles\Contragent\Repositories\ContragentRepository;
use App\Services\ContragentService;
use App\Services\SyncWithExternalSystemService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ContragentController extends Controller
{
    public const PERMISSIONS = [
        'index' => Permission::CONTRAGENTS_LIST_VIEW,
        'store' => Permission::CONTRAGENTS_CREATE,
        'show' => Permission::CONTRAGENTS_VIEW,
        'update' => Permission::CONTRAGENTS_EDIT,
        'destroy' => Permission::CONTRAGENTS_DELETE,
        'findInExternalNetworkByInn' => Permission::CONTRAGENTS_FIND_EXTERNAL_SYSTEM,
    ];

    private $repository;

    public function __construct(ContragentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Отобразить список контрагентов
     *
     * @OA\Get(
     *     path="/api/contragents/page-{page}",
     *     description="Contragents: index Page",
     *     tags={"Contragent Actions"},
     *     @OA\Parameter(
     *      name="page",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="int")
     *     ),
     *     @OA\Response(response="default", description="View Contragents")
     * )
     *
     */
    public function index(int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($page);
    }

    public function store(ContragentRequest $request): ActionResponse
    {
        return $this->createModelAndResponse($request, Contragent::class);
    }

    public function show(Contragent $contragent): ActionResponse
    {
        return new ActionResponse(true, ContragentService::getFullInfo($contragent));
    }

    /**
     * Обновить данные о контрагенте
     */
    public function update(ContragentRequest $request, Contragent $contragent)
    {
        $this->updateModel($request, $contragent);

        ContragentService::updateScoresInRequisiteByRequest($request);

        return new ActionResponse(true, $contragent);
    }

    /**
     * Удалить контрагента
     *
     * @OA\Delete(
     *     path="/api/contragents/{id}",
     *     description="Contragents: delete item",
     *     tags={"Contragent Actions"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true
     *     ),
     *     @OA\Response(response="default", description="Contragents: delete item")
     * )
     */
    public function destroy(Contragent $contragent): ActionResponse
    {
        $contragent->delete();

        return new ActionResponse(true);
    }

    /**
     * Поиск контрагента во внешней системе
     *
     * @param int $inn
     * @return ActionResponse
     *
     * @OA\Get(
     *     path="/api/contragents/find-external-by-inn/{inn}",
     *     description="Contragents: find Contragent in external System",
     *     tags={"Contragent Actions"},
     *     @OA\Parameter(
     *      name="inn",
     *      in="path",
     *      required=true
     *     ),
     *     @OA\Response(response="default", description="Contragents: find Contragent in external System")
     * )
     */
    public function findInExternalNetworkByInn($inn, Finder $finder, ContragentRepository $repository): ActionResponse
    {
        if ($contragent = $repository->findByInnOrOgrn($inn)) {
            return new ActionResponse(true, [
                'message' => 'Контрагент '. $contragent->title . ' уже существует в базе',
                'contragent' => $contragent,
            ]);
        }

        $contragent = $finder->findByInnOrOgrn($inn)->first();

        return new ActionResponse(true, [
            'message' => 'Контрагент '. $contragent->title . ' найден!',
            'contragent' => $contragent,
        ]);
    }

    /**
     * Синхронизировать контрагента с данными из внешней системы
     */
    public function syncWithExternalSystem(Contragent $contragent, Finder $finder): array
    {
        $response = $finder->findByInnOrOgrn($contragent->inn ?? $contragent->ogrn, false);

        return (new SyncWithExternalSystemService($contragent, SyncWithExternalSystemType::SLUG_CONTRAGENT_DADATA))
            ->create($response)
            ->getComparedData();
    }

    /**
     * Живой поиск контрагента в базе
     *
     * @param string $term
     * @return ActionResponse
     *
     * @OA\Get(
     *     path="/api/contragents/live-find/{term}",
     *     description="Contragents: live find in Base",
     *     tags={"Contragent Actions"},
     *     @OA\Parameter(
     *      name="inn",
     *      in="path",
     *      required=true
     *     ),
     *     @OA\Response(response="default", description="Contragents: live find in Base")
     * )
     */
    public function liveFind(string $term)
    {
        $contragents = Contragent::where('title', 'LIKE', "%{$term}%")
            ->orWhere('full_title', 'LIKE', "%{$term}%")
            ->orWhere('full_title_with_opf', 'LIKE', "%{$term}%")
            ->orWhere('inn', 'LIKE', "%{$term}%")
            ->orWhere('kpp', 'LIKE', "%{$term}%")
            ->get()
            ->all();

        return new ActionResponse(count($contragents) > 0, $contragents);
    }
}