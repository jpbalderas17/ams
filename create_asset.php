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

		$inputs=array_map('trim', $inputs);
		$errors="";
		if (empty($inputs['model_id'])){
			$errors.="Select a model. <br/>";
		}
		if (empty($inputs['asset_status_id'])){
			$errors.="Select a status for the asset. <br/>";
		}

		if (empty($inputs['serial_number'])){
			$errors.="Enter serial number for the asset. <br/>";
		}

		if (empty($inputs['asset_name'])){
			$errors.="Enter asset name. <br/>";
		}

		if (empty($inputs['order_number'])){
			$errors.="Enter order number. <br/>";
		}

		if( empty($inputs['purchase_cost'])){
			$inputs['purchase_cost']=0;
		}
		if( empty($inputs['purchase_date']) || $inputs['purchase_date']=="mm/dd/yyyy"){
			$errors.="Select a purchase date for the asset. <br/>";
		}
		else{
			try {
				$purchase_date=format_date($inputs['purchase_date']);
				
			} catch (Exception $e) {
				$errors.="Invalid purchase date entered.";
			}
			if(!empty($purchase_date)){
				$inputs['purchase_date']=$purchase_date;
			}
			else{
				$inputs['purchase_date']="";
			}
			// die;
		}
		if( empty($inputs['location_id'])){
			$errors.="Select a location. <br/>";
		}

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			$_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_assets.php");
			}
			else{
				redirect("frm_assets.php?id=".urlencode($inputs['id']));
			}
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
				$file_sql="";
				$insert=array('asset_tag'=>$asset_tag,'asset_id'=>$asset_id);
				if(!empty($_FILES['image']['name'])){
					$filename=$asset_id.getFileExtension($_FILES['image']['name']);
					move_uploaded_file($_FILES['image']['tmp_name'],"asset_images/".$filename);
					$file_sql=",image=:image";
					$insert['image']=$filename;
				}

				$con->myQuery("UPDATE assets SET asset_tag=:asset_tag{$file_sql} WHERE id=:asset_id",$insert);

				$con->myQuery("INSERT INTO activities(admin_id,action,action_date,category_type_id,item_id) VALUES(?,'Created Asset',NOW(),1,?)",array($_SESSION[WEBAPP]['user']['id'],$asset_id));
			}
			else{
				//Update

				$file_sql="";
				if(!empty($_FILES['image']['name'])){
					$filename=$inputs['id'].getFileExtension($_FILES['image']['name']);
					move_uploaded_file($_FILES['image']['tmp_name'],"asset_images/".$filename);
					$file_sql=",image=:image";
					$inputs['image']=$filename;
					// var_dump($inputs);
				}

				// echo "<br/>UPDATE assets SET asset_model_id=:model_id,asset_status_id=:asset_status_id,serial_number=:serial_number,asset_name=:asset_name,purchase_date=:purchase_date,order_number=:order_number,purchase_cost=:purchase_cost,notes=:notes,location_id=:location_id{$file_sql} WHERE id=:id";
				$con->myQuery("UPDATE assets SET asset_model_id=:model_id,asset_status_id=:asset_status_id,serial_number=:serial_number,asset_name=:asset_name,purchase_date=:purchase_date,order_number=:order_number,purchase_cost=:purchase_cost,notes=:notes,location_id=:location_id{$file_sql} WHERE id=:id",$inputs);
			}
			// die();
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