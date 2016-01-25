<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if($_POST['type']<>"out" && $_POST['type']<>"in" || (empty($_POST['id']))){
		redirect("assets.php");
	}
	
	$inputs=$_POST;

	switch ($inputs['type']) {
		case 'out':
			# Checkout
			# Validate input
			$error_msg="";
			if(empty($inputs['user_id'])){
				$error_msg.="Select a user.<br/>";
			}

			if(empty($inputs['checkout_date'])){
				$error_msg.="Select checkout date.<br/>";
			}


			if(!empty($error_msg)){
				Alert("You have the following errors: <br/>".$error_msg,"danger");
				redirect("check_asset.php?id=".$inputs['id']."&type=".$inputs['type']);
				die;
			}
			else
			{
				#Okay for Saving
				// $selected_user=$con->myQuery("SELECT id,location_id FROM users WHERE id=?",array($inputs['user_id']))->fetch(PDO::FETCH_ASSOC);
				#Validate if there is a selected user
				$asset_inputs['id']=$inputs['id'];
				$asset_inputs['asset_name']=$inputs['asset_name'];
				$asset_inputs['user_id']=$inputs['user_id'];
				$asset_inputs['check_out_date']=$inputs['checkout_date'];
				$asset_inputs['expected_check_in_date']=$inputs['expected_checkin_date'];
				$location_id=$con->myQuery("SELECT location_id FROM users WHERE id=?",array($_POST['user_id']))->fetchColumn();
				if(empty($location_id)){
					$asset_inputs['location_id']=0;
				}
				else
				{
					$asset_inputs['location_id']=$location_id;
				}
				
				#transaction here

				$con->myQuery("UPDATE assets SET user_id=:user_id,asset_name=:asset_name,check_out_date=:check_out_date,expected_check_in_date=:expected_check_in_date,location_id=:location_id WHERE id=:id",$asset_inputs);

				$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
				$activity_input['user_id']=$inputs['user_id'];
				$activity_input['notes']=$inputs['notes'];
				$activity_input['category_type_id']=1;
				$activity_input['item_id']=$inputs['id'];

				$con->myQuery("INSERT INTO activities(admin_id,user_id,action,notes,action_date,category_type_id,item_id) VALUES(:admin_id,:user_id,'Asset Checkout',:notes,NOW(),:category_type_id,:item_id)",$activity_input);
				
				#end of transaction 
			}
			break;
		case 'in':
			# Checkin
			# Validate input

			$error_msg="";

			if(empty($inputs['asset_status_id'])){
				$error_msg.="Select asset status.<br/>";
			}


			if(!empty($error_msg)){
				Alert("You have the following errors: <br/>".$error_msg,"danger");
				redirect("check_asset.php?id=".$inputs['id']."&type=".$inputs['type']);
				die;
			}
			else
			{
				#Okay for Saving
				$asset_inputs['id']=$inputs['id'];
				$asset_inputs['asset_name']=$inputs['asset_name'];
				
				#transaction here
				$location_id=$_SESSION[WEBAPP]['user']['location_id'];

				if(empty($location_id)){
					$asset_inputs['location_id']=0;
				}
				else
				{
					$asset_inputs['location_id']=$location_id;
				}

				$con->myQuery("UPDATE assets SET user_id=0,asset_name=:asset_name,check_out_date='0000-00-00',expected_check_in_date='0000-00-00',location_id=:location_id WHERE id=:id",$asset_inputs);

				$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
				$activity_input['user_id']="NULL";
				$activity_input['notes']=$inputs['notes'];
				$activity_input['category_type_id']=1;
				$activity_input['item_id']=$inputs['id'];

				// echo "INSERT INTO activities(admin_id,user_id,action,notes,action_date,category_type_id,item_id) VALUES({$activity_input['admin_id']},{$activity_input['user_id']},'Asset Checkin',{$activity_input['notes']},NOW(),{$activity_input['category_type_id']},{$activity_input['item_id']})";

				$con->myQuery("INSERT INTO activities(admin_id,user_id,action,notes,action_date,category_type_id,item_id) VALUES(:admin_id,:user_id,'Asset Checkin',:notes,NOW(),:category_type_id,:item_id)",$activity_input);
				
				#end of transaction 
			}
			break;
		default:
			redirect("assets.php");
			break;
	}
	Alert("Asset checked in.","success");
	redirect("assets.php");
	die;
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
		if (empty($inputs['asset_status_id'])){
			$errors.="Select a status for the asset. <br/>";
		} 

		if( empty($inputs['purchase_cost'])){
			$inputs['purchase_cost']=0;
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
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