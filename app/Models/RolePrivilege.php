<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePrivilege extends Model
{
    use HasFactory;

    protected $table = 'rolePrivilege';
    protected $primaryKey = ['roleID','privilegeID'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['roleID','privilegeID'];

    public function privilege() {
        return $this->belongsTo('App\Models\Privilege','privilegeID','privilegeID');
    }

    public function role() {
        return $this->belongsTo('App\Models\Roles','roleID','roleID');
    }
}
