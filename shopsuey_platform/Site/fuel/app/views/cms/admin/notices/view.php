<div class="fluid">
	<div class="widget">
		<div class="whead">
			<div class="floatR mt12 mr15">created: <?=date('m/d/Y h:i a', strtotime(@$notice->created))?></div>
			<?php if (CMS::can('can_manage_system_notifications')) : ?>
			<h6><a href="<?=Uri::create('admin/notice/edit/'.$notice->id)?>" class="buttonS bRed">Edit</a></h6><div class="clear"></div>
			<?php endif; ?>
		</div>
		<div class="formRow">
		<div class="messageArea">
				<div class="infoRow">
					<span class="name">
						<?php if (@$notice->date_start && @$notice->date_end) : ?>
						Effective dates: &nbsp;
						<?php endif; ?>

						<?php if (@$notice->date_start && !@$notice->date_end) : ?>
						Effective date: &nbsp;
						<?php endif; ?>

						<?=(@$notice->date_start) ? date('m/d/Y', strtotime(@$notice->date_start)) : ''?>

						<?php if (@$notice->date_start && @$notice->date_end): ?>
						<span> &ndash; </span>
						<?php endif; ?>

						<?=(@$notice->date_end) ? date('m/d/Y', strtotime(@$notice->date_end)) : ''?>
					</span>
					<div class="clear"></div>
				</div>
				<?=str_replace('&lt;', '<', str_replace("&gt;", '>', $notice->content))?>
			</div>
			<div class="clear"></div>
			</div>
	</div>
</div>
