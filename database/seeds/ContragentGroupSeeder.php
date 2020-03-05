<?php

use App\Models\Contragent;
use App\Models\Contragent\ContragentGroup;

class ContragentGroupSeeder extends CommonSeeder
{
    public function run()
    {
        if (env('ENV_TYPE') == 'dev') {
            $this->randomData(20);
        }
    }

    private function randomData(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $group = new ContragentGroup();
            $group->name = $this->getFaker()->name;
            $group->save();

            $group->contragents()->attach(
                $this->getRelations(Contragent::class, 25)
            );
        }
    }
}