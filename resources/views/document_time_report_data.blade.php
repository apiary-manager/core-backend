@php

    use App\Models\ControlTime\TimeReport;
    use Carbon\Carbon;use Dba\ControlTime\Services\TimeService;

    $timeReport = TimeReport::query()
        ->with(TimeReport::RELATION_EMPLOYEE)
        ->where(TimeReport::FIELD_DOCUMENT_ID, $document->id)
        ->first();

    $times = TimeService::getByPeriod(
        $timeReport->employee,
        Carbon::parse($timeReport->start_date),
        Carbon::parse($timeReport->end_date)
    );

    $workConditions = $timeReport->employee->getCurrentWorkConditions();

     //

    $hours = 0;

    $timesData = [];
    foreach ($times as $key => $time) {
        $timesData[] = [
            'СПИСАНИЕ_ДАТА' => $time->date,
            'СПИСАНИЕ_ЧАСЫ' => $time->getHours(),
            'СПИСАНИЕ_КОММЕНТ' => $time->comment,
        ];

        $hours += $time->getHours();
    }

    //

    $timeReport->times_quantity = $hours;
    $timeReport->save();

    //

    $data['variables'] = [
        'ЧАСЫ_КОЛВО' => $hours,
        'СОТРУДНИК_ПРЕДСТАВЛЕНИЕ' => $timeReport->employee->getFullName(),
        'ПЕРИОД_НАЧАЛО' => $timeReport->start_date,
        'ПЕРИОД_КОНЕЦ' => $timeReport->end_date,
        'ИТОГО_ОПЛАТА' => $hours * $workConditions->amount_hour,
    ] + getEmployeeData($timeReport->employee, $workConditions);

    if ($times->isEmpty()) {
        $timesData[] = [
            'СПИСАНИЕ_ДАТА' => "Нет данных",
            'СПИСАНИЕ_ЧАСЫ' => "Нет данных",
            'СПИСАНИЕ_КОММЕНТ' => "Нет данных",
        ];
    }

    $data['tables'] = [
         $timesData
    ];

    function getEmployeeData(App\Models\Employee\Employee $employee, \Dba\ControlTime\Models\WorkCondition $conditions)
    {
        return [
            'СОТРУДНИК_ДАТА_ПРИНЯТИЯ' => $employee->hired_date,
            'СОТРУДНИК_ДОЛЖНОСТЬ' => $conditions->position ?? "не указана",
            "СОТРУДНИК_СТАВКА" => $conditions->rate ?? "не указана",
            "СОТРУДНИК_ОПЛАТА_ЧАС" => $conditions->amount_hour ?? "не указана",
            "СОТРУДНИК_ОПЛАТА_МЕСЯЦ" => $conditions->amount_month ?? "не указана",
        ];
    }

@endphp

{!! json_encode($data) !!}
