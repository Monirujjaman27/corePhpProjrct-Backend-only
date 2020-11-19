
<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../lib/database.php');
    include_once ($filepath.'/../halpers/formet.php');
?>
<?php
    class Product{
        private $db;
        private $fm;
        public function __construct(){
            $this->db = new Database();
            $this->fm = new Format();
        }
        public function insert($data, $file){
            
                        $title   = $this->fm->validation($data['title']);
                        $catId   = $this->fm->validation($data['catId']);
                        $brandId = $this->fm->validation($data['brandId']);
                        $body    = $this->fm->validation($data['body']);
                        $price   = $this->fm->validation($data['price']);
                        $type    = $this->fm->validation($data['type']);
            
                        $title   = mysqli_real_escape_string($this->db->link, $title);
                        $catId   = mysqli_real_escape_string($this->db->link, $catId);
                        $brandId = mysqli_real_escape_string($this->db->link, $brandId);
                        $body    = mysqli_real_escape_string($this->db->link, $body);
                        $price   = mysqli_real_escape_string($this->db->link, $price);
                        $type    = mysqli_real_escape_string($this->db->link, $type);
            
            
                        $permited  = array('jpg', 'jepg', 'png', 'gif'); 
                        $file_name = $file['thumbnail']['name'];
            $file_size = $file['thumbnail']['size'];
            $file_temp = $file['thumbnail']['tmp_name'];

            $div          = explode('.', $file_name);
            $file_ext     = strtolower(end($div));
            $unique_image = date('d-m-y').'-'.time().'.'.$file_ext;
            $upload_image = "upload/".$unique_image;
            if($title == '' || $catId == '' || $brandId == '' || $file_name == '' || $body == '' || $price == '' || $type == ''){
                $msg = 'Fild Must Not Be empty';
                return $msg;
            }elseif($file_size>1048567){
                $msg = "Image size Should be less then 1MB";
                return $msg;
            }elseif(in_array($file_ext, $permited) === FALSE){
                $msg = "You can upload only: ".implode(', ' , $permited);  
                return $msg;
            }else{
                move_uploaded_file($file_temp, $upload_image);
                $query = "INSERT INTO tbl_product(title, catId, brandId, thumbnail, body, price, type) VALUES ('$title','$catId','$brandId','$unique_image','$body','$price','$type')";
                $result = $this->db->insert($query);
                if($result){
                    $msg = "<p class='mb-0 alert alert-success'>Insert Success</p>";
                    return $msg;
                }else{
                    $msg = '<p class="mb-0 text-warning">There Was Something Wrong to Insert the Catagory</p>';
                    return $msg;
                }
            }
        }

        public function show(){
            $query = "SELECT tbl_product.*, tbl_catagory.catagoryName, tbl_brand.brandName 
            FROM tbl_product
            INNER JOIN tbl_catagory
            ON tbl_product.catId = tbl_catagory.id
            INNER JOIN tbl_brand
            ON tbl_product.brandId = tbl_brand.id
            order by tbl_product.id DESC";

            $result = $this->db->select($query);
            return $result;
        }
        public function update($data, $file, $gatId){
            
            $title   = $this->fm->validation($data['title']);
            $catId   = $this->fm->validation($data['catId']);
            $brandId = $this->fm->validation($data['brandId']);
            $body    = $this->fm->validation($data['body']);
            $price   = $this->fm->validation($data['price']);
            $type    = $this->fm->validation($data['type']);

            $title   = mysqli_real_escape_string($this->db->link, $title);
            $catId   = mysqli_real_escape_string($this->db->link, $catId);
            $brandId = mysqli_real_escape_string($this->db->link, $brandId);
            $body    = mysqli_real_escape_string($this->db->link, $body);
            $price   = mysqli_real_escape_string($this->db->link, $price);
            $type    = mysqli_real_escape_string($this->db->link, $type);


            $permited  = array('jpg', 'jepg', 'png', 'gif'); 
            $file_name = $file['thumbnail']['name'];
            $file_size = $file['thumbnail']['size'];
            $file_temp = $file['thumbnail']['tmp_name'];

            $div          = explode('.', $file_name);
            $file_ext     = strtolower(end($div));
            $unique_image = date('d-m-y').'-'.time().'.'.$file_ext;
            $upload_image = "upload/".$unique_image;


        if(empty($file_name)){
            if($title == '' || $catId == '' || $brandId == '' || $body == '' || $price == '' || $type == ''){
                $msg = 'Fild Must Not Be empty';
                return $msg;
            }else{
                    $query = "UPDATE tbl_product SET title = '$title', catId = '$catId', brandId = '$brandId', body = '$body', price = '$price', type = '$type' WHERE id = '$gatId'";
                    $result = $this->db->insert($query);
                    if($result){
                        $msg = "<p class='mb-0 alert alert-success'>Update Success</p>";
                        return $msg;
                    }else{
                        $msg = '<p class="mb-0 text-warning">There Was Something Wrong to Update the Catagory</p>';
                        return $msg;
                    }
                }
            }else{

                if($title == '' || $catId == '' || $brandId == '' || $file_name == '' || $body == '' || $price == '' || $type == ''){
                    $msg = 'Fild Must Not Be empty';
                    return $msg;
                }else{
                    if($file_size>1048567){
                    $msg = "Image size Should be less then 1MB";
                    return $msg;
                    }elseif(in_array($file_ext, $permited) === FALSE){
                        $msg = "You can upload only: ".implode(', ' , $permited);  
                        return $msg;
                    }else{
                        move_uploaded_file($file_temp, $upload_image);
                        $query = "UPDATE tbl_product SET title = '$title', catId = '$catId', brandId = '$brandId', thumbnail = '$unique_image', body = '$body', price = '$price', type = '$type' WHERE id = '$gatId'";
                        $result = $this->db->insert($query);
                        if($result){
                            $msg = "<p class='mb-0 alert alert-success'>Update Success</p>";
                            return $msg;
                        }else{
                            $msg = '<p class="mb-0 text-warning">There Was Something Wrong to Update the Catagory</p>';
                            return $msg;
                        }
                    }
                 }

            }            
        }


        public function delete($gatId){
              $delquery = "DELETE FROM tbl_product WHERE id = '$gatId'";
              $del = $this->db->delete($delquery);
              if($del){
                $msg = "<p class='mb-0 alert alert-success'>Delete Success</p>";
                return $msg;
            }else{
                $msg = '<p class="mb-0 text-warning">There Was Something Wrong to Delete the Catagory</p>';
                return $msg;
            }
        }
        public function showByGatId($gatId){
            $query = "SELECT * FROM tbl_Product WHERE id = '$gatId'";
            $result = $this->db->select($query);
            return $result;
        }


        public function getProForFeatured(){
            $query = "SELECT * FROM tbl_Product WHERE type = '1' order by id desc";    
            $result = $this->db->select($query);
            return $result;        
        }

        public function getProForNew(){
            $query = "SELECT * FROM tbl_Product  order by id desc";    
            $result = $this->db->select($query);
            return $result;        
        }

    }
?>