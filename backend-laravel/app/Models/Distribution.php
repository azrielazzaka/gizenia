<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Distribution extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'distributions';
    
    protected $fillable = [
        'distribution_date', 
        'foods',           // Akan berisi array: [{menu_id, name, weight}, ...]
        'target_classes',  // Akan berisi array: ['Kelas 1', 'Kelas 2', ...]
        'responses'        // Akan menampung jawaban 'Ya/Tidak' dari user nanti
    ];
}