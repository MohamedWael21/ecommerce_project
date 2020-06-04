<?php
include '/var/webroot/myproject2/include/classes.php';

$p = new sign('login');

$p->header();
?>
<div class="container log">
   <div class="outer">  
        <h1>Login</h1>
        <form method="POST">
                <div class="user">

                <label for='us'>username</label>
                
                <input type="text" name="user" id="us" required autocomplete='off'>   

                </div>
                <div class="pass">
                
                <label for='pass'>  Password   </label>
                
                <input type="password" name="password" id='pass' required>
                <i class="fa fa-eye"></i>
                
                <label for="checkbox" class='check'>
                        <input type="checkbox" name="remember" id="checkbox" value="yes">
                         Remember me
                </label>
                </div>
                <div class="other">
                <a class='btn btn-success' href="sigin.php">Signin</a>
                <input type='submit' class="btn btn-primary" name="submit" value="Login">
                </div>
        </form>
</div>
</div>

<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

 $ch=$p->login($_POST['user'],$_POST['password']);      

 if($ch)
 {
         if(isset($_POST['remember'])){
               
                  setcookie("SID",$ch,time()+60*60*24*7);

         }
     session_start();
     $_SESSION['userid']=$ch;
     header('location:home.php');
     
     
 } 
 else
 {
         echo "<div class='alert alert-danger'>Wrong username or password </div>";
 }

 }

$p->footer();