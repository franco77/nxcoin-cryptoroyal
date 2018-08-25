<?php $userdata = userdata(); ?>
<nav class="sidebar-nav">
    <ul id="sidebarnav"> 
        <li> 
            <div class="user-profile d-flex no-block dropdown mt-20">
                <div class="user-pic" style="margin-right: 10px"><img alt="user" class="img-circle" width="40" src="<?php echo base_url('uploads/image/').''.$userdata->user_picture ?>"></div>
                <div class="user-content hide-menu m-l-10">
                    <a href="javascript:void(0)" class="" id="Userdd" role="button">
                        <h5 class="mb-0 user-name font-medium"><?php echo $userdata->user_fullname ?></h5>
                        <span class="op-5 user-email"><?php echo $userdata->email ?></span>
                    </a> 
                </div>
            </div> 
        </li>  
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url() ?>" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('profile') ?>" aria-expanded="false"><i class="mdi mdi-face"></i><span class="hide-menu">Profile</span></a></li><li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('referral') ?>" aria-expanded="false"><i class="mdi mdi-account-network"></i><span class="hide-menu">Referral</span></a></li>

        
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('stacking') ?>" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu">Staking</span></a></li>
 

        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Bonus </span></a>
            <ul aria-expanded="false" class="collapse  first-level">
                <li class="sidebar-item"><a href="<?php echo base_url('bonus-active') ?>" class="sidebar-link"><i class="mdi mdi-check-all"></i><span class="hide-menu"> Active </span></a></li>
                <li class="sidebar-item"><a href="<?php echo base_url('bonus-pasive') ?>" class="sidebar-link"><i class="mdi mdi-check-all"></i><span class="hide-menu"> Pasive </span></a></li>  
                <li class="sidebar-item"><a href="<?php echo base_url('bonus-rollover') ?>" class="sidebar-link"><i class="mdi mdi-check-all"></i><span class="hide-menu"> Rollover </span></a></li> 
                 <?php if ($this->ion_auth->is_admin()){ ?>
                <li class="sidebar-item"><a href="<?php echo base_url('admin/view/sendbonusbtc') ?>" class="sidebar-link"><i class="mdi mdi-check-all"></i><span class="hide-menu"> BTC </span></a></li> 
                <?php } ?>
            </ul>
        </li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('transfer') ?>" aria-expanded="false"><i class="mdi mdi-credit-card-multiple"></i><span class="hide-menu">Transfer</span></a></li> 
        <?php if ($this->ion_auth->is_admin()){ ?>
            <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Admin Panel</span></li> 
            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('admin/view/userlist') ?>" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Userlist</span></a></li>
            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('admin/view/userstaking') ?>" aria-expanded="false"><i class="mdi mdi-account-star"></i><span class="hide-menu">Staking User</span></a></li>
            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url('admin/view/userbonus') ?>" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu">Bonus User</span></a></li>
            
        <?php } ?> 
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link logout" href="#" aria-expanded="false"><i class="mdi mdi-directions"></i><span class="hide-menu">Log Out</span></a></li>
    </ul>
</nav>