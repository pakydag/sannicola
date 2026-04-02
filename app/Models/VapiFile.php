<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VapiFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vapi_file_id',
        'path',
        'size',
    ];
}
