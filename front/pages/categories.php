<?php
include '/var/webroot/myproject2/include/classes.php';
$p = new categories('categories');
$p->header();
$p->navbar();
?>
<div class="cat">
    <div class="outer">
        <a href="../pages/categories.php" >All</a>
    </div>
 <?php foreach( $p->getALLCat() as $cat):?>
    <div class="outer">
        <a href="../pages/categories.php?itd=<?php echo $cat['id'];?>">    
            <span class="catspan"> 
                <?php echo $cat['name']; ?> 
            </span>
        </a>
    </div>

 <?php endforeach; ?>  
</div>

<?php
if($_SERVER['REQUEST_METHOD']=='GET'){

    $items= isset($_GET['itd'])? $p->getItemAtCat($_GET['itd']) : $p->getItems();
      
     echo "<div class='row items'>";
    foreach($items as $it): $item = new item($it['id']);?>
            <div class="outer col-md-3 col-sm-1">
                 <span class='price'>$<?php echo number_format($item->getPrice()) ; ?> </span>
                 <a href="../pages/item.php?itd=<?php echo $item->getId();?>&itn=<?php echo $item->getName();?>">
                    <div class="imgecon"><img src="../lib/images/<?php echo $item->getImg(); ?>" alt="image" class='img-fluid' title="<?php echo $item->getImg(); ?>"/></div>
                    <p class='name'><?php echo ucfirst( $item->getName() ); ?></p>
                 </a>
            </div>
       
    <?php endforeach;  
    echo '</div>';    
}
$p->footer();
