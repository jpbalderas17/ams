<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if (empty($_GET['status']) ){
        $status="All";
    }
    else{
        $status=$_GET['status'];
    }

    $departments=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Assets");
?>

<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class='page-header text-center text-green'>
            Assets
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                                        <div class='col-sm-12'>
                      <form method='get'>
                      <div class=''>
                          <label class='col-md-2 text-right' >Department</label>
                          <div class='col-md-3'>
                            <select id='departments' class='form-control' name='department_id' data-placeholder='Select Department' onchange='getUsers()' <?php echo !(empty($_GET['department_id']))?"data-selected='".$_GET['department_id']."'":NULL ?>>
                                <?php echo makeOptions($departments) ?>
                            </select>
                          </div>
                      </div>
                      <div class=''>
                          <label class='col-md-2 text-right' >User</label>
                          <div class='col-md-3'>
                            <select id='users' class='form-control' name='user_id' data-placeholder='Select User' <?php echo !(empty($_GET['user_id']))?"data-selected='".$_GET['user_id']."'":NULL ?>>
                            <?php echo makeOptions($users) ?>
                            </select>
                          </div>
                      </div>
                      
                      <div class='col-md-2'>
                        <button type='submit'  class='btn-flat btn btn-success' >Filter</button>
                      </div>
                      </form>
                    </div>
                    <br/>
                    <br/>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_assets.php' class='btn btn-success pull-right btn-flat'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body table-responsive' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <?php
                                            if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                                            ?>
                                                <th></th>
                                            <?php
                                            endif;
                                            ?>
                                            <th class='text-center'>Asset Tag</th>
                                            <!-- <th class='text-center'>Serial Number</th> -->
                                            <th class='text-center' style="min-width: 150px">Asset Name</th>
                                            <th class='text-center'>Model</th>
                                            <th class='text-center'>Status</th>
                                            <th class='text-center'>Location</th>
                                            <th class='text-center'>Category</th>
                                            <!-- <th class='text-center date-td'>EOL</th> -->
                                            <th class='text-center'>Order Number</th>
                                            <th class='text-center date-td'>Checkout Date</th>
                                            <th class='text-center date-td'>Expected Checkin Date</th>
                                            <!-- <th class='text-center'>Notes</th> -->
                                            <th style='min-width:150px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
  </div>
<div class="modal fade" id="barcode_modal" tabindex="-1" role="dialog" aria-labelledby="barcode_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="barcode_modal_label">View Barcode</h4>
      </div>
      <div class="modal-body text-center" id='barcode_view'>
        
      </div>
      <div class="modal-footer">
        <a href=''  class="btn btn-flat btn-success" id='barcode_download'>Download</a>
      </div>
    </div>
  </div>
</div>
<script>
    <?php
         $filter_sql="";
        $dep_sql="";
        $user_sql="";
        $get_val="";
        if(!empty($_GET['department_id'])){
            $dep_sql="u.department_id=:department_id";
            $inputs['department_id']=$_GET['department_id'];
            $filter_sql.=$dep_sql;
            $get_val.="d_id=".urlencode($_GET['department_id']);
        }

        if(!empty($_GET['user_id'])){
            $user_sql="u.id=:user_id";
            $inputs['user_id']=$_GET['user_id'];
            if(!empty($filter_sql)){
                $filter_sql.=" AND ";
            }
            $filter_sql.=$user_sql;
            $get_val.="&u_id=".urlencode($_GET['user_id']);
        }
        if(!empty($dep_sql) || !empty($user_sql)){
            $filter_sql="AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
        }
        else{
            $filter_sql="";
        }
    ?>
    $(document).ready(function() {
        var dttable=$('#dataTables').DataTable({
                "scrollY":"400px",
                //"scrollX": true,
                "processing": true,
                "serverSide": true,
                "select":true,
                "ajax":{
                  "url":"ajax/assets.php?<?php echo $get_val?>",
                  "data":function(d){
                    d.status='<?php echo $status; ?>'
                  }
                },"language": {
                    "zeroRecords": "Asset not found"
                },
                order:[[1,'desc']]
                ,"columnDefs": [
                    { "orderable": false, "targets": [0] }
                  ] 
        });

        $('#departments').select2({
          allowClear:true
        });
        $('#users').select2({
          allowClear:true
        });

        // $('#dataTables tbody').on( 'dblclick', 'tr', function () {
        //     //self.Editor.edit( this );
        //     console.log(this);
        // } );
        
    });
        function getUsers() {
        // console.log($("#departments").val());
        $("#users").val(null).trigger("change"); 
        $("#users").load("ajax/cb_users.php?d_id="+$("#departments").val());
    }
    function get_barcode(id) {
        $('#barcode_modal').modal('show');
        $("#barcode_view").html("<img src='barcode/view.php?id="+id+"'>");
        $("#barcode_download").attr("href","barcode/download.php?id="+id);
    }
    </script>
<?php
    Modal();
	makeFoot();
?>