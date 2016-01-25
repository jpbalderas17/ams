<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		
		$errors="";
		if (empty($inputs['asset_id'])){
			$errors.="Select an asset. <br/>";
		}
		if (empty($inputs['asset_maintenance_type_id'])){
			$errors.="Select maintenance type. <br/>";
		}

		if (empty($inputs['title'])){
			$errors.="Enter maintenance title. <br/>";
		}
		if (empty($inputs['start_date'])){
			$errors.="Select start date. <br/>";
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_asset_maintenance.php");
			}
			else{
				redirect("frm_asset_maintenance.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{

			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				// echo "INSERT INTO asset_maintenances(asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes) VALUES('{$inputs['asset_id']}',{$inputs['asset_maintenance_type_id']},{$inputs['title']},{$inputs['start_date']},{$inputs['completion_date']},{$inputs['cost']},{$inputs['notes']})";
				$con->myQuery("INSERT INTO asset_maintenances(asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes) VALUES(:asset_id,:asset_maintenance_type_id,:title,:start_date,:completion_date,:cost,:notes)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE asset_maintenances SET asset_id=:asset_id,asset_maintenance_type_id=:asset_maintenance_type_id,title=:title,start_date=:start_date,completion_date=:completion_date,cost=:cost,notes=:notes WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("asset_maintenances.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>