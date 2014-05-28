<?php $extraParams = ($app_users) ? "?app_users=1" : ""; ?>
<div class="fluid">
    <div class="grid12">
        <!-- Search widget -->
        <div class="searchLine">
            <form action="<?=Uri::create('dashboard/users')?>/" id="search">
                <input type="hidden" name="app_users" value="<?php echo ($app_users) ? '1' : '0'; ?>" />
                <input type="text" name="string" class="ac" placeholder="Search" value="<?=$search?>" />
                <button type="submit" class="search-btn"><span class="icos-search"></span></button>
            </form>
        </div>
	</div>
</div>

<div class="widget fluid">
    <div class="whead"><h6><span class="icon-user-2"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::has_access('dashboard/user.add')) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create('dashboard/user/add')?>" title="Add new user"><span class="icon-contact" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
        <thead>
            <tr>
                <td class="sortCol"><div class="text-left">User Name<span></span></div></td>
                <td width="100" class="sortCol"><div class="text-left">Group<span></span></div></td>
                <td class="sortCol"><div class="text-left">Email<span></span></div></td>
                <td width="80" class="sortCol"><div class="text-left">Created<span></span></div></td>
                <td width="65" class=""></td>
            </tr>
        </thead>
		<?php if ($pagination->page->count > 1) : ?>
        <tfoot>
        	<tr>
            	<td colspan="5">
                	<div class="itemActions"></div>
                    <div class="tPages">
                    	<ul class="pages">
                        	<?php if (isset($pagination->page->prev)) : // previous link ?>
                        	<li class="prev"><a href="<?=Uri::create("dashboard/users/{$pagination->page->prev}/$search")?><?php echo $extraParams; ?>" title=""><span class="icon-arrow-14"></span></a></li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $pagination->page->count; $p++) : // page links ?>
                            <li><a class=" <?=($p == $pagination->page->current) ? 'active' : '' ?>" title="" href="<?=Uri::create("dashboard/users/$p/$search")?><?php echo $extraParams; ?>"><?=$p?></a></li>
                            <?php endfor; ?>

                            <?php if (isset($pagination->page->next)) : // next link ?>
                            <li class="next"><a href="<?=Uri::create("dashboard/users/{$pagination->page->next}/$search")?><?php echo $extraParams; ?>" title=""><span class="icon-arrow-17"></span></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </tfoot>
		<?php endif; ?>
        <tbody>
            <?php foreach($users as $user) : // Users list ?>
	            <?php if (isset($user->meta->real_name)): ?>
	            <?php $user_name = ucfirst($user->meta->real_name) ?>
	            <?php else: ?>
	            <?php $user_name = $user->email ?>
	            <?php endif; ?>
	            <tr>
                <td>
                	<?php if ($me->id == $user->id || (CMS::has_access('dashboard/user.edit') && $me->can_edit_group_members($user->group))) : ?>
                    <a href="<?=Uri::create("dashboard/user/$user->id/edit")?>"><?=$user_name?></a>

                    <?php else : ?>
                    <?=$user_name?>

                    <?php endif; ?>

                </td>
                <td><?=$user->group?></td>
                <td><?=$user->email?></td>
                <td><?=date('m/d/Y', $user->created_at)?></td>
                <td class="<?=(CMS::can('can_manage_users') && $user->username != 'admin') ? '' : ''?>">
                <?php if ($me->id == $user->id || (CMS::has_access('dashboard/user.edit') && $me->can_edit_group_members($user->group))) : ?>
                    <a href="<?=Uri::create("dashboard/user/$user->id/edit")?>" class="tablectrl_small bGold tipS" title="Edit <?=$user_name?>"><span class="iconb" data-icon="&#xe04d;"></span></a>
                    <?php endif; ?>
                    <?php if (CMS::has_access('dashboard/user.edit') && $me->can_edit_group_members($user->group) && $user->group != Model_User::GROUP_SUPERADMIN) : ?>
                    <span class="mrl5">
                    <a href="<?=Uri::create("dashboard/user/$user->id/edit", array(), array('delete' => 1))?>" class="tablectrl_small bRed tipS" title="Delete <?=$user_name?>"><span class="iconb" data-icon="&#xe095;"></span></a>
                    </span>
                    <div class="clear"></div>
                <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>