<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Login extends Model
{
    use HasFactory;
    protected $table = "login";

    protected $primaryKey = 'username';

    protected $keyType = 'string';

    //return 0
    public $incrementing = false;

    protected $fillable = [
        'username',
        'password',
        'priority',
        '姓名',
        '部門',
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
}
