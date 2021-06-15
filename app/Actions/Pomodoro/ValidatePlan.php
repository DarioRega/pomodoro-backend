<?php


namespace App\Actions\Pomodoro;

use App\Exceptions\NotSubscribedException;
use Auth;

trait ValidatePlan
{
    /**
     * @throws NotSubscribedException
     */
    public function userIsSubscribed($errorMessage)
    {
        if (!Auth::user()->subscribed()) {
            throw new NotSubscribedException($errorMessage);
        }
    }
}
