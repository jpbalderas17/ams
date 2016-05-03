<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!empty($_GET['id'])){
        $department=$con->myQuery("SELECT id,name FROM manufacturers WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Manufacturer Selected.");
            redirect("manufacturers.php");
        }
    }
	makeHead("Manufacturers");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <h1 class="page-header text-center text-green"> Manufacturers</h1>
    <section class='content'>
      <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='align-center'>
                            <form class='form-inline' method='POST' action='save_manufacturers.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label'> Manufacturer Name</label>
                                        <input type='text' class='form-control' name='name' placeholder='Enter Manufacturer Name' value='<?php echo !empty($department)?$department['name']:"" ?>' required>
                                        <a href='manufacturers.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($category)?'Are you sure you want to cancel the modification of this manufacturer?':'Are you sure you want to cancel the creation of the new manufacturer?';?>')">Cancel</a>
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
                                            <th>Manufacturer </th>
                                            <th style='max-width:60px;width:60px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT id,name FROM manufacturers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td>
                                                    <a class='btn btn-flat btn-sm btn-success' href='manufacturers.php?id=<?php echo $category['id'];?>' ><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=man' onclick='return confirm("Are you sure you want to delete this manufacturer?")'><span class='fa fa-trash'></span></a>
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
            "zeroRecords": "Manufacturer not found"
            }
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>