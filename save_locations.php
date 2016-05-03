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
		if (empty($inputs['name'])){
			$errors.="Enter location name. <br/>";
		}
		if (empty($inputs['address'])){
			$errors.="Enter Address. <br/>";
		}

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("locations.php");
			}
			else{
				redirect("locations.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO locations(name,address) VALUES(:name,:address)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE locations SET name=:name,address=:address WHERE id=:id",$inputs);
			}
			
			Alert("Save succesful","success");
			redirect("locations.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>