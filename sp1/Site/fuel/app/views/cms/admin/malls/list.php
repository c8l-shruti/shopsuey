<!-- Search widget -->
<div class="fluid">
    <div class="grid12">
        <div class="searchLine">
            <form action="<?=Uri::create('admin/malls')?>/" id="search">
                <input autocomplete="off" id="searchInput" type="text" name="string" class="ac" placeholder="Search by marketplace name" value="<?=htmlentities(urldecode($search), ENT_QUOTES)?>" />
                <button type="submit" class="search-btn"><span class="icos-search"></span></button>
            </form>
        </div>
    </div>
</div>

<!-- Malls List -->
<div id="mallsDataTableWrapper" class="widget fluid">
    <div style="display: none" class="whead" id="searchLoading"><h6><?= Asset::img('elements/loaders/1s.gif'); ?> Loading search results...</h6><div class="clear"></div></div>
    <div class="whead"><h6><span class="icon-basket"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::has_access('admin/mall.add')) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create('admin/mall/add')?>" title="Add new marketplace"><span class="icon-plus-3" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>

    <?php if (count($malls) > 0) : ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
	<thead>
	    <tr>
		<td><div class="text-left"><a href="<?=Uri::create("admin/malls/{$pagination->current_page}/$search?sort=name")?>">Marketplace Name</a></div></td>
        <td><div class="text-left"># of merchants</div></td>
        <td><div class="text-left"><a href="<?=Uri::create("admin/malls/{$pagination->current_page}/$search?sort=created_at")?>">Creation Date</a></div></td>
		<td width="50"><div class="text-left"><a href="<?=Uri::create("admin/malls/{$pagination->current_page}/$search?sort=status")?>">Status</a></div></td>
		<td width="30" class=""></td>
		<td width="30" class=""></td>
	    </tr>
	</thead>
	<tfoot>
		<tr>
		<td colspan="4">
		    <div class="tPages">
			<ul class="pages">
				<?php if (isset($pagination->previous)): ?>
				<li class="prev"><a href="<?=Uri::create("admin/malls/{$pagination->previous}/$search?$sort_string")?>" title=""><span class="icon-arrow-14"></span></a></li>
			    <?php endif; ?>
			    <?php if (isset($pagination->first)): ?>
				<li class="prev"><a href="<?=Uri::create("admin/malls/{$pagination->first}/$search?$sort_string")?>" title=""><?=$pagination->first?></a></li>
				<li class="prev">...</li>
				<?php endif; ?>
			    
			    <?php foreach ($pagination->pages as $page): ?>
			    <li><a class=" <?=($page['active']) ? 'active' : '' ?>" title="" href="<?=Uri::create("admin/malls/{$page['number']}/$search?$sort_string")?>"><?=$page['number']?></a></li>
			    <?php endforeach; ?>

			    <?php if (isset($pagination->last)): ?>
			    <li class="next">...</li>
			    <li class="next"><a href="<?=Uri::create("admin/malls/{$pagination->last}/$search?$sort_string")?>" title=""><?=$pagination->last?></a></li>
			    <?php endif; ?>
			    <?php if (isset($pagination->next)):?>
			    <li class="next"><a href="<?=Uri::create("admin/malls/{$pagination->next}/$search?$sort_string")?>" title=""><span class="icon-arrow-17"></span></a></li>
			    <?php endif; ?>
		    </ul>
		    </div>
		</td>
	    </tr>
	</tfoot>
	<tbody>
	    <?php foreach($malls as $mall) : ?>
		<?php $status = CMS::status($mall->status); ?>
	    <tr>
		<td><a href="<?=Uri::create("dashboard/health_metrics?id={$mall->id}")?>"><?=mb_convert_case($mall->name, MB_CASE_TITLE)?></a></td>
        <td>
            <?= $mall->merchant_count ?>
        </td>
        <td>
            <?=date('m/d/Y', $mall->created_at)?>
        </td>
		<td style="text-align: center"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
        <td><a href="<?=Uri::create("admin/mall/{$mall->id}/edit")?>?back<?=$sort_string?>&backs=<?=$search?>&backp=<?=$pagination->current_page?>" class="tablectrl_small bGold tipS" rel="tooltip" title="Edit <?=$mall->name?>"><span class="iconb" data-icon="&#xe04d;"></span></a></td>
		<td><a href="<?=Uri::create("admin/mall/{$mall->id}/delete")?>" onclick="return confirm('Are you sure?')" class="tablectrl_small bGold tipS" rel="tooltip" title="Delete <?=$mall->name?>"><span class="iconb" data-icon="&#xe094;"></span></a></td>
	    </tr>
	    <?php endforeach; ?>
	</tbody>
    </table>
    <?php endif; ?>
</div>

<script type="text/javascript">
SearchAsYouType.initialize({requestUrl: '/admin/malls?', inputElementSelector: '#searchInput', dataTableSelector: '#mallsDataTableWrapper', searchParameter: 'string', loadingSelector: '#searchLoading'})
</script>