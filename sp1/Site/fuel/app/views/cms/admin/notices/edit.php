<!-- Notice Info -->
<form id="wizard1" method="POST" enctype="multipart/form-data">
<input type="hidden" id="action" name="action" value="<?=$action?>" />

<?=CMS::create_nonce_field('user_'.$action, 'nonce')?>

<div class="fluid">
	<div class="grid9">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-info-3"></span>Title</h6>
				<div class="clear"></div>
			</div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="name" name="name" value="<?=@$notice->name?>" style="font-size: 22px; line-height: 26px; height: auto !important;" />
				</div>
			</fieldset>
		</div>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-document"></span>Content</h6>
				<div class="clear"></div>
			</div>

			<fieldset>
				<textarea name="content" id="description" rows="15"><?=@$notice->content?></textarea>
			</fieldset>
		</div>
	</div>
	<div class="grid3">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<?php if ($action == 'edit') : ?>
				<div class="formRow">
					<select name="status" class="fullwidth" data-placeholder="Status">
						<?php foreach(CMS::statuses(@$notice->status) as $status) : ?>
						<option value="<?=$status->value?>" <?=@$status->selected?>><?=$status->label?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Notice' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Notice'?></button>
				</div>
				<?php if ($action == 'edit') : ?>
				<div class="formRow">
					<a href="<?=Uri::create('dashboard/notice/view/'.$notice->id)?>" class="buttonL bRed fluid" style="text-align: center;"><i class="iconb" data-icon="&#xe044;"></i> &nbsp; View Notice</a>
				</div>
				<?php endif; ?>
			</fieldset>
		</div>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-calender"></span> Effective</h6>
				<div class="clear"></div>
			</div>
			<fieldset>
				<div class="formRow">
					<span class="mr5"><input type="text" id="date_start" name="date_start" class="datepicker" data-max="#date_end" value="<?=(@$notice->date_start && @$notice->date_start != '0000-00-00 00:00:00') ? date('m/d/Y', strtotime(@$notice->date_start)) : ''?>" placeholder="start" /></span>
					<span class="mr5 mt4">&ndash;</span>
					<span><input type="text" id="date_end" name="date_end" class="datepicker" data-min="#date_start" value="<?=(@$notice->date_end && @$notice->date_end != '0000-00-00 00:00:00') ? date('m/d/Y', strtotime(@$notice->date_end)) : ''?>" placeholder="end" /></span>
				</div>
			</fieldset>
		</div>
	</div>
</div>
</form>