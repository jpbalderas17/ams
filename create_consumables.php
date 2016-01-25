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
		if (empty($_POST['name'])){
			$errors.="Enter consumable name. <br/>";
		}
		if (empty($_POST['order_number'])){
			$errors.="Enter order number. <br/>";
		}
		if (empty($_POST['category_type'])){
			$errors.="Select category type. <br/>";
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

			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("frm_consumables.php?id=".$inputs['id']);
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['id']);
				//$inputs['name']=$_POST['name'];
				$con->myQuery("INSERT INTO consumables(name,order_number,purchase_date,purchase_cost,quantity,category_id) VALUES(:name,:order_number,:purchase_date,:purchase_cost,:quantity,:category_type)",$inputs);
					$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
					$activity_input['user_id']=$_SESSION[WEBAPP]['user']['id'];
					$activity_input['category_type_id']=2;

				$con->myQuery("INSERT INTO activities(admin_id,user_id,action,action_date,category_type_id) VALUES(:admin_id,:user_id,'Consumable Created',NOW(),:category_type_id)",$activity_input);
				Alert("Save succesful","success");

			}
			else{				
				//Update
				$activity_input['item_id']=$inputs['id'];
				$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
				$activity_input['user_id']=$_SESSION[WEBAPP]['user']['id'];
				$activity_input['category_type_id']=2;
				//$totalQty=$current_quantity-$inputs['quantity'];
				$current_quantity=$con->myQuery("SELECT quantity FROM consumables WHERE id=:id",array('id'=>$inputs['id']))->fetchColumn();

					if ($inputs['quantity'] > $current_quantity) {
						$activity_input['notes']="Quantity (".($inputs['quantity']-$current_quantity).")";
						// echo $inputs['quantity']." - ".$current_quantity." = ".($inputs['quantity']-$current_quantity);
						
						$con->myQuery("UPDATE consumables SET name=:name,order_number=:order_number,purchase_date=:purchase_date,purchase_cost=:purchase_cost,quantity=:quantity,category_id=:category_type WHERE id=:id",$inputs);
						$con->myQuery("INSERT INTO activities(admin_id,user_id,action,action_date,category_type_id,item_id,notes) VALUES(:admin_id,:user_id,'Consumable Checkin',NOW(),:category_type_id,:item_id,:notes)",$activity_input);

					}	
					elseif ($inputs['quantity'] < $current_quantity) {
						$activity_input['notes']="Quantity (".($current_quantity-$inputs['quantity']).")";
						// echo $current_quantity." - ".$inputs['quantity']." = ".($current_quantity-$inputs['quantity']);

						$con->myQuery("UPDATE consumables SET name=:name,order_number=:order_number,purchase_date=:purchase_date,purchase_cost=:purchase_cost,quantity=:quantity,category_id=:category_type WHERE id=:id",$inputs);
						$con->myQuery("INSERT INTO activities(admin_id,user_id,action,action_date,category_type_id,item_id,notes) VALUES(:admin_id,:user_id,'Consumable Checkout',NOW(),:category_type_id,:item_id,:notes)",$activity_input);
						
					}
					elseif ($inputs['quantity'] = $current_quantity) {
						$con->myQuery("UPDATE consumables SET name=:name,order_number=:order_number,purchase_date=:purchase_date,purchase_cost=:purchase_cost,quantity=:quantity,category_id=:category_type WHERE id=:id",$inputs);
						Alert("Update succesful","success");
					}
			}
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