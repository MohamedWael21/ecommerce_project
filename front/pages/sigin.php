<?php
include '/var/webroot/myproject2/include/classes.php';


$p = new sign('signin');

$p->header();
?>
<div class="container sign">
     <div class="outer">
         <h1> Signin </h1>
         <form method="POST" enctype='multipart/form-data'>
                    <div class="fields">
                        <label for="name">Username</label>
                        <input type="text" name="Name" id='name' autocomplete="off" required>
                    </div>
                    <div class="fields">
                        <label for="passw">Password</label>
                        <input type="password" name="pass" id='passw' required>
                        <i class="fa fa-eye"></i>
                    </div>
                    <div class="fields">
                        <label for="email">Email</label>
                        <input type="email" name="email" id='email' required>
                    </div>
                    <div class="fields">
                        <label for="fullN">FullName</label>
                        <input type="text" name="full" id='fullN' required>
                    </div>
                    <div class="fields">
                        <label for="img" class='file'>choose your Avater</label>
                        <input type="file" name="image" id='img' class='coustom-file' >
                    </div>
                    <div class="foot">
                    <a href='log.php'>Alerady have account ?</a>
                    <input type='submit' class='btn btn-success' name='submit'>
                    </div>
        </form>
     </div>
</div>




<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

 $c=$p->signin($_POST['Name'],$_POST['pass'],$_POST['email'],$_POST['full'],$_FILES['image']);
  
 if(is_array($c)){
     foreach($c as $value){
         echo "<div class='alert alert-danger'>". $value."</div>";
     }
 }else{
     if($c){
         header("location:log.php");
     }
 }

}
$p->footer();
