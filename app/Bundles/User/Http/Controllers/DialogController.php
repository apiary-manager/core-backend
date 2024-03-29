<?php

namespace App\Bundles\User\Http\Controllers;

use App\Bundles\User\Http\Resources\DialogsListResource;
use App\Bundles\User\Repositories\DialogRepository;
use App\Bundles\User\Services\DialogService;
use App\Bundles\User\Http\Resources\DialogResource;
use App\Bundles\User\Models\Dialog;
use App\Based\Contracts\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

final class DialogController extends Controller
{
    private $repository;

    private $service;

    public function __construct(DialogRepository $repository, DialogService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @tag Dialog
     */
    public function index(int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($page);
    }

    /**
     * Получить диалоги текущего пользователя
     * @tag Dialog
     * @return AnonymousResourceCollection<DialogsListResource>
     */
    public function user(int $page = 1): AnonymousResourceCollection
    {
        return DialogsListResource::collection($this->repository->findByUser(auth()->user(), $page));
    }

    /**
     * Получить диалог
     * @tag Dialog
     */
    public function show(Dialog $dialog, int $page = 1): DialogResource
    {
        return new DialogResource(...$this->service->show($dialog, $page));
    }

    /**
     * Удалить диалог
     * @tag dialog
     */
    public function destroy(Dialog $dialog): Dialog
    {
        return $dialog->hide(Auth::user());
    }
}
