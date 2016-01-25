<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

if(!AllowUser(array(1))){
		redirect("index.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		if (empty($inputs['name'])){
			$errors.="Enter label name. <br/>";
		}
		if (empty($inputs['asset_status_id'])){
			$errors.="Select label status. <br/>";
		}

		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("asset_status_labels.php");
			}
			else{
				redirect("asset_status_labels.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO asset_status_labels(name,asset_status_id) VALUES(:name,:asset_status_id)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE asset_status_labels SET name=:name,asset_status_id=:asset_status_id WHERE id=:id",$inputs);
			}
			
			Alert("Save succesful","success");
			redirect("asset_status_labels.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>