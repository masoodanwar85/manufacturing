<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLevel extends Model
{
    use HasFactory;

    protected $table = 'accessLevel';
    protected $primaryKey = 'accessLevelID';
    public $timestamps = false;

    protected $fillable = ['accessLevelID','accessLevel'];

    public function privileges() {
        return $this->hasMany('App\Models\Privilege', 'accessLevelID', 'accessLevelID');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Roles','rolePrivilege','accessLevelID','roleID');
    }
}
