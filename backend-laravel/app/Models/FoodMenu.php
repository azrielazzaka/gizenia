<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Pastikan menggunakan Model bawaan MongoDB Laravel

class FoodMenu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'food_menus';
    
    protected $fillable = [
    'name', 
    'description', 
    'serving_size_g', // Tambahkan berat porsi dalam gram
    'calories', 
    'protein', 
    'carbohydrates', 
    'fat', 
    'fiber', 
    // Tambahkan Mikronutrisi untuk akurasi ML
    'vitamin_a',
    'vitamin_c',
    'calcium',
    'iron',
    'sodium',
    'category',    
    'meal_time',   
    'image_url',
    'kaggle_id'       // Untuk referensi ke dataset asli
];
}