<?php

namespace App\Models;

use App\Enums\IdTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'id_type',
        'phone_number',
        'date_of_birth',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'id_type'       => IdTypeEnum::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}