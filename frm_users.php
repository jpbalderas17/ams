<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }

if(!AllowUser(array(1))){
        redirect("index.php");
    }

    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT id,user_type_id,first_name,middle_name,last_name,username,password,email,contact_no,employee_no,location_id,title,department_id from users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid user selected");
            redirect("users.php");
            die();
        }
    }
    
<<<<<<< HEAD
    $department=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $location=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $user_type=$con->myQuery("SELECT id,name FROM user_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);                                      
=======
    $department=$con->myQuery("SELECT id,name FROM departments where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $location=$con->myQuery("SELECT id,name FROM locations where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $user_type=$con->myQuery("SELECT id,name FROM user_types ")->fetchAll(PDO::FETCH_ASSOC);                                      
>>>>>>> origin/master
    makeHead("Users");
?>
<div id='wrapper'>
<?php
     require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Users Form</h1>
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
                            <form class='form-horizontal' method='POST' action='create_users.php' enctype="multipart/form-data">
                                <input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> User Type*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='user_type_id' data-placeholder='Select User Type' <?php echo!(empty($asset))?"data-selected='".$asset['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> First name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter First Name' name='first_name' value='<?php echo !empty($asset)?$asset['first_name']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Middle name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Middle Name' name='middle_name' value='<?php echo !empty($asset)?$asset['middle_name']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Last name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Last Name' name='last_name' value='<?php echo !empty($asset)?$asset['last_name']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Username*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Username' name='username' value='<?php echo !empty($asset)?$asset['username']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Password*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='password' class='form-control' placeholder='Enter Password' name='password' value='<?php echo !empty($asset)?$asset['password']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Email Address' name='email' value='<?php echo !empty($asset)?$asset['email']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Employee Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Employee Number' name='employee_no' value='<?php echo !empty($asset)?$asset['employee_no']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Position</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Position' name='title' value='<?php echo !empty($asset)?$asset['title']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Department</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='department_id' data-placeholder='Select Department' <?php echo!(empty($asset))?"data-selected='".$asset['department_id']."'":NULL ?>>
                                                    <?php
                                                    echo makeOptions($department);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='departments.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Contact Number' name='contact_no' value='<?php echo !empty($asset)?$asset['contact_no']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Location</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='location_id' data-placeholder='Select Location' <?php echo!(empty($asset))?"data-selected='".$asset['location_id']."'":NULL ?>>
                                                    <?php
                                                    echo makeOptions($location);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='locations.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>                                
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='user.php' class='btn btn-default'>Cancel</a>
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