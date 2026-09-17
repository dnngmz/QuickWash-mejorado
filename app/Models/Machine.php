<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'status'])]
class Machine extends Model
{
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
