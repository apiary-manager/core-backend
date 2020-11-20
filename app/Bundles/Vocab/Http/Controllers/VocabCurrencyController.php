<?php

namespace App\Bundles\Vocab\Http\Controllers;

use App\Bundles\Vocab\Models\VocabCurrency;
use App\Helper\ModelPrioritiesRefresher\ModelPrioritiesRefresher;
use App\Http\Controllers\Controller;
use App\Http\Responses\ActionResponse;
use App\Models\User\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

final class VocabCurrencyController extends Controller
{
    public const PERMISSIONS = [
        'index' => Permission::VOCAB_BANKS_LIST_VIEW,
        'store' => Permission::VOCAB_CURRENCIES_CREATE,
        'show' => Permission::VOCAB_CURRENCIES_VIEW,
        'update' => Permission::VOCAB_CURRENCIES_EDIT,
        'destroy' => Permission::VOCAB_CURRENCIES_DELETE,
    ];

    /**
     * Отобразить список валют
     *
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function index($page = 1): LengthAwarePaginator
    {
        return VocabCurrency::query()
            ->paginate(10, ['*'], 'VocabCurrenciesList', $page);
    }

    /**
     * Добавить валюту в справочник
     *
     * @param Request $request
     * @return ActionResponse
     */
    public function store(Request $request): ActionResponse
    {
        $currency = $this->createModel($request, VocabCurrency::class);

        (new ModelPrioritiesRefresher($currency))->refresh($request->get('priority'));

        return new ActionResponse($currency->save(), $currency);
    }

    /**
     * Отобразить валюту
     *
     * @param VocabCurrency $vocabCurrency
     * @return VocabCurrency
     */
    public function show(VocabCurrency $vocabCurrency): VocabCurrency
    {
        return $vocabCurrency;
    }

    /**
     * Обновить данные валюты
     *
     * @param Request $request
     * @param VocabCurrency $vocabCurrency
     * @return ActionResponse
     */
    public function update(Request $request, VocabCurrency $vocabCurrency): ActionResponse
    {
        $refresher = new ModelPrioritiesRefresher($vocabCurrency);
        $refresher->refresh($request->get('priority'));

        $isUpdate = $vocabCurrency->update($request->all());

        return new ActionResponse($isUpdate, $vocabCurrency);
    }

    /**
     * Удалить валюту из справочника
     *
     * @param VocabCurrency $vocabCurrency
     * @return ActionResponse
     * @throws \Exception
     */
    public function destroy(VocabCurrency $vocabCurrency): ActionResponse
    {
        return $this->deleteModelAndResponse($vocabCurrency);
    }
}