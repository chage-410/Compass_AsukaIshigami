<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories'); // リレーションの定義追記

    }

    // コメント数
    public function commentCounts($post_id)
    {
        return Post::with('postComments')->find($post_id)->postComments();
    }

    // いいね数
    public function likes()
    {
        return $this->hasMany(Like::class, 'like_post_id');
    }
}
