<!-- Search widget -->
<div class="fluid">
    <div class="grid12">
        <div class="searchLine">
            <form action="<?=Uri::create('dashboard/message/search')?>/" id="search">
                <input type="text" name="f" class="ac" placeholder="Search by..." value="<?=(isset($search)) ? $search : ''?>" />
                <button type="submit" class="search-btn"><span class="icos-search"></span></button>
            </form>
        </div>
    </div>
</div>

<!-- Message List -->
<div class="widget fluid">
    <div class="whead"><h6><span class="icon-comments-2"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::is_super_admin()) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create('dashboard/message/add')?>" title="Add new message"><span class="icon-comments" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>

    <?php if (count($messages) > 0) : ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
	<thead>
	    <tr>
		<td class="sortCol" width="60"><div class="text-left">Date<span></span></div></td>
		<td class="sortCol"><div class="text-left">ID<span></span></div></td>
		<td class="sortCol"><div class="text-left">Message<span></span></div></td>
		<td width="50"><div class="text-left">Status</div></td>
		<td width="30"></td>
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
			    <li class="prev"><a href="<?=Uri::create('dashboard/messages/'.$pagination->page->prev)?>" title=""><span class="icon-arrow-14"></span></a></li>
			    <?php endif; ?>

			    <?php for ($p = 1; $p <= $pagination->page->count; $p++) : // page links ?>
			    <li><a class=" <?=($p == $pagination->page->current) ? 'active' : '' ?>" title="" href="<?=Uri::create('dashboard/messages/'.$p)?>"><?=$p?></a></li>
			    <?php endfor; ?>

			    <?php if (isset($pagination->page->next)) : // next link ?>
			    <li class="next"><a href="<?=Uri::create('dashboard/messages/'.$pagination->page->next)?>" title=""><span class="icon-arrow-17"></span></a></li>
			    <?php endif; ?>
			</ul>
		    </div>
		</td>
	    </tr>
	</tfoot>
	<?php endif; ?>
	<tbody>
	    <?php foreach($messages as $message) : // Message list ?>
            <?php
                $msg_txt = substr($message->content, 0, 100);
                $msg_txt .= (strlen($msg_txt) == 100) ? '...' : '';
				$status = CMS::status($message->status);
            ?>
	    <tr>
		<td><?=date('m/d/Y', strtotime($message->created))?></td>
		<td><?=$message->id?></td>
		<td><?=$msg_txt?></td>
		<td style="text-align: center; vertical-align: middle;"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
		<td><a href="<?=Uri::create('dashboard/message/edit/'.$message->id)?>" class="tablectrl_small bGold tipS" title="Edit Message"><span class="iconb" data-icon="&#xe04d;"></span></a></td>
	    </tr>
	    <?php endforeach; ?>
	</tbody>
    </table>
    <?php endif; ?>
</div>