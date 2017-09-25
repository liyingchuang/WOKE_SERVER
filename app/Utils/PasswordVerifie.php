<?php

namespace App\Utils;

use DB;


/**
 * 验证 
 *
 * @author ring
 */
class PasswordVerifier {

    public function verify($username, $password) {
     
        $user = DB::table('ecs_users')->where('mobile_phone', $username)->first();
        if(!empty($user)){
            if(md5(md5($password).$user->ec_salt)!=$user->password){
              return false;
            }
            return $user->user_id;  
           
        }

        return false;
    }

}
