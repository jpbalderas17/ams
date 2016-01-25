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
				break;
			case 'dep':
				$table="departments";
				$page="departments.php";
				break;
			case 'depr':
				$table="depreciations";
				$page="depreciations.php";
				break;
			case 'asl':
				$table="asset_status_labels";
				$page="asset_status_labels.php";
				break;
			case 'l':
				$table="locations";
				$page="locations.php";
				break;
			case 'fu':
				$table="files";
				$page="assets.php";
				if(!empty($_GET['a'])){
					#asset_id
					$page="view_asset.php?id={$_GET['a']}";
				}
				break;
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