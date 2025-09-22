<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySkd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pelayanan()
    {
        return $this->belongsTo(Pelayanan::class);
    }
}
