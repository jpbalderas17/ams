<?php
	require_once 'support/config.php';
	if(!empty($_POST)){

		$user=$con->myQuery("SELECT first_name,middle_name,last_name FROM users WHERE username=? AND password=?",array($_POST['username'],$_POST['password']))->fetch(PDO::FETCH_ASSOC);
		if(empty($user)){
			//$_SESSION[WEBAPP]['message']='Invalid Username/Password';
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