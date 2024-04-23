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
        'avatarChoice',
        'last_login_time',
        'update_priority_time',
        'available_dblist',
        'preferred_lang',
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

    public function detail_info()
    {
        return $this->hasOne(人員信息::class, '工號');
    } // detail_info
}
