<script type="text/javascript">
    $(window).load(function() {
        $('.merchant-list-container').slimScroll({height: 700});
    });
</script>
<div class="sidePad">
    <?php if (Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
    <div class='merchant-count'>
        <p><?=$count; ?></p>
        <p>Merchants</p>
    </div>
    
     <a class="sideB bGold" href="<?=Uri::create('admin/merchant/add')?>?mall=<?= $company->id; ?>">
        <span class="icon-plus-2"></span>
        <span>New Merchant</span>
    </a>
    <br/>
    
    <div class="merchant-list-container">
        <table class="tDefault" width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td>Merchant name</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($merchants as $merchant): ?>
                <tr>
                    <td><a href="<?=Uri::create("admin/merchant/{$merchant->id}/edit")?>"><?=$merchant->name; ?></a></td>
                    <?php $status = CMS::status($merchant->status); ?>
                    <td style="text-align: center"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
        <br/>
        <a class="sideB bGold" href="<?=Uri::create('admin/merchant/add')?>?mall=<?= $company->id; ?>">
            <span class="icon-plus-2"></span>
            <span>New Merchant</span>
        </a>
    </div>
            
    <?php } ?>
</div>