<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends BaseModel
{
    use HasFactory;

    public function votable()
    {
        return $this->morphTo();
    }
}
