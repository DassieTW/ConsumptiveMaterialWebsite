<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationManagement extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "authorization_management";

    protected $primaryKey = ['authorization','priority'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        'authorization',
        'priority',
    ];
}
