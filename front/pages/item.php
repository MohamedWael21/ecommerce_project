<?php
include '/var/webroot/myproject2/include/classes.php';

if (isset($_GET['itd']) && isset($_GET['itn'])) {
    $id = $_GET['itd'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_GET['itn'], FILTER_SANITIZE_STRING);

    if (is_numeric($id)) {

        $q = new query(page::connect(), "items");
        if ($q->check("id", $id)) {
            session_start();
            $usid = isset($_SESSION['userid']) ? $_SESSION['userid'] : $_COOKIE['SID'];
            $user = new user($usid);
            $p = new itemp($name, $id);
            $p->header();
            $p->navbar();
?>

            <div class="container img-con-item">
                <img src="../lib/images/<?php echo $p->getImg();  ?>" alt="<?php echo $p->getName() . "image"; ?>" class="img-fluid">
            </div>
            <div class="item-info-page">

                <div class="info"><span>Name</span> : <p> <?php echo $p->getName(); ?> </p>
                </div>
                <div class="info"><span>Price</span> : <p> <?php echo $p->getPrice();  ?> </p>
                </div>
                <div class="info"><span>owner</span> : <p> <?php echo $p->getUserName();  ?> </p>
                </div>
                <div class="info"><span>categorie</span> : <p> <a href = "../pages/categories.php?itd=<?php echo $p->getCatId(); ?>"> <?php echo $p->getCatName();  ?> </a> </p>
                </div>


            </div>
            <div class="comments-con ">
                <div class="usercon">
                    <img src="../lib/images/<?php echo $user->getImg(); ?>" alt="userImage" class="rounded-circle">
                    <span><?php echo $user->getName(); ?></span>
                </div>
                <form method="POST">
                    <textarea name="Ncomment" id="commenttext" required></textarea>
                    <input type="hidden" name="USER_id" value="<?php echo $user->getId(); ?>">
                    <input type="submit" value="send" id="sendcomm" class="btn btn-primary">
                </form>
            </div>
            <div class="comment-replied">
                <?php $comments = $p->getComments();
                if (!empty($comments)) :
                ?>
                    <?php foreach ($comments as $comment) : ?>
                        <?php $use = new user($comment['user_id']); ?>
                        <div class="usercon">
                            <div class="badge">
                                <img src="../lib/images/<?php echo $use->getImg(); ?>" alt="userImage" class="rounded-circle">
                                <span><?php echo $use->getName() ?></span>
                            </div>
                            <div class="comment">
                                <?php echo $comment['comment']; ?>
                            </div>
                        </div>
                      
                    <?php endforeach; ?>

                <?PHP endif; ?>


            </div>



<?php
            $p->footer();
        } else {

            echo "there is no item with this id";
        }
    } else {

        header("location:../pages/categories.php");
    }
} else {

    header("location:../pages/categories.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['Ncomment'])) {

        echo "<div class='alert alert-danger'>you should add comment</div>";
    } else {

        if ($p->addComment($_POST['USER_id'], $_POST['Ncomment'])) {
        }
    }
}
