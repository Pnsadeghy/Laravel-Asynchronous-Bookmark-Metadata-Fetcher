<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'url',
        'title',
        'description'
    ];
}
