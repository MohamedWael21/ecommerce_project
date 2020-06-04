<?php
/* standard class for all pages (super) */
class page
{

  public $title;

  function __construct($t)
  {
    $this->title = $t;
  }

  function header()
  {
    print("<!doctype html>
 <html>
     <head>
      <meta charset='utf-8'>
      <meta name='viewport' content='width=device-width,initial-scale=1.0'>
      <title> $this->title</title>   
      <link rel='stylesheet' href='../lib/bootstrap.min.css'></link>
      <link rel='stylesheet' href='../lib/mystyle.css'></link>
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>   
    <body>");
  }

  function footer()
  {
    print('<script src="../lib/jquery-3.4.1.slim.min.js"></script>
 <script src="../lib/myjs.js"></script>
 </body>
 </html> ');
  }
  function navbar()
  {
    include "/var/webroot/myproject2/include/Navbar.html";
  }

  static function  connect()
  {
    try {
      $conc = new pdo("mysql:host=localhost;dbname=myproject;", "mohame", "wael@aref");
      $conc->setattribute(pdo::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conc;
    } catch (PDOException $e) {
      echo $e;
    }
  }
}
/************
 * 
 * 
 * **********
 * 
 * 
 * **********/

/* class query for  DB */

class query
{

  private $con;
  private $table;

  function __construct($c, $t)
  {

    $this->con = $c;
    $this->table = $t;
  }


  function bringAll()
  {

    $sql = "select * from $this->table";
    $stm = $this->con->prepare($sql);
    $stm->execute();
    $arr = $stm->fetchALL();

    return $arr;
  }

  function check($item, $value)
  {

    $sql = "select $item from $this->table where $item=?";
    $stm = $this->con->prepare($sql);
    $stm->execute(array($value));
    $count = $stm->rowcount();

    if ($count > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function bring($item, $cond = NULL, $val = NULL)
  {

    if ($cond == NULL) {
      $sql = "select $item from $this->table";
      $stm = $this->con->prepare($sql);
      $stm->execute();
      $ar = $stm->fetchAll();


      return $ar;
    } else {
      $sql = "select $item from $this->table where $cond = ?";
      $stm = $this->con->prepare($sql);
      $stm->execute(array($val));
      $ar = $stm->fetchAll();

      return $ar;
    }
  }
}

/************
 * 
 * 
 * **********
 * 
 * 
 * **********/
/* class for user */
class user
{

  private $id;
  private $name;
  private $email;
  private $fullname;
  private $img;
  public $userItems;

  function __construct($userid)
  {
    $co = page::connect();
    $q = new query($co, "users");
    $array = $q->bring('*', "id", $userid);
    foreach ($array as $element) {
      $this->id = $element['id'];
      $this->name = $element['name'];
      $this->email = $element['email'];
      $this->fullname = $element['fullname'];
      $this->img = $element['img'];
    }

    $q2 = new query($co, 'items');
    $this->userItems = $q2->bring('id', 'user_id', $userid);
  }

  function getId()
  {
    return $this->id;
  }

  function getName()
  {
    return $this->name;
  }

  function getEmail()
  {
    return $this->email;
  }

  function getFullname()
  {
    return $this->fullname;
  }

  function getImg()
  {

    return $this->img;
  }

  function getUserItems()
  {

    if (empty($this->userItems)) {

      return false;
    }

    return $this->userItems;
  }

  function getUserItemsNO()
  {

    return count($this->userItems);
  }
}


/************
 * 
 * 
 * **********
 * 
 * 
 * **********/



/* class for  login and signin */

class sign extends page
{

  function __construct($t)
  {
    $this->title = $t;
  }

  function login($username, $pass)
  {
    $co = $this->connect();
    $q = new query($co, 'users');
    $checkuser = $q->check('name', $username);
    $arr = $q->bring('password', 'name', $username);
    $checkpass = empty($arr) ? false : password_verify($pass, $arr[0]['password']);

    if ($checkuser && $checkpass) {
      $a = $q->bring("id", "name", $username);
      return $a[0]['id'];
    } else {
      return false;
    }
  }

  function signin($user, $pass, $email, $full, $img = null)
  {

    $co = $this->connect();
    $q = new query($co, 'users');
    $checkuser = $q->check('name', $user);


    $formerror = [];

    if (empty($user) || strlen($user) < 4) {
      $formerror[] = "username can\'t be empty or   can\'t be less than 4 character";
    }

    if (empty($pass) || strlen($pass) < 8) {
      $formerror[] = "password can\'t be empty or can\'t be less than 8 character";
    }

    if (empty($email)) {
      $formerror[] = "Email  can\'t be empty";
    }
    if (empty($full)) {
      $formerror[] = "FullName can\'t be empty";
    }
    if ($checkuser) {
      $formerror[] = "this username is used";
    }

    if (!empty($formerror)) {

      return $formerror;
    }


    $pass = password_hash($pass, PASSWORD_DEFAULT);

    if ($img['error'] == 4) {
      $co = $this->connect();
      $sql = "insert into users (name,password,email,fullname) value(?,?,?,?)";
      $st = $co->prepare($sql);
      $ch = $st->execute(array($user, $pass, $email, $full));

      return $ch;
    } else {

      $imageerror = [];

      $imagename = $img['name'];
      $imagetmp = $img['tmp_name'];
      $imagesize = $img['size'];

      $allwoedexstension = array('jpg', 'png', 'gif', 'jpeg');
      $imageextension = explode('.', $imagename);
      $imageextension = end($imageextension);
      $imageextension = strtolower($imageextension);

      if (!in_array($imageextension, $allwoedexstension)) {

        $imageerror[] = "Not allowed extenstion";
      }
      if ($imagesize > 4000000) {
        $imageerror[] = "Not allowed Size";
      }

      if (empty($imageerror)) {

        $imagename = rand(0000, 9999) . "_" . $imagename;

        move_uploaded_file($imagetmp, "../lib/images/$imagename");
        $co = $this->connect();
        $sql = "insert into users (name,password,email,fullname,img) value (?,?,?,?,?)";
        $stm = $co->prepare($sql);
        $ch = $stm->execute(array($user, $pass, $email, $full, $imagename));
        return $ch;
      } else {

        return $imageerror;
      }
    }
  }
}

/************
 * 
 * 
 * **********
 * 
 * 
 * **********/


/* class for home page */
class home  extends page
{
  
  function getLastItems(){
    $co = $this->connect();
    $stm = "SELECT id FROM items ORDER by id  DESC limit 8";
    $stmt = $co->prepare($stm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
}

/************
 * 
 * 
 * **********
 * 
 * 
 * **********/

/* class for  item */
class item
{
  private $id;
  private $name;
  private $price;
  private $img;
  private $userid;
  private $catid;


  public function __construct($d)
  {

    $q = new query(page::connect(), "items");
    $itemsData = $q->bring("*", "id", $d);

    foreach ($itemsData as $data) {
      $this->id =       $data['id'];
      $this->name =     $data['name'];
      $this->price =    $data['price'];
      $this->img =      $data['img'];
      $this->userid =   $data['user_id'];
      $this->catid =    $data['cat_id'];
    }
  }

  public function getId()
  {
    return $this->id;
  }
  public function getName()
  {
    return $this->name;
  }
  public function getPrice()
  {
    return $this->price;
  }
  public function getImg()
  {
    return $this->img;
  }
  public function getUser()
  {
    return $this->userid;
  }
  public function getCat()
  {
    return $this->catid;
  }
}
/************
 * 
 * 
 * **********
 * 
 * 
 * **********/


/*class for add item */
class add extends page
{
  private $userid;

  function __construct($t, $ud)
  {

    $this->title = $t;
    $this->userid = $ud;
  }

  function getcat()
  {

    $co = $this->connect();
    $query = new query($co, 'categories');
    $result = $query->bring("id,name");
    return $result;
  }

  function additem($n, $p, $c, $i = null)
  {

    $formerror = array();

    if (empty($n) || strlen($n) > 20) {

      $formerror[] = "name can\'t be empty or it too long";
    }
    if (empty($p)) {

      $formerror[] = "price can\'t be empty";
    }

    if ($c == 0) {
      $formerror[] = "you should choose category";
    }
    if (!empty($formerror)) {
      return $formerror;
    }

    $n = trim($n);
    $n = htmlspecialchars($n);
    $p = intval($p);

    $co = page::connect();

    if ($i['error'] == 4) {

      $sql = "insert into items (name,price,cat_id,user_id) value (?,?,?,?)";
      $stmt = $co->prepare($sql);
      $stmt->execute(array($n, $p, $c, $this->userid));
      if ($stmt->rowCount() > 0) {
        return true;
      } else {
        return $co->errorInfo();
      }
    } else {

      $imageerror = [];

      $imagename = $i['name'];
      $imagetmp = $i['tmp_name'];
      $imagesize = $i['size'];

      $allwoedexstension = array('jpg', 'png', 'gif', 'jpeg');
      $imageextension = explode('.', $imagename);
      $imageextension = end($imageextension);
      $imageextension = strtolower($imageextension);

      if (!in_array($imageextension, $allwoedexstension)) {

        $imageerror[] = "Not allowed extenstion";
      }
      if ($imagesize > 4000000) {
        $imageerror[] = "Not allowed Size";
      }

      if (empty($imageerror)) {

        $imagename = rand(0000, 9999) . "_" . $imagename;

        move_uploaded_file($imagetmp, "../lib/images/$imagename");
        $sql = "insert into items (name,price,cat_id,user_id,img) value (?,?,?,?,?)";
        $stmt = $co->prepare($sql);
        $stmt->execute(array($n, $p, $c, $this->userid, $imagename));
        if ($stmt->rowcount() > 0) {
          return true;
        } else {
          return $co->errorInfo();
        }
      } else {
        return $imageerror;
      }
    }
  }
}

/************   
 *                
 * 
 * **********
 * 
 * 
 * **********/

/* class for categories*/

class categories extends page
{

  function getALLCat()
  {
    $co = $this->connect();
    $q  =  new query($co, 'categories');
    $cat = $q->bringAll();

    return $cat;
  }

  function getItems()
  {

    $co = $this->connect();
    $q  =  new query($co, 'items');
    $idarray = $q->bring("id");

    return $idarray;
  }
  function getItemsNo()
  {
    $co = $this->connect();
    $q  =  new query($co, 'items');
    $idarray = $q->bring("id");

    return count($idarray);
  }

  function getCatNo()
  {
    $co = $this->connect();
    $q  =  new query($co, 'categories');
    $cat = $q->bringAll();

    return count($cat);
  }
  function getItemAtCat($cat_id)
  {
    $co = $this->connect();
    $q  =  new query($co, 'items');
    $idarray = $q->bring("id", "cat_id", $cat_id);
    return $idarray;
  }
}

/************   
 *                
 * 
 * **********
 * 
 * 
 * **********/

/*  class for items page */
class itemp extends page
{

  private $item_id;
  private $item_name;
  private $item_price;
  private $item_img;
  private $item_user;
  private $item_cat;
  function __construct($t, $itd)
  {

    $this->title = $t;

    $q = new query($this->connect(), "items");
    foreach ($q->bring("*", "id", $itd) as $arr) {

      $this->item_id = $arr['id'];
      $this->item_name = $arr['name'];
      $this->item_price = $arr['price'];
      $this->item_img = $arr['img'];
      $this->item_user = $arr['user_id'];
      $this->item_cat  = $arr['cat_id'];
    }
  }
  function getId()
  {

    return $this->item_id;
  }
  function getName()
  {

    return $this->item_name;
  }
  function getPrice()
  {

    return $this->item_price;
  }
  function getImg()
  {

    return $this->item_img;
  }
  function getUserName()
  {

    $q = new query($this->connect(), "users");
    $arr = $q->bring('name', "id", $this->item_user);
    return $arr[0]['name'];
  }
  function getCatName()
  {

    $q = new query($this->connect(), "categories");
    $arr = $q->bring('name', "id", $this->item_cat);
    return $arr[0]['name'];
  }
  function getUserId()
  {

    return $this->item_user;
  }
  function getCatId()
  {

    return $this->item_cat;
  }
  function addComment($userId, $comment)
  {
    $co = $this->connect();
    $comment = htmlspecialchars($comment);
    $stmt = 'INSERT INTO comments (user_id,item_id,comment) VALUE (?,?,?)';
    $stmt =  $co->prepare($stmt);
    $stmt->bindValue(1, $userId);
    $stmt->bindValue(2, $this->item_id);
    $stmt->bindValue(3, $comment);
    return $stmt->execute();
  }
  function getComments()
  {
    $co = $this->connect();
    $stmt = 'SELECT * FROM comments WHERE  item_id = ?';
    $stmt = $co->prepare($stmt);
    $stmt->bindValue(1,$this->item_id);
    $stmt->execute(); 
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    

  }
}
/************   
 *                
 * 
 * **********
 * 
 * 
 * **********/

