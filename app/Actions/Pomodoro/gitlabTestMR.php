<?php

namespace App\Actions\Pomodoro;

use Lorisleiva\Actions\Concerns\AsAction;

class gitlabTestMR
{
    use AsAction;

    public function handle(bool $isTested): string
    {
        if ($isTested === true) {
            return 'This code should me marked as tested';
        }
        if ($isTested === false) {
            return 'And this part is not tested';
        }
        return 'not tested';
    }
}
