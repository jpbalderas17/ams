<?php
	require_once 'support/config.php';
	
	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}

	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		$inputs=array_map("trim", $inputs);

		$errors="";
		
		if (empty($inputs['model_name'])){
			$errors.="Enter Model Name. <br/>";
		}

		if (empty($inputs['model_no'])){
			$errors.="Enter Model Number. <br/>";
		}

		if (empty($inputs['manufacturer_id'])){
			$errors.="Select a Manufacturer. <br/>";
		}

		if (empty($inputs['category_id'])){
			$errors.="Select a Category. <br/>";
		}

		if (empty($inputs['depreciation_id'])){
			$errors.="Select a Depreciation. <br/>";
		}

		if (empty($inputs['EOL']) || $inputs['EOL']=="mm/dd/yyyy"){
			$errors.="Enter End of Life. <br/>";
		}
		else{
			$inputs['EOL']=format_date($inputs['EOL']);
		}
		// var_dump($inputs);
		// die;
		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			$_SESSION[WEBAPP]['frm_inputs']['name']=$inputs['model_name'];
			unset($_SESSION[WEBAPP]['frm_inputs']['model_name']);
			Alert("You have the following errors: <br/>".$errors,"danger");
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