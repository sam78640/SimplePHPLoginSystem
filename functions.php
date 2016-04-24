<?php
//Connection to database Function
function database_connection($hostname,$username,$password,$database){
    mysql_connect($hostname,$username,$password);
    mysql_select_db($database);
}
//Checking User Login
function login_user($username,$password,$usertable){
    $pass_to_md5 = md5($password) //Convert password to md5
    $sql = "SELECT * FROM ".$usertable." WHERE username='$username' AND password='$pass_to_md5'";
    $results = mysql_query($sql); //Storing The Results of the query
    $count = mysql_num_rows($results); // It will return 1 if user login is successful
    if ($count == 1){
        return true;
    }
    else{
        return false;
    }
}
//Generate Activation Code
function activation_code(){
    return md5(time());
}
//Email user their activation code
function mail_activation_code($username,$email,$activation_code){
    $message = "Dear $username\n Here is you activation code: ".$activation_code;
    mail($email, 'Activation Code', $message);
}

//User Registration
function register_user($username,$password,$email,$usertable){
    $pass_to_md5 = md5($password);
    $activation_code = activation_code();
    $sql = "INSERT INTO ".$usertable." (username,password,email,activation_code,activated) VALUES ('$username','$pass_to_md5','$email','$activation_code','0')";
    mysql_query($sql);
    mail_activation_code($username,$email,$activation_code);
    return true;
}
//Checking Activation Code
function check_activation_code($activation_code,$usertable){
    $sql = "SELECT * FROM ".$usertable." WHERE activation_code='$activation_code'";
    $results = mysql_query($sql);
    $rows = mysql_num_rows($results); //This will return 1
    if ($rows == 1){
        $update_activated = "UPDATE ".$usertable." SET activated='1' WHERE activation_code='$activation_code'";
        mysql_query($update_activated);
        return true;
    }
    else{
        return false;
    }

}
