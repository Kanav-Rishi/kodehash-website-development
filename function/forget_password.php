<?php
ini_set('display_errors','1');
require_once('function.php');

function reset_password($email,$name,$token)
{
	
	$message= '
	<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Stuffking King</title>
		</head>
		<style type="text/css">
		  .main-bg{
			width: 500px;
			background: #ffde59;
			margin: 25px auto;
			padding: 50px 110px;
			font-family: arial;

		  }
		  .main-bg a{
			text-decoration: none;
			background: blue;
			color: #fff;
			padding: 10px 15px;
			border-radius: 25px;
		  }
		  .main-bg p{
			line-height: 1.2;
		  }
		  .bg-white{
			background: #fff;
		  }
		  .text-center{
			text-align: center;
		  }
		  .p-5{
			padding: 50px;
		  }
		  .w-50{
			width: 50%;
		  }
		  .text-danger{
			color: red;
			font-weight: 600;
		  }
		</style>
		<body>
				<!--  -->
			  <div class="main-bg" style="width: 500px;background: #ffde59;margin: 25px auto;padding: 50px 110px;font-family: arial;">
			
				<div class="bg-white" style="background: #fff;">
				  <div class="text-center p-5" style="text-align: center;padding: 50px;">
			
					<img src="https://sp2.clickncash.in/ecommerce/admin/assets/images/logo-inverse.png" style="width: 50%;">
					<p style="line-height: 1.2;">Hello '.$name.'</p>
					<h2 class="text-danger" style="color: red;font-weight: 600;">Forget Password</h2>
			
					<p style="margin-bottom: 25px;line-height: 1.2;">If You have lost your password or wish to reset it, use the link below to get started.</p>
					<a href="'.SITE_URL.'reset-password?token='.$token.'" style="text-decoration: none;background: blue;color: #fff;padding: 10px 15px;border-radius: 25px;"><button class="btn btn-primary">Reset Password</button></a>
			
			          
					<p style="margin-top: 25px;line-height: 1.2;">If You did not request a password reset, you can safely ignore this email. Only a person with access to your email can reset your account password.</p>
				  </div>
				</div>
			  </div>
		</body>
		</html>';
				    
					$mail = new PHPMailer();
					$mail->isSMTP();
					$mail->SMTPDebug = 2;
					$mail->SMTPKeepAlive = true;
					$mail->Mailer = “smtp”;
					$mail->Host = "stuffking.in"; 
					$mail->Port = 587;
					$mail->SMTPSecure = 'tls';
					
					$mail->SMTPAuth = true;
					
					$mail->Username = 'support@stuffking.in';
					
					$mail->Password = 'Asia@2021';
					$mail->SetFrom('support@stuffking.in');
					$mail->FromName = "StuffKing";
					$mail->Subject = 'Reset Password || Stuffking';
					$mail->IsHTML(true);
					$mail->MsgHTML($message);				
					$mail->AddAddress($email);
						if($mail->Send()){
							
							$umessage ='success';
						} else {
							$umessage = "Mailer Error: " . $mail->ErrorInfo;
						}	
					   
					
					$mail->ClearAllRecipients();
					
		return $umessage;		
}

?>