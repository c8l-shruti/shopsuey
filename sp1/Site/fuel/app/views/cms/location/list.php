<!-- Search widget -->
<div class="fluid">
    <div class="grid12">
        <div class="searchLine">
            <form action="<?=Uri::create('dashboard/location/search')?>/" id="search">
                <input type="text" name="f" class="ac" placeholder="Search by location name" value="<?=(isset($search)) ? $search : ''?>" />
                <button type="submit" class="search-btn"><span class="icos-search"></span></button>
            </form>
        </div>
    </div>
</div>

<!-- Locations List -->
<div class="widget fluid">
    <div class="whead"><h6><span class="icon-location"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::is_super_admin()) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create('dashboard/location/add')?>" title="Add new location"><span class="icon-plus-3" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>

    <?php if (count($locations) > 0) : ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
	<thead>
	    <tr>
		<td class="sortCol"><div class="text-left">Location Name<span></span></div></td>
		<td width="50" style="text-align: center">Status</td>
		<td width="30" class=""></td>
	    </tr>
	</thead>
	<?php if ($pagination->page->count > 1) : ?>
	<tfoot>
		<tr>
		<td colspan="3">
		    <div class="itemActions"></div>
		    <div class="tPages">
			<ul class="pages">
				<?php if (isset($pagination->page->prev)) : // previous link ?>
				<li class="prev"><a href="<?=Uri::create('dashboard/locations/'.$pagination->page->prev)?>" title=""><span class="icon-arrow-14"></span></a></li>
			    <?php endif; ?>

			    <?php for ($p = 1; $p <= $pagination->page->count; $p++) : // page links ?>
			    <li><a class=" <?=($p == $pagination->page->current) ? 'active' : '' ?>" title="" href="<?=Uri::create('dashboard/locations/'.$p)?>"><?=$p?></a></li>
			    <?php endfor; ?>

			    <?php if (isset($pagination->page->next)) : // next link ?>
			    <li class="next"><a href="<?=Uri::create('dashboard/locations/'.$pagination->page->next)?>" title=""><span class="icon-arrow-17"></span></a></li>
			    <?php endif; ?>
			</ul>
		    </div>
		</td>
	    </tr>
	</tfoot>
	<?php endif; ?>
	<tbody>
	    <?php foreach($locations as $location) : // Users list ?>
		<?php $status = CMS::status($location->status); ?>
	    <tr>
		<td><?=ucwords($location->name)?></td>
		<td style="text-align: center"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
		<td><a href="<?=Uri::create('dashboard/location/edit/'.$location->id)?>" class="tablectrl_small bGold tipS" rel="tooltip" title="Edit <?=$location->name?>"><span class="iconb" data-icon="&#xe04d;"></span></a></td>
	    </tr>
	    <?php endforeach; ?>
	</tbody>
    </table>
    <?php endif; ?>
</div>