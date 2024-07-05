<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 */
class Client extends BasicModel
{
    use HasFactory;

    protected $guarded = [];
}
