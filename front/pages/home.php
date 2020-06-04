<?php
include '/var/webroot/myproject2/include/classes.php';
session_start();
if (isset($_SESSION['userid']) || isset($_COOKIE['SID'])) {
    $p = new home('Home');
    $p->header();
    $p->navbar();
    echo "<div class='row items'>";
?>

    <?php foreach ($p->getLastItems() as $itd) :  $item = new item($itd['id']) ?>

        <div class="outer col-md-3 col-sm-1">
            <span class='price'>$<?php echo number_format($item->getPrice()); ?> </span>
            <a href="../pages/item.php?itd=<?php echo $item->getId(); ?>&itn=<?php echo $item->getName(); ?>">
                <div class="imgecon"><img src="../lib/images/<?php echo $item->getImg(); ?>" alt="image" class='img-fluid' title="<?php echo $item->getImg(); ?>" /></div>
                <p class='name'><?php echo ucfirst($item->getName()); ?></p>
            </a>
        </div>

    <?php endforeach; ?>
<?php
    echo "</div>";
    $p->footer();
} else {
    echo 'you shoud login first you will redirect after 3 second';
    header("refresh:3; url='log.php'");
}
