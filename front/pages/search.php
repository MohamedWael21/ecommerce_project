<?php
include '/var/webroot/myproject2/include/classes.php';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    $hint = $_GET['s'];
    $co = page::connect();
    $stm = "SELECT id,name FROM items where name like ? ";
    $hint = "%" . $hint . "%";
    $stmt = $co->prepare($stm);
    $stmt->bindValue(1, $hint, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = json_encode($result);
    echo $result;
} else {

    $p = new page("result");
    $p->header();
    $p->navbar();
    $hint = $_POST['search'];
    $co = page::connect();
    $stm = "SELECT id FROM items where name like ? ";
    $hint = "%" . $hint . "%";
    $stmt = $co->prepare($stm);
    $stmt->bindValue(1, $hint, PDO::PARAM_STR);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="container">
        <div class="row items">
            <?php foreach ($items as $it) : $item = new item($it['id']); ?>

                <div class="outer col-md-3 col-sm-1">
                    <span class='price'>$<?php echo number_format($item->getPrice()); ?> </span>
                    <a href="../pages/item.php?itd=<?php echo $item->getId(); ?>&itn=<?php echo $item->getName(); ?>">
                        <div class="imgecon"><img src="../lib/images/<?php echo $item->getImg(); ?>" alt="image" class='img-fluid' title="<?php echo $item->getImg(); ?>" /></div>
                        <p class='name'><?php echo ucfirst($item->getName()); ?></p>
                    </a>
                </div>

            <?php endforeach;  ?>

        </div>
    </div>







<?php
    $p->footer();
}
