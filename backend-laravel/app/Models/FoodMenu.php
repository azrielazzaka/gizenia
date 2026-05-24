<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 

class FoodMenu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'food_menus';
    
    protected $fillable = [
        'name', 
        'description', 
        'serving_size_g', 
        'calories', 
        'protein', 
        'carbohydrates', 
        'fat', 
        'fiber', 
        'vitamin_a',
        'vitamin_c',
        'calcium',
        'iron',
        'sodium',
        'category',    
        'meal_time',   
        'image_url',
        'kaggle_id',
        'cluster_id' // <--- TAMBAHKAN BARIS INI
    ];
}