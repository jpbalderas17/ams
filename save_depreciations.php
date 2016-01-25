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
		if (empty($inputs['name'])){
			$errors.="Enter a depreciations name. <br/>";
		}

		if (empty($inputs['terms'])){
			$errors.="Enter number of terms. <br/>";
		}


		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("depreciations.php");
			}
			else{
				redirect("depreciations.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO depreciations(name,terms) VALUES(:name,:terms)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE depreciations SET name=:name,terms=:terms WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("depreciations.php");
		}
		die();
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>