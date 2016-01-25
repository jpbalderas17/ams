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
		if (empty($inputs['name'])){
			$errors.="Enter a Maintenance Type. <br/>";
		}



		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("maintenance_types.php");
			}
			else{
				redirect("maintenance_types.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO asset_maintenance_types(name) VALUES(:name)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE asset_maintenance_types SET name=:name WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("maintenance_types.php");
			die();
		}
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>