<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="index.php"?"active":"";?>">
              <a href="index.php">
              
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <?php
                if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
            ?>
            <li>
            <form action="view_asset.php" method="get" class="sidebar-form">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter Asset Tag" name='asset_tag' style='' required>
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
            </li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "deleted_assets.php",
            "assets.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-barcode fa-fw"></i>
                <span>Asset Status </span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="assets.php" && (empty($_GET['status']) || $_GET['status']=='All')?"active":"";?>'>
                <a href="assets.php?status=All"><i class="fa fa-circle-o"></i><span>List All</span></a>
                </li>
                <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="deleted_assets.php"?"active":"";?>'>
                    <a href="deleted_assets.php"><i class="fa fa-circle-o"></i><span>Deleted</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="assets.php" && (!empty($_GET['status']) && $_GET['status']=='Deployed')?"active":"";?>">
                  <a href="assets.php?status=Deployed"><i class="fa fa-circle-o"></i><span>Deployed</span></a>
                </li>
                <?php
                $navbar_asset_statuses=$con->myQuery("SELECT name FROM asset_status_labels WHERE is_deleted=0 ORDER BY name ")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($navbar_asset_statuses as  $navbar_asset):
                ?>
                  <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="assets.php" && (!empty($_GET['status']) && $_GET['status']==htmlspecialchars($navbar_asset['name']))?"active":"";?>">
                    <a href="assets.php?status=<?php echo urlencode(htmlspecialchars($navbar_asset['name'])) ?>"><i class="fa fa-circle-o"></i><span><?php echo htmlspecialchars(($navbar_asset['name'])) ?></span></a>
                  </li>
                <?php
                endforeach;
                unset($navbar_asset);
                unset($navbar_asset_statuses);
                ?>
              </ul>
            </li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "asset_models.php",
            "asset_maintenances.php",
            "categories.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-barcode fa-fw"></i>
                <span>Asset Management</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="asset_models.php"?"active":"";?>'>
                  <a href="asset_models.php"><i class="fa fa-th fa-fw"></i><span>Asset Models</span></a>
                </li>
                <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="assets_maintenances.php"?"active":"";?>'>
                    <a href="asset_maintenances.php"><i class="fa fa-wrench fa-fw"></i><span>Asset Maintenances</span></a>
                </li>
                <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="categories.php"?"active":"";?>'>
                  <a href="categories.php"><i class="fa fa-check fa-fw"></i><span>Categories</span></a>
                </li>
              </ul>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="consumables.php"?"active":"";?>">
                <a href="consumables.php"><i class="fa fa-tint fa-fw"></i><span>Consumables</span></a>
            </li>

            <?php
                endif;
            ?>
            <?php
                if($_SESSION[WEBAPP]['user']['user_type']!=4):
            ?>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "report_asset.php",
            "report_asset_activity.php",
            "report_asset_maintenance.php",
            "consumables_report.php",
            "consumable_activity_report.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset.php"?"active":"";?>">
                    <a href="report_asset.php"><i class="fa fa-circle-o"></i><span>Asset Report</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset_activity.php"?"active":"";?>">
                    <a href="report_asset_activity.php"><i class="fa fa-circle-o"></i><span>Asset Activity Report</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset_maintenance.php"?"active":"";?>">
                    <a href="report_asset_maintenance.php"><i class="fa fa-circle-o"></i><span>Asset Maintenance Report</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="consumables_report.php"?"active":"";?>">
                    <a href="consumables_report.php"><i class="fa fa-circle-o"></i><span>Consumable Report</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="consumable_activity_report.php"?"active":"";?>">
                    <a href="consumable_activity_report.php"><i class="fa fa-circle-o"></i><span>Consumable Activity Report</span></a>
                </li>
              </ul>
            </li>
            <?php
                endif;
            ?>
            <?php
              if(AllowUser(array(1))):
            ?>
            <li class='header'>ADMINISTRATOR MENU</li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "asset_status_labels.php",
            "user.php",
            "departments.php",
            "depreciations.php",
            "locations.php",
            "maintenance_types.php",
            "manufacturers.php",
            "settings.php"
            )))?"active":"";?>'>
              <a href=''><i class="fa fa-cubes"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="user.php"?"active":"";?>">
                  <a href="user.php">
                    <i class="fa fa-users"></i> <span>Users</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="settings.php"?"active":"";?>">
                  <a href="settings.php">
                    <i class="fa fa-gear"></i> <span>Settings</span>
                  </a>
                </li>
                <?php
                  if(AllowUser(array(1))):
                ?>
                <!-- <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="audit_log.php"?"active":"";?>">
                  <a href="audit_log.php">
                    <i class="fa fa-list"></i> <span>Audit Log</span>
                  </a>
                </li> -->

                <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
                "asset_status_labels.php",
                "departments.php",
                "depreciations.php",
                "locations.php",
                "maintenance_types.php",
                "manufacturers.php")))?"active":"";?>'>
                  <a href="#">
                    <i class="fa fa-sort-alpha-asc"></i>
                    <span>Metadata</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>

                  <ul class='treeview-menu'>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="asset_status_labels.php"?"active":"";?>'><a href="asset_status_labels.php"><i class="fa fa-list fa-fw"></i> <span>Asset Status Labels</span></a>
                    </li>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="departments.php"?"active":"";?>'><a href="departments.php"><i class="fa fa-building-o fa-fw"></i> <span>Departments</span></a>
                    </li>
                    <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="depreciations.php"?"active":"";?>"><a href="depreciations.php"><i class="fa fa-arrow-down fa-fw"></i> <span>Depreciations</span></a>
                    </li>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="locations.php"?"active":"";?>'><a href="locations.php"><i class="fa fa-globe fa-fw"></i> <span>Locations</span></a>
                    </li>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="maintenance_types.php"?"active":"";?>'><a href="maintenance_types.php"><i class="fa fa-gear fa-fw"></i> <span>Maintenance Types</span></a>
                    </li>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="manufacturers.php"?"active":"";?>'><a href="manufacturers.php"><i class="fa fa-gear fa-fw"></i> <span>Manufacturers</span></a>
                    </li>
                  </ul>
                </li>
                <?php
                  endif;
                ?>
              </ul>
            </li>
            <?php
              endif;
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>