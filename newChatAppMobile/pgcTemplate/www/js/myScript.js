
//this here gives the variable the
//login.php file
var server = "http://192.168.0.13/a4-cp/newChatAppMobile/login.php";
//this is the register function, that alerts
//the user if they dont type into the username
//(considering you have already registered)
function register(){
    if ($('#regUser').val() == " ") {
       alert("Enter your username, please");
       return;
    }
    //just makes sure the user puts in a password
    if ($('#regPass').val() == " "){
        //also alerts if they didnt enter anything
        alert("Put in your password, please!");
        return;
    }
    //check to see if the passwords match
    if ($('#regPass').val() == $('#regConf').val()){
         var dataToSend = {
         action: "register",
         username: $('#regUser').val(),
         password: $('#regPass').val()
         }
         //i think this grabs your login.php file, sends your
         //data to the function datatosend(), then takes the
         //response of the password and makes sure it matches
         $.post(server,dataToSend,registerResponse);
    }else{
        alert("Please put your password in correctly.");
    }
}
//this function registers the reponse saves it as response
//then alerts the response and checkLogin, register is definitely
//the longest function, and the bottom response goes with
//the register function
function registerResponse(response){
     if (response == 'Your Registered!') {
       $.mobile.navigate("#login"); 
     }else{
        alert("registration failed");
     }
     
     alert(response);
     checkLogin();
}
//the start of the login function that makes
//the login button actually work
function login(){
    if ($('#username').val()=="") {
       alert("You need a username, silly!");
       return;
    }
    if ($('#password').val()=='') {
        alert("Please enter your password.");
        return;
    }
    var dataTosend = {
        action: "login",
        username: $('#username').val(),
        password: $('#password').val()
    }
    $.post(server,dataTosend,loginResponse);
}

function loginResponse(response) {
    if (response=='Your logged in!') {
        $.mobile.navigate("#message")
        $('#username').val('');
        $('#password').val('');  
    }
}
//when the page is loaded it calls checkLogin
$(document).ready(checkLogin);

function checkLogin() {
    var dataToSend = {
        action: "checkLogin"    
    }
    $.post(server,dataToSend,loginResponse);
}
//this is the log our function so it would work
function logout(){
    var dataToSend = {
        action: "logout"
    }
    $.post(server, dataToSend, logoutResponse);
}
//stores the response Recieved
function logoutResponse(response){
    $.mobile.navigate("#login");
}
//this is the SEND function and a tricky one at that
 function send(){
    var dataToSend = {
        action: "send",
        message: $('#messageBox').val()
    }
    //this is a box where text gets typed
    $('#messageBox').val('');
    $.post(server,dataToSend,responseRecieved);
}
//the message area is the entire chat  screen 
function responseRecieved(res){
   $('#messageArea').html(res);nd
}
//gets the messagwe and keeps it stores the data
function getMessage() {
 var dataToSend = {
     action: "show"
 }
    $.post(server,dataToSend,responseRecieved);
 
}
//sets intervals for get meaages 10000 of a second? right?
setInterval(getMessage,1000);
//gets everything ready(the functions)
$(document).ready(function(){
   $('#message').keyup(function(ev){
       if (ev.keyCode==13){
           send();
        }
     })
});
//logs user out when window is closed
window.onbeforehand = function (e) {
    if ((window.event.clientY < 0)) {
        logout();
    }
};
