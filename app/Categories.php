<?php namespace App;
use Illuminate\Database\Eloquent\Model;
// instance of Posts class will refer to posts table in database
class Categories extends Model {
    //restricts columns from modifying
    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany('App\Posts','category_id');
    }
}