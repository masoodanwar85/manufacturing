<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEquipment extends Model
{
    use HasFactory;
    protected $table = 'customerEquipments';
    protected $primaryKey = 'customerEquipmentID';
    public $timestamps = false;
    protected $fillable = ['customerID','equipmentType','equipmentSerial','createdByUserID'];

	public function customer()
    {
        return $this->hasOne('App\Models\Customer','customerID','customerID');
    }
}
