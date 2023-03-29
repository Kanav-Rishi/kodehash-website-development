<?php
ini_set('display_errors','1');
//error_reporting(E_ALL);
// define('SITE_URL','https://'.$_SERVER['HTTP_HOST'].'/admin/');
// define('ASSET_URL','https://'.$_SERVER['HTTP_HOST'].'/');
// define('WEB_URL','https://'.$_SERVER['HTTP_HOST'].'/');
$siteTitle='Ecommernce Site';
// include_once(  __DIR__ .'/../includes/config.php');
session_start();

function getPDOObject()
 {
$dsn = 'mysql:host=localhost;dbname=kodehash;charset=utf8mb4';
$user = 'root';
$pass = '';
$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_PERSISTENT, true);

   return $pdo;
}
function sqlfetch($query)
{
	$row=array();
	$pdo=getPDOObject();
	$sql=$pdo->query($query);	
	$datas = $sql->fetchAll(PDO::FETCH_ASSOC);
	foreach($datas as $data)
	$row[]=$data;
	return $row;
}

/*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
function getRows($table,$conditions = array()){
	$pdo=getPDOObject();
	$sql = 'SELECT ';
	$sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
	$sql .= ' FROM '.$table;
	if(array_key_exists("where",$conditions)){
		$sql .= ' WHERE ';
		$i = 0;
		foreach($conditions['where'] as $key => $value){
			$pre = ($i > 0)?' AND ':'';
			$sql .= $pre.$key." = '".$value."'";
			$i++;
		}
	}
	
	if(array_key_exists("order_by",$conditions)){
		$sql .= ' ORDER BY '.$conditions['order_by']; 
	}
	
	if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
		$sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
	}elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
		$sql .= ' LIMIT '.$conditions['limit']; 
	}
	
	$query = $pdo->prepare($sql);
	$query->execute();
	
	if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
		switch($conditions['return_type']){
			case 'count':
				$data = $query->rowCount();
				break;
			case 'single':
				$data = $query->fetch(PDO::FETCH_ASSOC);
				break;
			default:
				$data = '';
		}
	}else{
		if($query->rowCount() > 0){
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	return !empty($data)?$data:false;
}

/*
 * Insert data into the database
 * @param string name of the table
 * @param array the data for inserting into the table
 */
function insert($table,$data){
	$pdo=getPDOObject();
	
	// $fld_str='';$val_str='';
	// if($table_name && is_array($data_array))
		// {
	  $sql="SHOW COLUMNS FROM `".$table."`";
		$columns_query= sqlfetch($sql);
		
				foreach($columns_query as $coloumn_data)  
			  $column_name[]=$coloumn_data['Field'];
				// print_r($column_name);  
	
	if(!empty($data) && is_array($data)){
		$columns = '';
		$values  = '';
		$i = 0;
		if(!array_key_exists('created',$data)){
			$data['created'] = date("Y-m-d H:i:s");
		}
		if(!array_key_exists('modified',$data)){
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$actual_data=array();
		
		foreach($data as $key=>$val)
			{
			 if(in_array($key,$column_name))
				{
					// echo $key;
					$actual_data[$key]=$val;
				}
			}
		// print_r($actual_data);
		$columnString = implode(',', array_keys($actual_data));
		$valueString = ":".implode(',:', array_keys($actual_data));
		$sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
		$query = $pdo->prepare($sql);
		foreach($actual_data as $key=>$val){
			$val = htmlspecialchars(strip_tags($val));
			$query->bindValue(":".$key, $val);
		}
		$insert = $query->execute();
		if($insert){
			$data['id'] = $pdo->lastInsertId();
			return $data;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

/*
 * Update data into the database
 * @param string name of the table
 * @param array the data for updating into the table
 * @param array where condition on updating data
 */
function update($table,$data,$conditions){
	
	$sql="SHOW COLUMNS FROM `".$table."`";
		$columns_query= sqlfetch($sql);
		
				foreach($columns_query as $coloumn_data)  
			  $column_name[]=$coloumn_data['Field'];
	$actual_data=array();
		
		foreach($data as $key=>$val)
			{
			 if((in_array($key,$column_name)) )
				{
					// echo $key;
					$actual_data[$key]=addslashes($val);
				}
			}
	$pdo=getPDOObject();
	
	
	if(!empty($actual_data) && is_array($actual_data)){
		$colvalSet = '';
		$whereSql = '';
		$i = 0;
		// if(!array_key_exists('modified',$data)){
			// $actual_data['modified'] = date("Y-m-d H:i:s");
		// }
		foreach($actual_data as $key=>$val){
			$pre = ($i > 0)?', ':'';
			$val = ($val);
			$colvalSet .= $pre.$key."='".$val."'";
			$i++;
		}
		if(!empty($conditions)&& is_array($conditions)){
			$whereSql .= ' WHERE ';
			$i = 0;
			foreach($conditions as $key => $value){
				$pre = ($i > 0)?' AND ':'';
				$whereSql .= $pre.$key." = '".$value."'";
				$i++;
			}
		}
	 $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
		$query = $pdo->prepare($sql);
		$update = $query->execute();
		return $update?$query->rowCount():false;
	}else{
		return false;
	}
}


function check_session()
{
	if(empty($_SESSION['admin_id']))
	{
		header('location:login'); exit();
	}
}

function check_login()
{
	if(isset($_SESSION['admin_id']))
	{
		$location = SITE_URL;
		header("location:$location");
		exit();
		
	}
}


function login_me()
{
	extract($_POST);

	$pdo = getPDOObject();
	if(empty($email))
	{
		$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> Login Email Required !</div>';
	}else if(empty($password)){
		$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> Password Required !</div>';
	}else{
	//	echo "id,email,phone,password,name,astatus,deleted FROM `admin` WHERE email = '".$email."'"; die();
		$sql = $pdo->prepare("SELECT id,email,phone,password,name,astatus,deleted FROM `admin` WHERE email = ?");
		$sql->execute([$email]);
		$count_row = $sql->rowCount();
	
		if($count_row > 0){
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			if($data['password'] !=  md5($password))
			{
				$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> Wrong Password !</div>';
			}else if($data['deleted'] == '1'){
				$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> Your are not a valid user !</div>';
			}else{
				$_SESSION['admin_id'] = $data['id'];
				$_SESSION['admin_name'] = $data['name'];
				$_SESSION['admin_email'] = $data['email'];
				$_SESSION['admin_phone'] = $data['phone'];
				/** Update Login detail **/
				$login_ip = $_SERVER['REMOTE_ADDR'];
				$last_login = date('Y-m-d H:i:s');
				$update = $pdo->prepare("UPDATE `admin` SET login_ip=?,last_login=? WHERE id=?");
				$update->execute([$login_ip,$last_login,$data['id']]);
				$location = SITE_URL;
				header("location:$location");
				die();
			}
		}else{
			$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> Wrong Email ID !</div>';
		}
	}
	return $umessage;
}


 function get_profile($id)
  {
	$pdo = getPDOObject();
	$profileSql = $pdo->prepare("SELECT * FROM `admin` WHERE id = ? limit 1");
	$profileSql->execute([$id]);
	$count=$profileSql->rowCount();
	$rowData = $profileSql->fetch(PDO::FETCH_ASSOC);
	return $rowData;
  }
  
  
function get_active_status_text($num)
{
	$status='';
	if($num==0)
		$status='<span class="badge badge-secondary align-text-bottom ml-1t">InActive</span>';
	if($num==1)
		$status='<span class="badge badge-success align-text-bottom ml-1">Active</span>';
	return $status;
}

function get_approval_status_text($num)
{
	$status='';
	if($num==0)
		$status='<span class="badge badge-warning align-text-bottom ml-1t">Pending</span>';
	if($num==1)
		$status='<span class="badge badge-primary align-text-bottom ml-1">Approved</span>';
	if($num==2)
		$status='<span class="badge badge-danger align-text-bottom ml-1">Rejected</span>';
	if($num==3)
		$status='<span class="badge badge-info align-text-bottom ml-1">Hold</span>';
	return $status;
}


/*** Vendor Register *****/
function vendor_register()
{
	$pdo=getPDOObject();
	extract($_POST);
	if(empty($email))
	{
		$umessage ='<div class="alert alert-danger">Please enter email</div>';
	}else if(strlen($name) < 2){
		$umessage ='<div class="alert alert-danger">Please enter valid name.</div>';
	}else if(empty($password) OR strlen($password)< 4){
		$umessage ='<div class="alert alert-danger">Please enter password with mininum 4 characters</div>';
	}else if($password != $passwordrep){
		$umessage ='<div class="alert alert-danger">Password does not match.</div>';
	}else{
		
		// check for duplicate entry //
		$check_sql = $pdo->prepare("SELECT email from `vendors` WHERE email=? and deleted=? limit 1");
		$check_sql->execute([$email,"0"]);
		$count_row = $check_sql->rowCount();
		if(!$count_row)
		{
			$token = bin2hex(random_bytes(8));
			$password = md5($password);
			$register = $pdo->prepare("INSERT INTO `vendors` (`name`,`email`,`password`,`token`) VALUES (?,?,?,?)");
			$result = $register->execute([$name,$email,$password,$token]);
			if($result)
			{
			  $umessage ='<div class="alert alert-success"><string>Congratulations !!</string>You registered register successfully.</div>';
			}else
				$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong>Registration failed.</div>';
		}else{
			$umessage ='<div class="alert alert-danger"><strong>Sorry !!</strong> This email already exist.</div>';
		}
	}
	return $umessage;
}

function RemoveSpecialChar($str) { 
      
    // Using str_replace() function  
    // to replace the word  
    $res = str_replace( array( '\'', '"', 
    ',' , ';',"'","’", '&',' & ','#','<', '>' ),'-', $str); 
     $slug=trim($res);
	 $slug= str_replace(' ','-',strtolower($slug));
	  $slug= str_replace('--','-',strtolower($slug));
	 $new_slug = str_replace('---','-',strtolower($slug));
    // Returning the result  
    return $new_slug; 
    }

function get_vendor_name($id)
{
	$pdo = getPDOObject();
	$name = '';
	$sql = $pdo->prepare("SELECT name FROM `vendors` WHERE v_id=? limit 1");
	$sql->execute([$id]);
	$rows = $sql->fetch(PDO::FETCH_ASSOC);
    if($rows){
		$name = $rows['name'];
	}
    return $name;	
}

function get_vendor_detail($id)
{
	$pdo = getPDOObject();
	$name = '';
	$sql = $pdo->prepare("SELECT * FROM `vendors` WHERE v_id=? limit 1");
	$sql->execute([$id]);
	$rows = $sql->fetch(PDO::FETCH_ASSOC);
    if($rows){
		$name = $rows;
	}
    return $name;	
}

function get_fereal($id)
{
	$pdo = getPDOObject();
	$name = '';
	//echo "SELECT * FROM `referals` WHERE id='".$id."' limit 1"; die();
	$sql = $pdo->prepare("SELECT * FROM `referals` WHERE id='".$id."' limit 1");
	$sql->execute();
	$rows = $sql->fetch(PDO::FETCH_ASSOC);
    if($rows){
		$name = $rows;
	}
    return $name;
}

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
        }
        
        
    function vendor_count(){
    $vendor_arr=array();
    $pdo=getPDOObject();
    $sql= $pdo->prepare("SELECT v_id FROM `vendors` WHERE deleted='0'");
    $sql->execute();
    $num = $sql->rowCount();
    if($num>0)
    {
        foreach( $sql as $vendor)
        {
        $vendor_arr[]= $vendor['v_id'];
        }
    }
    return $vendor_arr;
    
}

function prod_count(){
    $prod_arr=array();
    $pdo=getPDOObject();
    $sql=$pdo->prepare("SELECT p_id FROM `products`WHERE deleted='0' ");
    $sql->execute();
    $num=$sql->rowCount();
    if($num>0)
    {
        foreach($sql as $prod)
        {
            $prod_arr[]=$prod['p_id'];
        }
    }
    return $prod_arr;
}

function user_count()
{
    $user_arr=array();
    $pdo= getPDOObject();
    $sql=$pdo->prepare("SELECT user_id FROM `users` WHERE deleted='0' ");
    $sql->execute();
    $num=$sql->rowCount();
    if($num>0)
    {
        foreach($sql as $user)
        {
            $user_arr[]= $user['user_id'];
        }
    }
    return $user_arr;
    
}

    /*Product Count*/
 function get_prodcnt($v_id){
         $pcount = '';
        $pdo=getPDOObject();
        $query = $pdo->prepare("SELECT count(*) as rcount FROM `products` WHERE v_id=? AND deleted='0' AND astatus='1' ");
        $query->execute([$v_id]);
        $num = $query->rowCount();
        if($num)
           {
        	   	$data = $query->fetch(PDO::FETCH_ASSOC);
        	   	$pcount = $data['rcount'];
           } 
           return $pcount;

    }
    
    function get_dev_status_text($num)
	{
		$status='';
		if($num==0)
			$status='<span class="badge badge-warning align-text-bottom ml-1t">Processing</span>';
		if($num==1)
			$status='<span class="badge badge-primary align-text-bottom ml-1">Completed</span>';
		if($num==2)
			$status='<span class="badge badge-danger align-text-bottom ml-1">Canceled</span>';
		if($num==3)
			$status='<span class="badge badge-warning align-text-bottom ml-1">Refunded</span>';
		if($num==4)
			$status='<span class="badge badge-focus align-text-bottom ml-1">On Hold</span>';
		if($num==5)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Pending Payment</span>';
		if($num==6)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Dispatched</span>';
		return $status;
	}
	function get_order_status_text($num)
	{
		$status='';
		if($num==0)
			$status='<span class="badge badge-warning align-text-bottom ml-1t">Processing</span>';
		if($num==1)
			$status='<span class="badge badge-primary align-text-bottom ml-1">Completed</span>';
		if($num==2)
			$status='<span class="badge badge-danger align-text-bottom ml-1">Canceled</span>';
		if($num==3)
			$status='<span class="badge badge-warning align-text-bottom ml-1">Refunded</span>';
		if($num==4)
			$status='<span class="badge badge-focus align-text-bottom ml-1">On Hold</span>';
		if($num==5)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Pending Payment</span>';
		if($num==6)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Dispatched</span>';
		return $status;
	}
	
	function amount_settlement_text($num)
	{
		$status='';
		if($num=='')
			$status='<span class="badge badge-warning align-text-bottom ml-1t">No Request</span>';
		if($num==0)
			$status='<span class="badge badge-primary align-text-bottom ml-1">Processing</span>';
		if($num==2)
			$status='<span class="badge badge-danger align-text-bottom ml-1">Rejected</span>';
		if($num==1)
			$status='<span class="badge badge-success align-text-bottom ml-1">Approved</span>';
		return $status;
	}
	
	function get_payment_status_text($num)
	{
		$status='';
		if($num==0)
			$status='<span class="badge badge-warning align-text-bottom ml-1t">Processing</span>';
		if($num==1)
			$status='<span class="badge badge-primary align-text-bottom ml-1">Completed</span>';
		if($num==2)
			$status='<span class="badge badge-danger align-text-bottom ml-1">Canceled</span>';
		if($num==3)
			$status='<span class="badge badge-warning align-text-bottom ml-1">Refunded</span>';
		if($num==4)
			$status='<span class="badge badge-focus align-text-bottom ml-1">On Hold</span>';
		if($num==5)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Pending Payment</span>';
		if($num==6)
			$status='<span class="badge badge-focus align-text-bottom ml-1">Dispatched</span>';
		return $status;
	}
	
function get_name($table,$select,$whrstr)
	{
		$value='';
		$pdo = getPDOObject();
		$sql=$pdo->query("SELECT $select FROM $table WHERE $whrstr");
		
		if(!empty($sql))
			foreach($sql as $row)
			{
				$value=$row[$select];
				
				
				$value = explode(",",$value);
				$value = array_map('utf8_encode', $value);
				$value = implode(",",$value);
			}
			return $value; 
	}
function get_customer_name($id)
{
	$result = '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT fname FROM `users` WHERE user_id=?");
	$sql->execute([$id]);
	$result = $sql->fetchcolumn();
	return $result;
}
function get_product_cat($id)
{
	$name= '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT name FROM `category` WHERE cat_id = ? LIMIT 1");
	$sql->execute([$id]);
	$name = $sql->fetchcolumn();
	return $name;
}
function get_product_subCat($id)
{
	$name= '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT name FROM `sub_category` WHERE subCat_id = ? LIMIT 1");
	$sql->execute([$id]);
	$name = $sql->fetchcolumn();
	return $name;
}
function get_area_boy_name($id)
{
	$name= '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT name FROM `delivery_boys` WHERE id = ? LIMIT 1");
	$sql->execute([$id]);
	$name = $sql->fetchcolumn();
	return $name;
}
 function get_delivery_boy_name($id)
 {
	    $name = 'Nill';
	     $pdo = getPDOObject();
	 
		 $nameSql = $pdo->prepare("SELECT name FROM `delivery_boys` WHERE id=? LIMIT 1");
		 $nameSql->execute([$id]);
		 $name = $nameSql->fetchcolumn();
	
	 return $name;
 }
    
  function getCatSlug($cat_id)
{
	$name = '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT slug FROM `category` WHERE cat_id=? LIMIT 1");
	$sql->execute([$cat_id]);
	$count = $sql->rowCount();
	if($count > 0){
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		$name = $row['slug'];
	}
	return $name;
}

function subCatSlug($subCat_id)
{
	$name = '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT slug FROM `sub_category` WHERE subCat_id=? LIMIT 1");
	$sql->execute([$subCat_id]);
	$count = $sql->rowCount();
	if($count > 0){
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		$name = $row['slug'];
	}
	return $name;
}

/*New Add */   
 function getProdSlug($cat_id){
       $pname='';
       $pdo=getPDOObject();
       $query=$pdo->prepare("SELECT slug FROM `category` WHERE cat_id=?");
       $query->execute([$cat_id]);
       $data=$query->rowcount();
       if($data>0){
              $fech_data= $query->fetch(PDO::FETCH_ASSOC); 
              $pname= $fech_data['slug'];
              return  $pname;
       }
       
   }
    function getsubcat($subCat_id){
       $pname='';
       $pdo=getPDOObject();
       $query=$pdo->prepare("SELECT slug FROM `sub_category` WHERE subCat_id=?");
       $query->execute([$subCat_id]);
       $data=$query->rowcount();
       if($data>0){
              $fech_data= $query->fetch(PDO::FETCH_ASSOC); 
              $pname= $fech_data['slug'];
              return  $pname;
       }
       
   }
   
   
    /*Order Count*/
 function get_ordrcnt($user_id){
         $pcount = '';
        $pdo=getPDOObject();
        $query = $pdo->prepare("SELECT count(*) as rcount FROM `order_history` WHERE user_id=? ");
        $query->execute([$user_id]);
        $num = $query->rowCount();
        if($num)
           {
        	   	$data = $query->fetch(PDO::FETCH_ASSOC);
        	   	$pcount = $data['rcount'];
           } 
           return $pcount;

    }
	/*Cancel Order Count*/
 function get_canclord($user_id){
         $pcount = '';
        $pdo=getPDOObject();
        $query = $pdo->prepare("SELECT count(*) as rcount FROM `cancellation_request` WHERE user_id=? ");
        $query->execute([$user_id]);
        $num = $query->rowCount();
        if($num)
           {
        	   	$data = $query->fetch(PDO::FETCH_ASSOC);
        	   	$pcount = $data['rcount'];
           } 
           return $pcount;

    }
	
	/*Return Order Count*/
 function get_retrnord($user_id){
         $pcount = '';
        $pdo=getPDOObject();
        $query = $pdo->prepare("SELECT count(*) as rcount FROM `return_request` WHERE user_id=? ");
        $query->execute([$user_id]);
        $num = $query->rowCount();
        if($num)
           {
        	   	$data = $query->fetch(PDO::FETCH_ASSOC);
        	   	$pcount = $data['rcount'];
           } 
           return $pcount;

    }

/*Get Product Name*/
function get_product_name($p_id)
{
	$result = '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT p_name FROM `products` WHERE p_id=?");
	$sql->execute([$p_id]);
	$result = $sql->fetchcolumn();
	return $result;
}
/*Get Customer Name*/
function get_custmr_name($user_id)
{
	$name = '';
	$pdo = getPDOObject();
	$sql = $pdo->prepare("SELECT fname,lname FROM `users` WHERE user_id=? LIMIT 1");
	$sql->execute([$user_id]);
	$result = $sql->fetch(PDO::FETCH_ASSOC);
    $name = $result['fname']." ".$result['lname'];
	return $name;
}

/*Get Customer Name*/
function get_payment_status_name($text){
	$status='';
	if($text=='pending')
		$status='<span class="badge badge-primary align-text-bottom ml-1t">Pending</span>';
	if($text=='authorized')
		$status='<span class="badge badge-success align-text-bottom ml-1">Received</span>';
	if($text=='failed')
		$status='<span class="badge badge-danger align-text-bottom ml-1">Failed</span>';
	return $status;
}


?>