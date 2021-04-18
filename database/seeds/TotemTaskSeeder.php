<?php

use Illuminate\Database\Seeder;

class TotemTaskSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \App\Bundles\Admin\Services\CronTaskManager $manager */
        $manager = app(\App\Bundles\Admin\Services\CronTaskManager::class);

        //

        $manager->createDailyAt('Получить курсы валют', 'currency-course:now', ['12:00', '18:00']);
        $manager->createDailyAt(
            'Получить новости из внешних источников',
            'external-news:fetch',
            ['12:00', '18:00']
        );

        $manager->createDailyAt(
            'Получить погоду за текущий месяц',
            'weather:fetch current-month',
            ['12:00']
        );

        $manager->createDailyAt(
            'Обновить таблицу спам IP',
            'blockip:get-new-ips',
            ['23:00']
        );

        $manager->createDailyAt(
            'Загрузить выходные дни на следующую неделю',
            'holiday:fetch next-week',
            ['23:00']
        );
    }
}
