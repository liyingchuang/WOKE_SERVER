<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/17 0017
 * Time: 上午 11:02
 */
namespace App;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'woke_category';
    protected $primaryKey = 'cat_id';
}