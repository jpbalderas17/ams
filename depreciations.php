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
        $department=$con->myQuery("SELECT id,name,terms FROM depreciations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Depreciation Selected.");
            redirect("depreciations.php");
        }
    }
	makeHead("Depreciations");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Depreciations</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='align-center'>
                            <form class='form-horizontal' method='POST' action='save_depreciations.php'>

                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label col-md-2'> Depreciation Name</label>
                                    <div class='col-md-3'>
                                        <input type='text' class='form-control' name='name' placeholder='Enter Depreciation Name' value='<?php echo !empty($department)?$department['name']:"" ?>'>
                                    </div>

                                    <label class=' control-label col-md-2'> Terms(Months)</label>
                                    <div class='col-md-3'>
                                        <input type='text' class='form-control unsigned_integer' name='terms' placeholder='Enter Terms (Months)' value='<?php echo !empty($department)?$department['terms']:"" ?>'>
                                    </div>
                                    <div class='col-md-2'>
                                        
                                        <a href='depreciations.php' class='btn btn-flat btn-default' onclick='return confirm("<?php echo !empty($department)?"Are you sure you want to cancel modification of this depreciation?":"Are you sure you want to cancel creation of the new depreciation?"?>")'>Cancel</a>
                                        <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                </div>

                            </form>
                            </div>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            
                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th>Depreciation Name</th>
                                            <th>Terms</th>
                                            <th style='max-width: 60px;width: 60px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT id,name,terms FROM depreciations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td><?php echo htmlspecialchars($category['terms'])?></td>
                                                <td>
                                                    <a class='btn btn-flat btn-sm btn-success' href='depreciations.php?id=<?php echo $category['id'];?>' ><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=depr' onclick='return confirm("Are you sure you want to delete this depreciation?")'><span class='fa fa-trash'></span></a>
                                                </td>
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
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
            "scrollY":"400px"
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>