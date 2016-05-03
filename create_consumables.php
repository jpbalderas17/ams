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
		// var_dump($inputs);

		$inputs=array_map('trim',$_POST);
		$inputs['purchase_cost']=floatval($inputs['purchase_cost']);
		$inputs['quantity']=intval($inputs['quantity']);
		
		// var_dump($inputs);
		// die;
		$errors="";
		if (empty($inputs['name'])){
			$errors.="Enter consumable name. <br/>";
		}
		if (empty($inputs['order_number'])){
			$errors.="Enter order number. <br/>";
		}
		if (empty($inputs['category_type'])){
			$errors.="Select category type. <br/>";
		}
		if (empty($inputs['purchase_date']) || $inputs['purchase_date']=="mm/dd/yyyy"){
			$errors.="Provide Purchase Date. <br/>";
		}
		else{
			$inputs['purchase_date']=format_date($inputs['purchase_date']);
		}
		if (empty($inputs['purchase_cost'])){
			$errors.="Enter purchase cost. <br/>";
		}

		if (empty(intval($inputs['quantity']))){
			$inputs['quantity']=0;
			if(empty($inputs['id'])){
				$errors.="Invalid quantity. <br/>";
			}
		}


		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// var_dump($_SESSION[WEBAPP]['frm_inputs']);
			// die;
			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("frm_consumables.php?id=".$inputs['id']);
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				//$inputs=$_POST;
				
				//$inputs['name']=$_POST['name'];
				unset($inputs['id']);

				$con->myQuery("INSERT INTO consumables(name,order_number,purchase_date,purchase_cost,quantity,category_id) VALUES(:name,:order_number,:purchase_date,:purchase_cost,:quantity,:category_type)",$inputs);
					$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
					$activity_input['user_id']=$_SESSION[WEBAPP]['user']['id'];
					$activity_input['category_type_id']=2;
					$activity_input['notes']="Quantity (".$inputs['quantity'].")";
					$activity_input['item_id']=$con->lastInsertId();
			
				$con->myQuery("INSERT INTO activities(admin_id,user_id,action,action_date,category_type_id,item_id,notes) VALUES(:admin_id,:user_id,'Consumable Created',NOW(),:category_type_id,:item_id,:notes)",$activity_input);
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