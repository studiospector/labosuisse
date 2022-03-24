<?php

namespace Caffeina\LaboSuisse\Actions;

use Carbon\Carbon;

trait Utils
{
    public function getTime(): string
    {
        return Carbon::now()->format('H:i:s');
    }
}
