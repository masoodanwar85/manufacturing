<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'userType';
    protected $primaryKey = 'userTypeID';
    public $timestamps = false;

    protected $fillable = ['userType'];

    public function users() {
        return $this->hasMany('App\Models\User', 'userTypeID', 'userTypeID');
    }
}
