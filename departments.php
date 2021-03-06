<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!empty($_GET['id'])){
        $department=$con->myQuery("SELECT id,name FROM departments WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Department Selected.");
            redirect("departments.php");
        }
    }
	makeHead("Departments");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Departments</h1>
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
                            <form class='form-inline' method='POST' action='save_department.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label'> Department Name</label>
                                        <input type='text' class='form-control' name='name' placeholder='Enter Department Name' value='<?php echo !empty($department)?$department['name']:"" ?>'>
                                        <a href='departments.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($department)?'Are you sure you want to cancel the modification of the department?':'Are you sure you want to cancel the creation of the new department?';?>')">Cancel</a>
                                        <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
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
                                            <th class='text-center'>Department Name</th>
                                            <th class='text-center' style='max-width: 60px;width: 60px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td>
                                                    <a class='btn btn-flat btn-sm btn-success' href='departments.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=dep' onclick='return confirm("This department will be deleted.")'><span class='fa fa-trash'></span></a>
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
            "scrollY":"400px",
             "language": {
                "zeroRecords": "Department not found"
            }
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>