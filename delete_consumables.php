<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if(empty($_GET['id']) || empty($_GET['t'])){
		redirect('index.php');
		die;
	}
	else
	{
		$table="";
		switch ($_GET['t']) {
			case 'a':
				$table="assets";
				$page="assets.php";
				break;
			case 'c':
				$table="categories";
				$page="categories.php";
				break;
			default:
				# code...
				break;
		}
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>