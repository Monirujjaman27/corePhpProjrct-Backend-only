
<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../lib/database.php');
    include_once ($filepath.'/../halpers/formet.php');
?>
<?php
    class Cls_cart{
        private $db;
        private $fm;
        public function __construct(){
            $this->db = new Database();
            $this->fm = new Format();
        }
        public function insert($gatId, $quantity){
            $quantity = $this->fm->validation($_POST['quantity']);
            $quantity = mysqli_real_escape_string($this->db->link, $quantity);
            $id = mysqli_real_escape_string($this->db->link, $gatId);


            $squery = "SELECT * FROM tbl_product WHERE id = '$id'";
            $result = $this->db->select($squery)->fetch_assoc();

            $sId = session_id();
            $productName = $result['title'];
            $price = $result['price'];
            $image = $result['thumbnail'];


            $query = "INSERT INTO tbl_cart( sId, productId,productName, price, quantity, image) VALUES ('$sId','$id','$productName','$price','$quantity','$image')";
            $inserted = $this->db->insert($query);
            if($inserted){
                header("Location:shopping-cart.php");
            }else{
                header("Location:404.php");
            }
        }







        
    }
    
?>