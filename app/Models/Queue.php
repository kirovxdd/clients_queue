<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $client_id
 * @property int $created_at
 */
class Queue extends BasicModel
{
    use HasFactory;

    public const FIELD_CLIENT_ID  = 'client_id';
    public const FIELD_CREATED_AT = 'created_at';

    public $timestamps = false;

    protected $guarded = [];

    private ?int $clientPosition = null;

    public function getClientPosition(): int
    {
        if ($this->clientPosition !== null) {
            return $this->clientPosition;
        }

        $this->clientPosition = self::query()->orderBy(self::FIELD_CREATED_AT)->where(self::FIELD_CREATED_AT, '<=', $this->created_at)->count();

        return $this->clientPosition;
    }
}
