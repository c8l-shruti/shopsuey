<!-- Applications -->
<div class="widget">
    <div class="whead">
    	<h6>Applications</h6>
        <span class="floatR">
            <a class="buttonS bGold" href="<?=Config::get('base_url')?>developer/app" style="margin: 5px 45px -6px 0">
                <span class="icon-plus-2"></span>
                <span>Create an app</span>
            </a>
        </span>
        <div class="clear"></div>
    </div>
    <div class="tOptions">
        <a href="#" class="search-toggle"><?=Asset::img('icons/options.png')?></a>
    </div>
    <div class="showpars">
		<?php if (isset($items)) : if (is_array($items)) : ?>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="app-list">
            <thead>
                <tr>
                    <th class="textL"><strong>Application Name</strong></th>
                    <th class="textL"><strong>Contact Email</strong></th>
                    <th class="textL" width="60"><strong>Edited</strong></th>
                </tr>
            </thead>
            <tbody>
            	<?php foreach($items as $item) : ?>
                <tr>
                	<td><a href="<?=Config::get('base_url')?>developer/app/<?=$item['token']?>"><?=$item['name']?></a></td>
                    <td><a href="mailto:<?=$item['contact']?>"><?=$item['contact']?></a></td>
                    <td><?=($item['updated_at'] > 0) ? date('m/d/Y', $item['updated_at']) : date('m/d/Y', $item['created_at'])?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
		<?php endif; endif; ?>
    </div>
</div>
<!-- End Applications -->