<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'symbol'];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
