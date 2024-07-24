<?php

namespace App\Models;

use App\Models\_Abstracts\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminModel extends Authenticatable implements JWTSubject
{
    public $table = 'admins';

    public $fillable = [
        "email",
        "name",
        "password",
        "status",
        "role"
    ];

    protected $hidden = [
        'password'
    ];

    const STATUS_ACTIVE     = 1;
    const STATUS_UNACTIVE   = 0;
    const STATUS_MAP = [
        self::STATUS_ACTIVE     => 'Active',
        self::STATUS_UNACTIVE   => 'Unactive'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'admin_user', 'admin_id', 'user_id');
    }
}
