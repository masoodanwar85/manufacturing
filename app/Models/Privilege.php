<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    protected $table = 'privilege';
    protected $primaryKey = 'privilegeID';
    public $timestamps = false;

    protected $fillable = ['moduleID','accessLevelID','privilegeCode','privilegeName'];

	public function accessLevel() {
        return $this->belongsTo('App\Models\AccessLevel','accessLevelID','accessLevelID');
    }

	public function module() {
        return $this->belongsTo('App\Models\Modules','moduleID','moduleID');
    }

    public function rolePrivileges() {
        return $this->hasMany('App\Models\RolePrivilege', 'privilegeID', 'privilegeID');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Roles','rolePrivilege','privilegeID','roleID');
    }
}
