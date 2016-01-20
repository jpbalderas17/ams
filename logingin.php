<?php
	require_once 'support/config.php';
	if(!empty($_POST)){

		$user=$con->myQuery("SELECT first_name,middle_name,last_name,id,location_id,user_type_id as user_type FROM users WHERE username=? AND password=?",array($_POST['username'],$_POST['password']))->fetch(PDO::FETCH_ASSOC);
		if(empty($user)){
			Alert("Invalid Username/Password","success");
			redirect('frmlogin.php');
		}
		else{
			$_SESSION[WEBAPP]['user']=$user;
			redirect("index.php");	
		}
		die;
	}
	else{
		redirect('frmlogin.php');
		die();
	}
	redirect('frmlogin.php');
?>