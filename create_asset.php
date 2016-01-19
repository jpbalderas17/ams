<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST)){
		//Validate form inputs

		$errors="";
		if (empty($_POST['model_id'])){
			$errors.="Select a model. <br/>";
		}
		if (empty($_POST['asset_status_id'])){
			$errors.="Select a status for the asset. <br/>";
		}

		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			redirect("frm_assets.php");
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($_POST['id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['id']);
				$inputs['asset_tag']="2-124125";
				$con->myQuery("INSERT INTO assets(asset_model_id,asset_status_id,asset_tag,serial_number,asset_name,purchase_date,purchase_cost,order_number,notes,location_id) VALUES(:model_id,:asset_status_id,:asset_tag,:serial_number,:asset_name,:purchase_date,:purchase_cost,:order_number,:notes,:location_id)",$inputs);
			}
			else{
				//Update
			}

			Alert("Save succesful","success");
			redirect("assets.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>