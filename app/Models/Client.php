<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'client';
    protected $primaryKey = 'clientID';
    public $timestamps = false;
    protected $fillable = ['clientID','firstName','lastName','phone','address','city','state','zipCode','description'];

	public function users()
    {
        return $this->hasMany('App\Models\User','clientID','clientID');
    }
}
