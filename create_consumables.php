<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST)){
		//Validate form inputs

		$errors="";
		if (empty($_POST['name'])){
			$errors.="Enter consumable name. <br/>";
		}
		if (empty($_POST['order_number'])){
			$errors.="Enter order number. <br/>";
		}
		if (empty($_POST['purchase_date'])){
			$errors.="Provide Purchase Date. <br/>";
		}
		if (empty($_POST['purchase_cost'])){
			$errors.="Enter purchase cost. <br/>";
		}
		if (empty($_POST['quantity'])){
			$errors.="Enter quantity of order. <br/>";
		}


		if($errors!=""){

			Alert("You the following errors: <br/>".$errors,"danger");
			redirect("frm_consumables.php");
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($_POST['id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['id']);
				//$inputs['name']=&_POST['name'];
				$con->myQuery("INSERT INTO consumables(name,order_number,purchase_date,purchase_cost,quantity) VALUES(:name,:order_number,:purchase_date,:purchase_cost,:quantity)",$inputs);
			}
			else{				
				//Update
				$inputs=$_POST;
				unset($inputs['id']);
				$con->myQuery("UPDATE consumables SET name=:name,order_number=:order_number,purchase_date=:purchase_date,purchase_cost=:purchase_cost,quantity=:quantity, WHERE id=:id")

			}

			Alert("Save succesful","success");
			redirect("consumables.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>