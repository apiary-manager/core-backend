<?php

namespace App\Bundles\ExternalNews\Http\Controllers;

use App\Bundles\ExternalNews\Http\Requests\UpdateRequest;
use App\Bundles\ExternalNews\Contracts\ExternalNewsRepository;
use App\Based\Contracts\Controller;
use App\Based\Http\Responses\ActionResponse;
use App\Bundles\ExternalNews\Models\ExternalNews;
use App\Bundles\User\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExternalNewsController extends Controller
{
    public const PERMISSIONS = [
        'index' => Permission::EXTERNAL_NEWS_LIST_VIEW,
        'show' => Permission::EXTERNAL_NEWS_VIEW,
        'update' => Permission::EXTERNAL_NEWS_EDIT,
        'store' => Permission::EXTERNAL_NEWS_CREATE,
        'destroy' => Permission::EXTERNAL_NEWS_DELETE,
    ];

    private $repository;

    public function __construct(ExternalNewsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Отобразить новости из внешних источников
     * @tag ExternalNews
     */
    public function index(int $page = 1)
    {
        return $this->repository->paginate($page);
    }

    /**
     * Отобразить несколько последних новостей из внешних источников
     * @tag ExternalNews
     */
    public function chart(int $count = 6): LengthAwarePaginator
    {
        return ExternalNews::with('source')
            ->latest()
            ->paginate($count);
    }

    /**
     * Display the specified resource.
     *
     * Показать новость
     * @tag ExternalNews
     */
    public function show(ExternalNews $externalNews)
    {
        return $externalNews;
    }

    /**
     * Обновить новость
     * @tag ExternalNews
     */
    public function update(ExternalNews $externalNews, UpdateRequest $request): ActionResponse
    {
        return $this->updateModelAndResponse($request, $externalNews);
    }

    /**
     * Удалить новость
     * @tag ExternalNews
     */
    public function destroy(ExternalNews $externalNews): ActionResponse
    {
        return new ActionResponse($externalNews->delete() > 0);
    }

    /**
     * Удалить все новости
     * @tag ExternalNews
     */
    public function truncate(): ActionResponse
    {
        ExternalNews::query()->truncate();

        return new ActionResponse(true);
    }
}
