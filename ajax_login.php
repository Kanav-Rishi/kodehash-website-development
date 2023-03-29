<?php
include('function/function.php');
$obj=new StdClass;
//print_r($_POST);die;
$email=addslashes($_POST['email']);
$pass=($_POST['pass']);
//print_r($mobile);
if($email=="" && $pass=="")
{
	$obj->success="Please Fill All Values";
}
else
{
	$sql_check=sqlfetch("Select id,password from signup where email='$email' and password=md5('$pass') ");
	//print_r($pass);
	if(count($sql_check))
	{
		$_SESSION['user_email'] = $email;
		$_SESSION['user_id']=$sql_check[0]['id'];
		$_SESSION['user_pass']=$pass;
		$obj->success="Login Successful";
	}
	else {
		$obj->success="Incorrect Credentials";
	}
	}
echo json_encode($obj);
?>