<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $category=$con->myQuery("SELECT categories.id,categories.name,category_types.name as asset_type,category_type_id FROM `categories` JOIN category_types ON categories.category_type_id=category_types.id WHERE categories.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($category)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Category Selected");
            redirect("categories.php");
            die();
        }
    }

    $category_types=$con->myQuery("SELECT id,name FROM category_types")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Categories");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Category Form</h1>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='row'>
                    	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                    		<form class='form-horizontal' method='POST' action='save_category.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($category)?$category['id']:""?>'>
                    			
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Category Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='name' placeholder='Enter Category Name' value='<?php echo !empty($category)?$category['name']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Category Type</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='category_type_id' data-placeholder="Select a Category" <?php echo!(empty($category))?"data-selected='".$category['category_type_id']."'":NULL ?>>
                    						<?php
                    							echo makeOptions($category_types);
                    						?>
                    					</select>
                    				</div>
                    			</div>
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='assets.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>
                    			
                    		</form>
                    	</div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
</div>
<?php
Modal();
?>
<?php
	makeFoot();
?>