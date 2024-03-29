<?php

namespace App\Based\Support;

use App\Based\Contracts\ModelWithPriorityInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ModelPrioritiesRefresher
{
    /** @var Collection<ModelWithPriorityInterface> */
    private $allModels;

    /** @var ModelWithPriorityInterface */
    private $currentModel;

    /**
     * ModelPrioritiesRefresher constructor.
     * @param ModelWithPriorityInterface $currentModel
     */
    public function __construct(ModelWithPriorityInterface $currentModel)
    {
        $this->allModels = $currentModel::all();
        $this->currentModel = $currentModel;
    }

    /**
     * @param int $newValue
     */
    public function refresh(int $newValue)
    {
        $parts = $this->createNumberParts($newValue);

        $i = 0;

        $priorities = [];
        foreach ($parts as $priority => $models) {
            /** @var ModelWithPriorityInterface $model */
            foreach ($models as $model) {
                $model->setPriority(++$i);
                $priorities[$i] = $model;
            }
        }

        foreach ($this->reloadArray($priorities) as $model) {
            $model->save();
        }
    }

    /**
     * Создать двумерный массив
     * Ключом которого будет число-приоритет
     *
     * @param int $newValue
     * @return array
     */
    private function createNumberParts(int $newValue): array
    {
        $parts = [];
        foreach ($this->allModels as $model) {
            if ($model->getKey() == $this->currentModel->getKey()) {
                continue;
            }

            $parts[$model->getPriority()][] = $model;
        }

        return $this->addValue($parts, $newValue);
    }

    /**
     * Добавить значение в массив
     *
     * @param array $parts
     * @param int $newValue
     * @return array
     */
    private function addValue(array $parts, int $newValue): array
    {
        $this->prepareNewValue($newValue);

        if (isset($parts[$newValue]) && $this->isNewPriorityMinus($newValue)) {
            array_unshift($parts[$newValue], $this->currentModel);
        } else {
            $parts[$newValue][] = $this->currentModel;
        }

        return $parts;
    }

    /**
     * Подготовить новое значение-приоритет
     *
     * @param int $newValue
     */
    private function prepareNewValue(int &$newValue): void
    {
        if ($newValue > $this->allModels->count()) {
            $newValue = $this->allModels->count();
        }
    }

    /**
     * Стал ли приоритет меньше
     *
     * @param int $newValue
     * @return bool
     */
    private function isNewPriorityMinus(int $newValue): bool
    {
        return ($newValue < $this->currentModel->getPriority());
    }

    /**
     * Перегрузить массив
     *
     * @param array $array
     * @return array<Model>
     */
    private function reloadArray(array $array): array
    {
        $array = array_unique($array);

        ksort($array);

        return $array;
    }
}
