<div class="widget fluid">
    <div class="whead"><h6><span class="icon-cart"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if (CMS::has_access('dashboard/offer/code.add')) : // Add button ?>
    <div class="tOptions text-center" style="width: 20px">
        <a href="<?=Uri::create("dashboard/offer/{$offer->id}/code/add")?>" title="Add new offer code"><span class="icon-plus-2" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
    </div>
    <?php endif; ?>
    <?php if (count($codes) > 0): ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
        <thead>
            <tr>
                <td class="sortCol"><div class="text-left">Offer Code<span></span></div></td>
								<td width="80" class="">Type</td>
								<td width="80" class="">Auto Generated</td>
								<td width="80" class="sortCol"><div class="text-left">Created<span></span></div></td>
								<td width="50" class="">Status</td>
                <td width="60" class=""></td>
            </tr>
        </thead>
        <tbody>
			<?php foreach($codes as $code) : // Codes list ?>
			<?php $status = CMS::status($code->status); ?>
			<tr>
			<td><?=$code->code?></td>
			<td><?=CMS::code_type_label($code->type)?></td>
			<td><?php if ($code->auto_generated): ?>&#xe097;<?php endif; ?></td>
			<td><?=date('m/d/Y', $code->created_at)?></td>
			<td style="text-align: center; vertical-align: middle;"><span rel="tooltip" title="<?=$status->label?>" class="iconb" data-icon="<?=$status->icon?>"></span></td>
			<td>
				<a href="<?=Uri::create("dashboard/offer/code/{$code->id}/edit")?>" class="tablectrl_small bGold tipS" title="Edit Code"><span class="iconb" data-icon="&#xe04d;"></span></a>
			</td>
			</tr>
			<?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>