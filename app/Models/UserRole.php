<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'userRole';
    protected $primaryKey = ['userID','roleID'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['userID','roleID'];
}
