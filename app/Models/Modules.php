<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    use HasFactory;

    protected $table = 'modules';
    protected $primaryKey = 'moduleID';
    public $timestamps = false;

    protected $fillable = ['moduleID','moduleCode','moduleName'];

    public function privileges() {
        return $this->hasMany('App\Models\Privilege', 'moduleID', 'moduleID');
    }
}
