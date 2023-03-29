<?php require_once('function.php');
 function sendRegisterMail($name,$email)
 {
	
	$email_t = explode(',',$email);
	$subject = "Registration Mail - Kodeahash";
	
	  $message='<p><div style="background-color:#f4f4f4">
					<table style="min-width:100%;border-spacing:0" width="100%" cellspacing="0" cellpadding="0">
						 <tbody>
							<tr>
								<td style="min-width:100%;background-color:#f4f4f4;padding:10px" width="100%">
									<center>
										<table style="margin:0 auto;border-spacing:0" width="870" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td style="text-align:left" width="100%">
														<table style="min-width:100%;background-color:#2457aa;color:#000000;padding:30px;border-spacing:0">
														
															<tbody>
																<tr style="display:flex">
																</tr>
															</tbody>
														</table>
														
													<table style="min-width:100%;width:100%;border-spacing:0" width="100%" cellspacing="0" cellpadding="0">
														<tbody>
															<tr>
																<td style="min-width:100%;background-color:#f6f9ff;color:#58585a;padding:30px" width="100%">
																	<h1 style="text-align:center">Contact Successful</h1>
																	<p>Hello '.$name.', You have contacted to Kodehash Team. We will get back to you as soon as possible.</p>
																	<hr>
																	<p>
																		Best Regards,<br>
																		Kodehash<br>
																		<br>
																	</P>
																</td>
															</tr>
														</tbody>
													
													</table>
						
													</td>
												</tr>
											</tbody>
										</table>
									</center>
								</td>
							</tr>
						 </tbody>
					</table>
				</div></p>';
	 
	               
					//Set who the message is to be sent from
					
					$mail = new PHPMailer(true);
					$mail->isSMTP();
					
					// $mail->SMTPDebug = 3;
					$mail->SMTPKeepAlive = true;
					$mail->Mailer = "smtp";
					$mail->Host = "tls://smtp.gmail.com";
					$mail->Port = 587;
					$mail->SMTPSecure = 'tls';
					
					$mail->SMTPAuth = true;
					
					$mail->Username = 'kanavgilhotra89@gmail.com';
					$mail->Password = 'miyisghdbwbpujmp';
					$mail->SetFrom('kanavgilhotra89@gmail.com');
					$mail->FromName = "Kodehash";
					$mail->Subject = 'Contact Successful || Kodehash';
					$mail->IsHTML(true);
					$mail->MsgHTML($message);
					
				foreach($email_t as $key=>$index){
				  
						$to = $email_t[$key];
					    $mail->AddAddress($to);
						if($mail->Send()){
							
							$umessage='success';
						} else {
							$umessage='failed';
						}	   
						$mail->ErrorInfo;
						$mail->ClearAllRecipients();
					}
		return $umessage;		
 }
		/******** Admin Mail **********/

    function sendRegisterAdminMail($name,$email,$phone,$city,$state)
		 {
			$main_site_info=sqlfetch("SELECT * FROM company_profile ORDER by id desc limit 1");
			$main_site_info = $main_site_info[0];
			$send_mail_to = explode(',',$main_site_info['email']);
			$subject = "Vendor Registration - StuffKing";
			
			
				 $message.='<p><div style="background-color:#f4f4f4">
					<table style="min-width:100%;border-spacing:0" width="100%" cellspacing="0" cellpadding="0">
						 <tbody>
							<tr>
								<td style="min-width:100%;background-color:#f4f4f4;padding:10px" width="100%">
									<center>
										<table style="margin:0 auto;border-spacing:0" width="870" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td style="text-align:left" width="100%">
														<table style="min-width:100%;background-color:#2457aa;color:#000000;padding:30px;border-spacing:0">
														
															<tbody>
																<tr style="display:flex">
																</tr>
															</tbody>
														</table>
														
													<table style="min-width:100%;width:100%;border-spacing:0" width="100%" cellspacing="0" cellpadding="0">
														<tbody>
															<tr>
																<td style="min-width:100%;background-color:#f6f9ff;color:#58585a;padding:30px" width="100%">
																	<h1 style="text-align:center">Contact Successful</h1>
																	<p>A New Vendor has been registered with the following details</p>
																	<hr>
															
																	<p><b>Name  :</b>'.$name.'</p>
																	<p><b>Email :</b>'.$email.'</p>
																	<p><b>Phone :</b>'.$phone.'</p>
																	<p><b>City  :</b>'.$city.'</p>
																	<p><b>State :</b>'.$state.'</p>
																	
																	<p>
																		Best Regards,<br>
																		Kodehash<br>
																		<br>
																	</P>
																</td>
															</tr>
														</tbody>
													
													</table>
						
													</td>
												</tr>
											</tbody>
										</table>
									</center>
								</td>
							</tr>
						 </tbody>
					</table>
				</div></p>';
						   $email_t = $send_mail_to;
							//Set who the message is to be sent from
							
							$mail = new PHPMailer();
							$mail->isSMTP();
							$mail->SMTPDebug = 2;
							$mail->SMTPKeepAlive = true;
							$mail->Mailer = “smtp”;
							$mail->Host = "ssl://smtp.gmail.com"; 
							
							$mail->Port = 587;
							$mail->SMTPSecure = 'tls';
							//$mail->SMTPAutoTLS = false;
							//Whether to use SMTP authentication
							$mail->SMTPAuth = true;
							
							$mail->Username = 'pg0462771@gmail.com';
							
							$mail->Password = 'priti@123';
							$mail->SetFrom('pg0462771@gmail.com');
							$mail->FromName = "StuffKing";
							$mail->Subject = 'Registration Conformation || Stufking';
							$mail->IsHTML(true);
							$mail->MsgHTML($message);
							
						foreach($email_t as $key=>$index){
						   // echo 'yes';
						$to = $email_t[$key]; // note the comma
						//print_r($to);die;
							$mail->AddAddress($to);
						if($mail->Send()){
							
							$umessage='success';
						} else {
							$umessage='failed';
						}	
							   // echo 'yes';
							   //var_dump($mail);
							$mail->ErrorInfo;
							$mail->ClearAllRecipients();
							}
				return $umessage;		
		 }		

?>