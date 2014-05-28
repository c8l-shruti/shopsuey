<!-- Search widget -->
<div class="fluid">
    <div class="grid12">
        <div class="searchLine">
            <form action="<?=Uri::create('admin/merchants')?>/" id="search">
                <input autocomplete="off" id="searchInput" type="text" name="string" class="ac" placeholder="Search by merchant name" value="<?=htmlentities(urldecode($search), ENT_QUOTES)?>" />
                <button type="submit" class="search-btn"><span class="icos-search"></span></button>
            </form>
        </div>
    </div>
</div>

<!-- Merchants List -->
<div id="merchantsDataTableWrapper" class="widget fluid">
    <div style="display: none" class="whead" id="searchLoading"><h6><?= Asset::img('elements/loaders/1s.gif'); ?> Loading search results...</h6><div class="clear"></div></div>
    <div class="whead"><h6><span class="icon-user-3"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::has_access('admin/merchant.add')) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create('admin/merchant/add')?>" title="Add new merchant"><span class="icon-contact" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>

    <?php if (count($merchants) > 0) : ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
	<thead>
	    <tr>
		<td><div class="text-left"><a href="<?=Uri::create("admin/merchants/{$pagination->current_page}/$search?sort=name")?>">Merchant Name</a></div></td>
		<td><div class="text-left"><a href="<?=Uri::create("admin/merchants/{$pagination->current_page}/$search?sort=mall.name")?>">Marketplace</a></div></td>
		<td><div class="text-left"><a href="<?=Uri::create("admin/merchants/{$pagination->current_page}/$search?sort=created_at")?>">Creation Date</a></div></td>
        <td width="50"><div class="text-left"><a href="<?=Uri::create("admin/merchants/{$pagination->current_page}/$search?sort=status")?>">Status</a></div></td>
		<td width="30" class=""></td>
		<td width="30" class=""></td>
	    </tr>
	</thead>
	<tfoot>
		<tr>
		<td colspan="5">
		    <div class="tPages">
			<ul class="pages">
				<?php if (isset($pagination->previous)): ?>
				<li class="prev"><a href="<?=Uri::create("admin/merchants/{$pagination->previous}/$search?$sort_string")?>" title=""><span class="icon-arrow-14"></span></a></li>
			    <?php endif; ?>
			    <?php if (isset($pagination->first)): ?>
				<li class="prev"><a href="<?=Uri::create("admin/merchants/{$pagination->first}/$search?$sort_string")?>" title=""><?=$pagination->first?></a></li>
				<li class="prev">...</li>
				<?php endif; ?>
			    
			    <?php foreach ($pagination->pages as $page): ?>
			    <li><a class=" <?=($page['active']) ? 'active' : '' ?>" title="" href="<?=Uri::create("admin/merchants/{$page['number']}/$search?$sort_string")?>"><?=$page['number']?></a></li>
			    <?php endforeach; ?>

			    <?php if (isset($pagination->last)): ?>
			    <li class="next">...</li>
			    <li class="next"><a href="<?=Uri::create("admin/merchants/{$pagination->last}/$search?$sort_string")?>" title=""><?=$pagination->last?></a></li>
			    <?php endif; ?>
			    <?php if (isset($pagination->next)):?>
			    <li class="next"><a href="<?=Uri::create("admin/merchants/{$pagination->next}/$search?$sort_string")?>" title=""><span class="icon-arrow-17"></span></a></li>
			    <?php endif; ?>
			</ul>
		    </div>
		</td>
	    </tr>
	</tfoot>
	<tbody>
	    <?php foreach($merchants as $merchant) : // Users list ?>
		<?php $status = CMS::status($merchant->status); ?>
	    <tr>
		<td>
            <a href="<?=Uri::create("admin/merchant/{$merchant->id}/edit")?>?back<?=$sort_string?>&backs=<?=$search?>&backp=<?=$pagination->current_page?>">
                <?=mb_convert_case($merchant->name, MB_CASE_TITLE)?>
            </a>
        </td>
        <td>
            <?php if ($merchant->mall_name) { ?>
                <?=$merchant->mall_name?>, <?=$merchant->mall_city?>
            <?php } ?>
        </td>
        <td>
            <?=date('m/d/Y', $merchant->created_at)?>
        </td>
		<td style="text-align: center"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
		<td><a href="<?=Uri::create("admin/merchant/{$merchant->id}/edit")?>?back<?=$sort_string?>&backs=<?=$search?>&backp=<?=$pagination->current_page?>" class="tablectrl_small bGold tipS" rel="tooltip" title="Edit <?=$merchant->name?>"><span class="iconb" data-icon="&#xe04d;"></span></a></td>
		<td><a href="<?=Uri::create("admin/merchant/{$merchant->id}/delete")?>" onclick="return confirm('Are you sure?')" class="tablectrl_small bGold tipS" rel="tooltip" title="Delete <?=$merchant->name?>"><span class="iconb" data-icon="&#xe094;"></span></a></td>
	    </tr>
	    <?php endforeach; ?>
	</tbody>
    </table>
    <?php endif; ?>
</div>

<script type="text/javascript">
SearchAsYouType.initialize({requestUrl: '/admin/merchants?', inputElementSelector: '#searchInput', dataTableSelector: '#merchantsDataTableWrapper', searchParameter: 'string', loadingSelector: '#searchLoading'})
</script>