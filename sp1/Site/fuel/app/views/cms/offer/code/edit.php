<!-- Offer Info -->
<form id="wizard1" method="post" action="<?=$action?>">

<div class="fluid">
	<div class="grid9"><!-- Column Left -->
		<div class="widget"><!-- Offer Info -->
			<div class="whead">
				<h6><span class="icon-info-3"></span>Offer Code Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid2"><label for="type">Type:</label></div>
					<div class="grid8">
						<select name="type" id="type" class="fullwidth" data-placeholder="Type">
							<option></option>
							<?php foreach(CMS::code_types(@$offer_code->type) as $type) : ?>
							<option value="<?=$type->type?>" <?php if ($type->selected): ?>selected="selected"<?php endif; ?>><?=$type->label?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="code">Code:</label></div>
					<div class="grid8">
						<input type="text" id="code" name="code" placeholder="Offer code" value="<?=@$offer_code->code?>" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>

		</div>

		<div class="grid3"><!-- Column Right -->
			<div class="widget"><!-- Publish -->
				<div class="whead">
					<h6><span class="icon-cog"></span>Publish</h6>
					<div class="clear"></div>
				</div>
				<?php if (@$offer_code->id) : ?>
				<div class="formRow">
					<select name="status" class="fullwidth" data-placeholder="Status">
						<option></option>
						<?php foreach(CMS::statuses(@$offer_code->status) as $status) : ?>
						<option value="<?=$status->value?>" <?=@$status->selected?>><?=$status->label?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>
				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=(!@$offer_code->id) ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Offer Code' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Offer Code'?></button>
				</div>
			</div>
		</div>
	</div>
</div>

</form>