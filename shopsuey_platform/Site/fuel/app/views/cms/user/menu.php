<?php if (isset($in_list) && $me->group == Model_User::GROUP_SUPERADMIN): ?>
    <div class="sidePad">
        <a class="sideB bGold" href="<?=Uri::create('dashboard/user/add')?>">
            <span class="icon-plus-2"></span>
            <span>New User</span>
        </a>
    </div>
    <?php if ($app_users): ?>
    <div class="sidePad" style="margin-top: 10px;">
        <a class="sideB bGold" href="<?=Uri::create('dashboard/users')?>">
            <span class="icon-plus-2"></span>
            <span>Platform Users</span>
        </a>
    </div>
    <?php else: ?>
    <div class="sidePad" style="margin-top: 10px;">
        <a class="sideB bGold" href="<?=Uri::create('dashboard/users?app_users=1')?>">
            <span class="icon-plus-2"></span>
            <span>App Users</span>
        </a>
    </div>
    <?php endif; ?>
<?php endif; ?>