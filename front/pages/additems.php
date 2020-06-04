<?php
include '/var/webroot/myproject2/include/classes.php';
session_start();
$usid = isset($_SESSION['userid']) ? $_SESSION['userid']:$_COOKIE['SID'];
$p=new add('AddItem',$usid);
$p->header();
$p->navbar();
?>
<div class="container add">
    <div class="outer">
    <h1>Add Item</h1>   
    <form method='POST' enctype="multipart/form-data">
       <div class="itemname row">
            <label for="itemN" class="col-md-3">Name</label>
            <input type="text"  name="itemname" class="col-md-9" id='itemN' autocomplete="off" required>
       </div>
       <div class="itemprice row">
            <label for="itemP" class="col-md-3">Price</label>
            <input type="text"    name="itemprice" class="col-md-9" id=itemP  autocomplete="off" required>
       </div>
       <div class="itemcat row">
         <label for="imageC" class="col-md-3"> Category</label>
           <select name="itemcat" id="imageC" class="col-md-9" required>
               <option value="0">...</option>
                <?php foreach( $p->getcat() as $arr){?>
                    <option value="<?php echo $arr['id'];?>"> <?php echo $arr['name'];?> </option>
                <?php }?>
           </select>
       </div>
       <div class="itemimage row">
           <span class="col-md-3">Image</span>
            <label for="itemI" class="col-md-9 btn btn-primary">choose Image</label>
            <input type="file"   name="itemiamge"  id=itemI>
       </div>
       <div class="itemaddbtn">
           <input type="submit" class="btn btn-success" value="Add">
       </div>    
    </form>
    </div>
</div>

<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

   $check = $p->additem($_POST['itemname'],$_POST['itemprice'],$_POST['itemcat'],$_FILES['itemiamge']);

   if($check===true){
       echo "<div class = 'alert alert-success'>Sucess</div>";
   }else{
       foreach($check as $arr){
        echo "<div class = 'alert alert-danger'>$arr</div>";
       }
   }
}
$p->footer();
