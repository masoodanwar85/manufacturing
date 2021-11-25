<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'roleID';
    public $timestamps = false;

    protected $fillable = ['roleID','roleName','description'];

    public function users() {
        return $this->belongsToMany('App\Models\User','userRole','roleID','userID');
    }

	public function rolePrivileges() {
        return $this->hasMany('App\Models\RolePrivilege','roleID','roleID');
    }

    public function privileges() {
        return $this->belongsToMany('App\Models\Privilege','rolePrivilege','roleID','privilegeID');
    }

    public function accessLevels() {
        return $this->belongsToMany('App\Models\AccessLevel','rolePrivilege','roleID','roleID');
    }
}
