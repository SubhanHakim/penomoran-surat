<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    protected $fillable = ['nama'];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
