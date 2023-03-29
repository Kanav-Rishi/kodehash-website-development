<?php
	include('function/function.php');
	$obj=new stdClass();

	include('smtp/PHPMailerAutoload.php');
	require 'function/registrationMail.php';
	if($_POST["signup"]=="signup")
	{
	
        extract($_POST);
		$posted_data=$_POST;
		$name=$_POST["uname"];
        $username=$_POST["uname"];
        $mobile=$_POST["mobile"];
		$email=$_POST['email'];
		$country=$_POST["country"];
		$des=$_POST["desc"];
		$des=str_replace("<",$des,'&lt');
		$des=str_replace(">",$des,'&gt');
		$phone=$_POST["mobile"];
		
		if($username=="" || $mobile=="" || $email=="" || $des=="" || $country="")
		{
			$obj->success="Please Input all fields";
		}
			else if(!preg_match('/^[0-9]{10}+$/', $mobile)) {
				$obj->success= "Please Input valid Mobile Number";
				}
			else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  $obj->success= "Invalid email format";
				}
			else if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
				  $obj->success="Only letters and white space allowed";
				}
		else{
        $email=$_POST["email"];
        $posted_data['name']=$_POST['uname'];
		$posted_data['mobile']=$_POST['mobile'];
		$posted_data['description']=$_POST['desc'];
		$posted_data['country']=$_POST["country"];
		$posted_data['deleted']='0';
			$city="Gurugram";
			$state="Haryana";
			$vendorMail = sendRegisterMail($name,$email);
		$query=insert('contact',$posted_data);
				if($query)
				{
					$obj->success="Data Inserted Successfully";
				}
				else
				{
					$obj->success="Error in Insertion";
				}
			}
		}
			else
			{
				$obj->success="Error in Signup";
			}
			
echo json_encode($obj);
?>