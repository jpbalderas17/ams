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
		
		if (empty($inputs['model_name'])){
			$errors.="Enter Model Name. <br/>";
		}

		if (empty($inputs['manufacturer_id'])){
			$errors.="Select a Manufacturer. <br/>";
		}

		if (empty($inputs['category_id'])){
			$errors.="Select a Category. <br/>";
		}

		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_asset_models.php");
			}
			else{
				redirect("frm_asset_models.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO asset_models(name,manufacturer_id,category_id,model_no,EOL,depreciation_id) VALUES(:model_name,:manufacturer_id,:category_id,:model_no,:EOL,:depreciation_id)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE asset_models SET name=:model_name,manufacturer_id=:manufacturer_id,category_id=:category_id,model_no=:model_no,EOL=:EOL,depreciation_id=:depreciation_id WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("asset_models.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>