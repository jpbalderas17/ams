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
			$errors.="Enter a manufacturer name. <br/>";
		}



		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("manufacturers.php");
			}
			else{
				redirect("manufacturers.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO manufacturers(name) VALUES(:name)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE manufacturers SET name=:name WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("manufacturers.php");
			die();
		}
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>