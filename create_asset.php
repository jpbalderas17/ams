<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		if (empty($inputs['model_id'])){
			$errors.="Select a model. <br/>";
		}
		if (empty($inputs['asset_status_id'])){
			$errors.="Select a status for the asset. <br/>";
		}

		if( empty($inputs['purchase_cost'])){
			$inputs['purchase_cost']=0;
		}
		if( empty($inputs['purchase_date'])){
			$errors.="Select a purchase date for the asset. <br/>";
		}


		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			redirect("frm_assets.php");
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$asset_tag=date("Ynd");
				$stmt=$con->myQuery("INSERT INTO assets(asset_model_id,asset_status_id,serial_number,asset_name,purchase_date,purchase_cost,order_number,notes,location_id) VALUES(:model_id,:asset_status_id,:serial_number,:asset_name,:purchase_date,:purchase_cost,:order_number,:notes,:location_id)",$inputs);

				$asset_id=$con->lastInsertId();
				$asset_tag=date("Ynd").$asset_id;

				$con->myQuery("UPDATE assets SET asset_tag=? WHERE id=?",array($asset_tag,$asset_id));

				$con->myQuery("INSERT INTO activities(admin_id,action,action_date,category_type_id,item_id) VALUES(?,'Created Asset',NOW(),1,?)",array($_SESSION[WEBAPP]['user']['id'],$asset_id));
			}
			else{
				//Update
				$con->myQuery("UPDATE assets SET asset_model_id=:model_id,asset_status_id=:asset_status_id,serial_number=:serial_number,asset_name=:asset_name,purchase_date=:purchase_date,order_number=:order_number,purchase_cost=:purchase_cost,notes=:notes,location_id=:location_id WHERE id=:id",$inputs);
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