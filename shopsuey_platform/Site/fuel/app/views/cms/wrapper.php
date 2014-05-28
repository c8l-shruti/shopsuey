<body>
<?php if (\Fuel::$env == 'development') { ?>
<div id="dev">Dev Environment</div>
<?php } ?>

<div id="loaderContainer">
    <div id="loaderBg"></div>
    <div id="spinnerContainer"></div>
    <div id="loaderMessage"></div>
</div>

<!-- Sidebar begins -->
<div id="sidebar">
    <?=CMS::main_nav()?>
    
    <!-- Secondary nav -->
    <div class="secNav">
        <div class="secWrapper">
            <div class="secTop">
            	<div class="logo">
            		<a href="<?=Uri::create('dashboard')?>" title=""><?=Asset::img('logo.png')?></a>
                </div>
            </div>
            <?=CMS::system_notifications()?>
            
            <?php if (isset($page['subnav'])) { echo $page['subnav']; } ?>
       </div>
       <div class="clear"></div>
   </div>
</div>
<!-- Sidebar ends -->
    
    
<!-- Content begins -->
<div id="content">
    <div align="center" class="mt5 mini-logo"><a href="<?=Uri::create('dashboard')?>" title=""><?=Asset::img('logo.png')?></a></div>
    <div class="contentTop">
        <span class="pageTitle"><span class="<?=(isset($page['icon'])) ? $page['icon'] : 'icon-screen'?>"></span><?=$page['name']?></span>
        <?php if (isset($company) && $company): ?>
            <span class="companySelect">
                <form id="company_select" action="<?=Uri::create('dashboard')?>" method="post">
                    <select id="company_id_select" name="company_id">
                    <?php foreach(CMS::locations() as $location): ?>
                        <option value="<?=$location->id?>" <?= $location->id == $company->id ? 'selected="selected"' : '' ?>><?=$location->name?></option>
                    <?php endforeach; ?>
                    </select>
                </form>
            </span>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
    
    <!-- Breadcrumbs begins -->
    <div class="breadLine">
		<?php if (isset($crumbs)) : ?>
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <?php foreach($crumbs as $crumb) : ?>
                <li><a href="<?=(isset($crumb['link'])) ? $crumb['link'] : '#'?>"><?=$crumb['title']?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="breadLinks">
            <ul>
                <li class="has menu-btn">
                	<a href="#">
                    	<i class="icon-list"></i>
                    </a>
                    <ul class="hidden">
                    	<?=CMS::mini_nav()?>
                    </ul>
                </li>
                <?php if (isset($company) && $company): ?>
                <li class="has">
                	<a alt="Company Profile" href="#">
                    	<i class="icon-user-3"></i>
                        <span>Company Profile</span>
                        <span><?=Asset::img('elements/control/hasddArrow.png')?></span>
                    </a>
                    <ul class="hidden">
                    	<li>
                        	<a alt="Edit Company" href="<?=Uri::create('dashboard/company/edit')?>">
                                <span class="icon-pencil"></span>
                                Edit Company
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if (isset($forced_company) && $forced_company): ?>
                <li>
                	<a alt="Company Profile" href="<?=Uri::create('admin/mall/' . $forced_company->id . "/edit")?>">
                    	<i class="icon-pencil"></i>
                        <span>Edit</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="has">
                    <a alt="Menu" href="#">
                        <i class="icon-user-2"></i>
                        <span><?= $me->get_friendly_name() ?></span>
                        <span><?=Asset::img('elements/control/hasddArrow.png')?></span>
                    </a>
                    <ul class="hidden">
                    	<li>
                        	<a alt="User Settings" href="<?=Uri::create('dashboard/profile/edit/')?>">
                                <span class="icon-pencil"></span>
                                User Settings
                            </a>
                        </li>
                        <?php if (isset($company) && $company): ?>
                        <li>
                        	<a alt="Billing" href="<?=Uri::create('dashboard/profile/billing/')?>">
                                <span class="icon-cart"></span>
                                Billing
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                        	<a alt="Log Out" href="<?=Uri::create('logout')?>">
                                <span class="icon-key"></span>
                                Log Out
                            </a>                        	
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs ends -->
    
    <!-- Wrapper begins -->
    <div class="wrapper">
        <!-- Alert -->
        <div class="<?=(isset($message)) ? ' slideDown' : ' hide'?><?=(isset($message['autohide'])) ? ' autohide': ''?>">
        <?=CMS::note(@$message['message'], @$message['type'])?>
        </div>
        <!-- Alert ends -->
        
        <!-- Main content -->
        <?=@$content?>
        <!-- Main content ends -->
    </div>
    <!-- Wrapper ends -->
    
</div>
<!-- Content ends -->
    <div id="footer">
        ShopSuey Admin v<?php echo Config::get('admin_version')?>
    </div>

<script type="text/javascript">

$("#company_id_select").change(function() {
	$(this).parent().submit();
});

</script>
    
</body>
