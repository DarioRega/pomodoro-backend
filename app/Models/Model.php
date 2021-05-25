<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as CoreModel;

class Model extends CoreModel
{
    public static function last(): Model
    {
        return self::latest()->first();
    }
}
