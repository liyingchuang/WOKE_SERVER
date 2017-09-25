<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable,
        Authorizable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'woke_users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *dd   dfasdfa
     * @var array
     */
    protected $fillable = ['user_id', 'user_name', 'mobile_phone', 'password', 'user_rank', 'headimg','parent_id','ec_salt','reg_time','qq_id','union_id','last_login'];
    protected $primaryKey = 'user_id';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];
    
    public function follow() {
          return $this->hasMany('App\Follow', 'user_id','user_id');
    }
    public function follower() {
          return $this->hasMany('App\Follow', 'user_id','user_id');
    }
    public function photos() {
          return $this->hasMany('App\UserPhoto', 'user_id','user_id');
    }
     public function banned() {
          return $this->hasOne('App\UserBanned', 'user_id','user_id');
    }
    public function showoff() {
          return $this->hasOne('App\UserShowOff', 'user_id','user_id');
    }
    
    public function getHeadimgAttribute() {
        $in = strstr($this->attributes['headimg'], 'http');
        if ($in) {
            return $this->attributes['headimg'];
        }
        if (strlen($this->attributes['headimg'])==0) {
             return 'http://woke.jiugubao.com/image/logo2.png';
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['headimg'];
    }
    public function getImageAttribute() {
        $in = strstr($this->attributes['image'], 'clouddn.com/');
        if ($in||empty($this->attributes['image'])) {
            return $this->attributes['image'];
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['image'];
    }
    public function getBgAttribute() {
        $in = strstr($this->attributes['bg'], 'clouddn.com/');
        if ($in||empty($this->attributes['bg'])) {
            return $this->attributes['bg'];
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['bg'];
    }
     public function getFlagAttribute($value) {
        $QINIU_HOST=$_ENV['QINIU_HOST'];
       if($this->attributes['is_v']==1){
             return $QINIU_HOST."/377123001.png"; 
       }
       if($this->attributes['is_v']==2){
           return $QINIU_HOST."/377123002.png"; 
       }
       if($this->attributes['is_v']==3){
           return $QINIU_HOST."/377123003.png"; 
       }
       if($this->attributes['is_v']==4){
         return $QINIU_HOST."/377123004.png";  
       }
       return "";
     }
    
  
}
