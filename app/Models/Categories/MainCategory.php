<?php

namespace App\Models\Categories;

use App\Models\Categories\SubCategory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories()
    {
        return $this->hasMany(\App\Models\Categories\SubCategory::class); // リレーションの定義追記

    }
}
