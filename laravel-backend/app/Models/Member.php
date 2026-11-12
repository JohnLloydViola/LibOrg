<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $table = 'members';
    public $timestamps = false; // Set to false since table uses native timestamp defaults without updated_at

    protected $fillable = [
        'full_name',
        'email',
        'password_hash',
        'phone_number',
        'address',
        'role',
        'api_token',
    ];

    protected $hidden = [
        'password_hash',
        'api_token',
    ];

    // Eloquent Relationship: A member has many transactions
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'member_id', 'id');
    }
}
