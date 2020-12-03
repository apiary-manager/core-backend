<?php

namespace App\Bundles\Supply\Contracts;

use App\Http\Responses\ActionResponse;

interface Creator
{
    public function many(array $supplies, array $options = []): ActionResponse;

    public function hasOption(string $name): bool;
}
