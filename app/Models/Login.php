<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Login extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = "login";

    protected $primaryKey = 'username';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'priority',
        '姓名',
        '部門',
        'avatarChoice'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
