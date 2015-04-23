<?php
    class user{

        private $id;

        public function __construct($nid){
            $this->id = $nid;
        }

        public function __set($name,$value){
            global $db;
            if ($this->id != NULL) {
                switch($name){
                    default :
                        $db->query("update users set ".$name."='".$db->real_escape_string($value)."' where (id='".$this->id."')");
                    break;
                }
            }
        }

        public function __get($name){
            global $db;
            if ($this->id != NULL) {
                switch($name){
                    case "id":
                        return $this->id;
                    break;
                    /*
                    case "property":
                        $q=$db->query("select property from users where (id='".$this->id."')");
			            $r=$q->fetch_row();
                        return $r[0];
                    */
                    default:
                        $q=$db->query("select ".$name." from users where (id='".$this->id."')");
			            $r=$q->fetch_row();
                        return $r[0];
                    break;
                }
            }else{
                return NULL;
            }
        }

        public static function username_exists($username){
            global $db;
            $q=$db->query("select count(username) from users where (username='".$username."')");
			$r=$q->fetch_row();
            return $r[0]>0;
        }

        public static function create($username,$password){
            global $db;
            $nid=newguid();
            $db->query("insert into users (username,passowrd) values('".$db->real_escape_string($username)."','".$db->real_escape_string($password)."')");
            return new user($nid);
        }

        public function delete(){
            global $db;
            $db->query("delete from users where id='".$this->id."'");
        }

        public static function login($login,$password){
            global $db;
            
           $q=$con->query("select id,password from users where (login = '".$login."')");
            if($q->num_rows==0){
                return "login_error";
            }else{
                $r=$q->fetch_row();
                if ($password != $r['password']) {
                    return "password_error";
                }
                else{
                    return $r['id']
                }
            }
        }
        
    }
?>
