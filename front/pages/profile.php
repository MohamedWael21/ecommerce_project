<?php
include '/var/webroot/myproject2/include/classes.php';
session_start();
$usid = isset($_SESSION['userid']) ? $_SESSION['userid'] : $_COOKIE['SID'];
if ($usid) :
    $user = new user($usid);
    $p =  new page($user->getName());
    $p->header();
    $p->navbar();
?>
    <div class="container profile">
        <div class="user">
            <div class="row">
                <div class="userimg col-md-2">
                    <div class="img">
                        <img src="../lib/images/<?php echo $user->getImg(); ?>" alt="IMAGE" class="img-fluid" title=<?php echo $user->getName(); ?>>
                    </div>
                </div>
                <div class="userinfo col-md-10">
                    <div class="info">
                        Name: <?php echo $user->getName(); ?>
                    </div>
                    <div class="info">
                        Email: <?php echo $user->getEmail(); ?>
                    </div>
                    <div class="info">
                        FullName: <?php echo $user->getFullname(); ?>
                    </div>
                    <div class="info">
                        ItemsNO: <?php echo $user->getUserItemsNO(); ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="userItems">
            <h1> Your Items </h1>
            <div class="row">
                <?php if (!empty($user->getUserItems())):?>
                    <?php foreach (  $user->getUserItems()as $itid) :  $item = new item($itid['id']); ?>

                        <div class="item col-md-4">

                            <span class='price'>$<?php echo number_format($item->getPrice()); ?> </span>
                            <a href="../pages/item.php?itd=<?php echo $item->getId(); ?>&itn=<?php echo $item->getName(); ?>">
                                <div class="imgecon"><img src="../lib/images/<?php echo $item->getImg(); ?>" alt="image" class='img-fluid' title="<?php echo $item->getImg(); ?>" /></div>
                                <p class='name'><?php echo ucfirst($item->getName()); ?></p>
                            </a>

                        </div>

                    <?php endforeach; ?>
                <?php else: echo "There is no Items"?>
                <?php endif;?>    
            </div>
        </div>
    </div>














<?php
    $p->footer();
endif;
