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
		if (empty($_POST['user_type_id'])){
			$errors.="Select user type. <br/>";
		}
		if (empty($_POST['first_name'])){
			$errors.="Enter first name. <br/>";
		}
		if (empty($_POST['middle_name'])){
			$errors.="Enter middle name. <br/>";
		}
		if (empty($_POST['first_name'])){
			$errors.="Enter first name. <br/>";
		}
		if (empty($_POST['username'])){
			$errors.="Enter username. <br/>";
		}
		if (empty($_POST['password'])){
			$errors.="Enter password. <br/>";	
		}
		if (empty($_POST['email'])){
			$errors.="Enter email address. <br/>";
		}

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("frm_users.php");
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['id']);
				//$inputs['name']=$_POST['name'];
				$con->myQuery("INSERT INTO users(user_type_id,first_name,middle_name,last_name,username, password,email,employee_no,contact_no,location_id,title,department_id) VALUES(:user_type_id,:first_name,:middle_name,:last_name,:username,:password, :email,:employee_no,:contact_no,:location_id,:title,:department_id)",$inputs);
				Alert("Save succesful","success");
			}
			else{				
				//Update
				
				$con->myQuery("UPDATE users SET user_type_id=:user_type_id,first_name=:first_name,middle_name=:middle_name,last_name=:last_name,username=:username,password=:password,email=:email,employee_no=:employee_no,contact_no=:contact_no,location_id=:location_id,title=:title,department_id=:department_id WHERE id=:id",$inputs);
				Alert("Update succesful","success");
			}
			
			redirect("user.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>