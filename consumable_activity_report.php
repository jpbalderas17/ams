<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }
	
	  if(!empty($_GET['date_start'])){
    $date_start=date_create($_GET['date_start']);
  }
  else{
    $date_start="";
  }
  if(!empty($_GET['date_end'])){
    $date_end=date_create($_GET['date_end']);
  }
  else{
    $date_end="";
  }

$departments=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
$users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	
    makeHead("Consumable Activity Reports");
?>

<?php
     require_once("template/header.php");
	require_once("template/sidebar.php");
?>

<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Consumables Activity Report</h1>
    </div>
    <section class='content'>
        <div class="row">
            <div class='col-sm-12'>
                      <form method='get'>
                      <div class='row'>
                          
                              <label class='col-md-3 text-right' >Department</label>
                              <div class='col-md-3'>
                                <select id='departments' class='form-control' name='department_id' data-placeholder='Select Department' onchange='getUsers()' <?php echo !(empty($_GET['department_id']))?"data-selected='".$_GET['department_id']."'":NULL ?>>
                                    <?php echo makeOptions($departments) ?>
                                </select>
                              </div>
                          
    
                              <label class='col-md-3 text-right' >User</label>
                              <div class='col-md-3'>
                                <select id='users' class='form-control' name='user_id' data-placeholder='Select User' <?php echo !(empty($_GET['user_id']))?"data-selected='".$_GET['user_id']."'":NULL ?>>
                                <?php echo makeOptions($users) ?>
                                </select>
                              </div>
                          
                      </div>
                      <br/>
                          <div class='row'>
                          <label class='col-md-3 text-right' >Start Date</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_start' class='form-control' id='date_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                          </div>
                          
                          <label class='col-md-3 text-right' >End Date</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_end' class='form-control' id='date_end' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                          </div>    
                      </div>
                      
                      <div class='col-md-12 text-right'>
                      <br/>
                        <button type='submit'  class=' btn btn-success btn-flat' >Filter</button>
                      </div>
                      </form>
                    </div>
                    <br/>
                    <br/>
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            
                                            <th>Action Date</th>
                                            <th>Admin</th>
                                            <th>Name</th>
                                            <th>Order Number</th>
                                            <th>Action</th>
                                            <th>User</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                           

                                             <?php
                                             $date_filter="";
                                            if(!empty($date_start)){
                                                $date_filter.=" AND action_date >= '".date_format($date_start,'Y-m-d')."'";
                                            }

                                            if(!empty($date_end)){
                                                $date_filter.=" AND action_date <= '".date_format($date_end,'Y-m-d')."'";
                                            }
                                            
                                            $filter_sql="";
                                            $dep_sql="";
                                            $user_sql="";
                                            if(!empty($_GET['department_id'])){
                                                $dep_sql="u.department_id=:department_id";
                                                $inputs['department_id']=$_GET['department_id'];
                                                $filter_sql.=$dep_sql;
                                            }

                                            if(!empty($_GET['user_id'])){
                                                $user_sql="u.id=:user_id";
                                                $inputs['user_id']=$_GET['user_id'];
                                                if(!empty($filter_sql)){
                                                    $filter_sql.=" AND ";
                                                }
                                                $filter_sql.=$user_sql;
                                            }
                                            if(!empty($dep_sql) || !empty($user_sql)){
                                                $filter_sql="AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
                                                $assets=$con->myQuery("SELECT DATE_FORMAT(action_date,'%m/%d/%Y'),(SELECT CONCAT(first_name,' ',middle_name,' ',last_name)  FROM users WHERE id=admin_id) AS admin,NAME, order_number,ACTION,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name) FROM users WHERE id=a.user_id)AS USER,notes FROM consumables AS c LEFT JOIN activities AS a ON a.item_id=c.id WHERE category_type_id=2 and is_deleted=0 {$date_filter} {$filter_sql}",$inputs)->fetchAll(PDO::FETCH_ASSOC);
                                            }
                                            else{
                                                $filter_sql="";
                                                $assets=$con->myQuery("SELECT DATE_FORMAT(action_date,'%m/%d/%Y'),(SELECT CONCAT(first_name,' ',middle_name,' ',last_name)  FROM users WHERE id=admin_id) AS admin,NAME, order_number,ACTION,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name) FROM users WHERE id=a.user_id)AS USER,notes FROM consumables AS c LEFT JOIN activities AS a ON a.item_id=c.id WHERE category_type_id=2 and is_deleted=0 {$date_filter} {$filter_sql}")->fetchAll(PDO::FETCH_ASSOC);
                                            }

                                            
                                            foreach ($assets as $asset):
                                            ?> <tr>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                ?>
                                                 
                                                    <td>
                                                        <?php
                                                            echo htmlspecialchars($value);
                                                        ?>
                                                    </td>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </tr>
                                        <?php
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>


<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY":"400px",
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span style='color:#fff'><span class='fa fa-download'></span> Download Excel </span>",
                        className:"btn btn-success btn-flat",
                        "extension":".xls"
                    }
                    ]
        });

        $('#departments').select2({
          allowClear:true
        });
        $('#users').select2({
          allowClear:true
        });
    });
    function getUsers() {
        // console.log($("#departments").val());
        $("#users").val(null).trigger("change"); 
        $("#users").load("ajax/cb_users.php?d_id="+$("#departments").val());
    }
    </script>
<?php
    Modal();
    makeFoot();
?>