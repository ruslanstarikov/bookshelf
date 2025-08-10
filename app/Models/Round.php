<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function turns()
    {
        return $this->hasMany(Turn::class);
    }
}
