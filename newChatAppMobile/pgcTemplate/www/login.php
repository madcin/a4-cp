<?php
//this makes the $action = to $_POST ['action']as an array
$action = $_POST['action'];
//action function that calls all the cases listed
switch($action){
    //goes through each case until it reaches the
    //end ;show""\
    case 'register':
        register();
        break;
    case 'login':
        login();
        break;
    case 'checkLogin':
        checkLogin();
        break;
    case 'logout':
        logout();
        break;
    case 'send':
        send();
        break;
    case 'show':
        getMessage();
        break;
}
//the register function gets information from
//the sql workbench inserts into the users table
//(username, and password) the Values username and
//password are prepared (the query) then makes a hashpassword
//for the orginal password in the $_POST as an assoc array
function register(){
    require("db.php");
    $con = dbConnect();
    $query= "INSERT INTO users(username,password)VALUES( :username,:password)";
    $stm = $con->prepare($query);
    $hashPass = md5($_POST['password']);
    //here your bind values so no one can do anysql injections
    //binds the fisrt value as a string and the second on as an string also
    $stm->bindvalue(':username', $_POST['username'],PDO::PARAM_STR);
    $stm->bindvalue(':password',$hashPass, PDO::PARAM_STR);
    //executes the statement and if doesnt work it will
    //be alerted as failed.
    if($stm->execute()){
        login();
    }else{
        echo "Failed";
    }
}
//this is the the longest most annoyingest function ever
//does the same thing as register as grabing certain things from certain
//tables and binding to avoid sql injections
function login(){
    require_once('db.php');
    $con = dbConnect();
    $query = "SELECT password,id_user FROM users WHERE username = :username;";
    $stm = $con->prepare($query);
    $stm->bindValue(":username", $_POST['username'],PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);
    $hashpass = md5($_POST["password"]);
    //Compare the strings if its 0 the strings are identical
    //and will alert the user Success!
    if(strcmp($row["password"],$hashpass) == 0){
        echo 'success';
        //to advance further u must always have
        //session_start or nothing will happen
        session_start();
        //it knows when the user logs in because
        //it takes a boolean true or false,true meaning
        //they are logged in! yay!!
        $_SESSION['loggedIn'] = true;
        $_SESSION['id_user'] = $row['id_user'];
    }else{
        echo "failed";
    }
}   
//same sort of idea as SESSION logged in
//only its checking if your logged in
function checkLogin(){
    session_start();
    if(!isset($_SESSION['loggedIn'])){
        echo 'failed';
        return;
    }
    if($_SESSION['loggedIn']){
        echo 'success';
    }else{
        echo "failed";
    }
}
//this logs out the user whenever they want
function logout(){
    session_start();
    unset($_SESSION['loggedIn']);
    echo 'done';
}
//this function sends the messages and binds it.
//Does the same things as the functions above
//only it uses different information on the sql,
//and adds the users name, the times the message was
//sent and datetime.
function send(){
    session_start();
    require('db.php');
    $con = dbConnect();
    if(isset($_POST["message"])){
    $query = "INSERT INTO messages(message,id_sent,datetime)VALUES(:message,:id_sent,NOW());";
    $stm = $con->prepare($query);
    $stm->bindValue(":message",$_POST['message'],PDO::PARAM_STR);
    $stm->bindValue(":id_sent",$_SESSION['id_user'],PDO::PARAM_INT);
    $stm->execute();
    }
}
//This is the get messages function that gets the
//current messages as the query once all the green
//text begins with Select, you open your sql, go to
//your shcemea open it, delete whatevers on the first
//line and copy and paste the "SELECT....DESC", lightening bolt it
//apply, then that should activate getting whatever the
//user wants for messages, it all apears magically and it
//ALSO merges the 2 tables which in this case is "messages" and "users"
function getMessage() {
    session_start();
    require('db.php');
    $con = dbConnect();
    $query= "
        SELECT
            messages.datetime,
            messages.message,
            users.username
        FROM messages
        INNER JOIN users
            ON users.id_user = messages.id_sent
        ORDER BY messages.id_message DESC;";
    //make a statement, prepare it[ as an associate array]
    $stm = $con->prepare($query);
    $stm->execute();
    while( $row=$stm->fetch(PDO::FETCH_ASSOC)){
        echo $row["username"] . ": " . $row ["message"] . "<br>" . explode(" ",$row["datetime"])[1] . "<br><br>";
    }
}

?>