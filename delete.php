<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
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
			case 'am':
				$table="asset_models";
				$page="asset_models.php";
				break;
			case 'c':
				$table="consumables";
				$page="consumables.php";
				break;
			case 'u':
				$table="users";
				$page="user.php";
			default:
				redirect("index.php");
				break;
		}
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>