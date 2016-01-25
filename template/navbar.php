<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">SGTSI Asset Management System</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <!-- <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    
                </li> -->
                <!-- /.dropdown -->
                <?php
                    if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-plus fa-fw"></i>Create New  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="frm_assets.php"><i class="fa fa-barcode fa-fw"></i> Asset</a>
                            </li>
                            <li><a href="frm_consumables.php"><i class="fa fa-tint fa-fw"></i> Consumable</a>
                            </li>

                            <?php
                                if(AllowUser(array(1))):
                            ?>
                            <li><a href="frm_users.php"><i class="fa fa-user fa-fw"></i> User</a>
                            </li>
                            <?php
                                endif;
                            ?>
                        </ul>
                        <!-- /.dropdown-create new -->
                    </li>
                <?php
                    endif;
                ?>
                 <?php
                    if($_SESSION[WEBAPP]['user']['user_type']==1):
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench fa-fw"></i> Admin <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="asset_models.php"><i class="fa fa-th fa-fw"></i> Asset Models</a>
                        </li>
                        <li><a href="asset_status_labels.php"><i class="fa fa-list fa-fw"></i> Asset Status Labels</a>
                        </li>
                        <li><a href="categories.php"><i class="fa fa-check fa-fw"></i></i> Categories</a>
                        </li>
                        <li><a href="departments.php"><i class="fa fa-building-o fa-fw"></i></i> Departments</a>
                        </li>
                        <li><a href="depreciations.php"><i class="fa fa-arrow-down fa-fw"></i></i> Deprecations</a>
                        </li>
                        <li><a href="locations.php"><i class="fa fa-globe fa-fw"></i></i> Locations</a>
                        </li>
                        <li><a href="maintenance_types.php"><i class="fa fa-gear fa-fw"></i></i> Maintenance Types</a>
                        </li>
                        <li><a href="manufacturers.php"><i class="fa fa-gear fa-fw"></i></i> Manufacturers</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <?php

                    endif;
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>Welcome, <?php echo $_SESSION[WEBAPP]['user']['first_name']?>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="view_user.php?id=<?php echo $_SESSION[WEBAPP]['user']['id']?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
               
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation" style=''>
                <div class="sidebar-nav navbar-collapse">

                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>

                        <?php
                            if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                        ?>

                        <li class="sidebar-search">

                            <div class="input-group custom-search-form">
                                <form class='form' method='get' action='view_asset.php'>
                                    <div class='form-group'>
                                        <div class="input-group">
                                          <input type="text" class="form-control" placeholder="Enter Asset Tag" name='asset_tag' style=''>
                                          <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">&nbsp;<span class="glyphicon glyphicon-search"></span>&nbsp;</button>
                                          </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li >
                            <a href="#" ><i class="fa fa-barcode fa-fw"></i> Assets<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level" >
                                    <li>
                                        <a href="assets.php?status=Deployed">Deployed</a>
                                    </li>
                            	<?php
                            		$navbar_asset_statuses=$con->myQuery("SELECT name FROM asset_status_labels WHERE is_deleted=0 ORDER BY name ")->fetchAll(PDO::FETCH_ASSOC);
                            		foreach ($navbar_asset_statuses as  $navbar_asset):
                            	?>
                            		<li>
                                    	<a href="assets.php?status=<?php echo urlencode(htmlspecialchars($navbar_asset['name'])) ?>"><?php echo htmlspecialchars(($navbar_asset['name'])) ?></a>
                                	</li>
                            	<?php
                            		endforeach;
                                    unset($navbar_asset);
                                    unset($navbar_asset_statuses);
                            	?>
                                <li>
                                    <a href="assets.php?status=All">List All</a>
                                </li>
                                <li>
                                    <a href="asset_models.php">Asset Models</a>
                                </li>
                                <li>
                                    <a href="categories.php">Categories</a>
                                </li>
                                <li>
                                    <a href="deleted_assets.php">Deleted</a>
                                </li>
                                <li>
                                    <a href="asset_maintenances.php">Asset Maintenances</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="consumables.php"><i class="fa fa-tint fa-fw"></i> Consumables</a>
                        </li>
                        <li>
                            <a href="user.php"><i class="fa fa-edit fa-fw"></i> People</a>
                        </li>
                        <?php
                            endif;
                        ?>

                        <?php
                            if($_SESSION[WEBAPP]['user']['user_type']!=4):
                        ?>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Reports<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="report_asset.php">Asset Report</a>
                                </li>
                                <li>
                                    <a href="report_asset_activity.php">Asset Activity Report</a>
                                </li>
                                <li>
                                    <a href="report_asset_maintenance.php">Asset Maintenance Report</a>
                                </li>
                                <li>
                                    <a href="consumables_report.php">Consumable Report</a>
                                </li>
                                <li>
                                    <a href="consumable_activity_report.php">Consumable Activity Report</a>
                                </li>
                                <!-- <li>
                                    <a href="report_asset_depreciation.php">Depreciation Report</a>
                                </li> -->
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php
                            endif;
                        ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>            
            <!-- /.navbar-static-side -->
</nav>