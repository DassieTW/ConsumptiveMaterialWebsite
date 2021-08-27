<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
class Login extends Model
{
    use HasFactory;
=======
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Login extends Authenticatable
{
    use HasFactory, Notifiable;

>>>>>>> 0827tony
    protected $table = "login";

    protected $primaryKey = 'username';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

<<<<<<< HEAD
=======
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
>>>>>>> 0827tony
    protected $fillable = [
        'username',
        'password',
        'priority',
        '姓名',
        '部門',
<<<<<<< HEAD
    ];

    /*public function getPasswordAttribute($password)
    {
        return decrypt($password);
    }

    public function getPassAttribute($pass)
    {
        return decrypt($pass);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = encrypt($password);
    }*/
=======
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
>>>>>>> 0827tony
}
