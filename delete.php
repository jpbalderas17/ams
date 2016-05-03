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
				$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
				$activity_input['item_id']=$_GET['id'];
				$con->myQuery("INSERT INTO activities(admin_id,action,action_date,category_type_id,item_id) VALUES(:admin_id,'Deleted Asset',NOW(),1,:item_id)",$activity_input);
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
			case 'mt':
				$table="asset_maintenance_types";
				$page="maintenance_types.php";
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
			case 'man':
				$table="manufacturers";
				$page="manufacturers.php";
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