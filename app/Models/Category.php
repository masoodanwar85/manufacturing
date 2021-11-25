<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $primaryKey = 'categoryID';
    public $timestamps = false;
    protected $fillable = ['categoryName','createdByUserID'];

    public function products()
    {
        return $this->hasMany('App\Models\Product','categoryID','categoryID');
    }
}
