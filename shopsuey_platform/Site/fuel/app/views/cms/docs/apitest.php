<form id="form" method="post">
<div class="fluid">
	<div class="grid6"><!-- Column Left -->
		<div class="widget"><!-- Request Info -->
			<div class="whead">
				<h6><span class="icon-beaker"></span>Request</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<select class="fullwidth" data-placeholder="Method..." name="method">
						<option></option>
						<option value="GET" <?=(@$form->method == 'GET') ? 'selected="selected"' : ''?>>GET</option>
						<option value="POST" <?=(@$form->method == 'POST') ? 'selected="selected"' : ''?>>POST</option>
						<option value="PUT" <?=(@$form->method == 'PUT') ? 'selected="selected"' : ''?>>PUT</option>
						<option value="DELETE" <?=(@$form->method == 'DELETE') ? 'selected="selected"' : ''?>>DELETE</option>
					</select>
				</div>
				<div class="formRow">
					<input type="text" id="endpoint" name="endpoint" placeholder="End Point" value="<?=(@$form->endpoint) ? @$form->endpoint : Uri::create('api') . '/'?>" />
				</div>
				<div class="formRow">
					<input type="text" id="access_key" name="access_key" placeholder="Access Key" value="<?=(@$form->access_key) ? @$form->access_key : $login_hash ?>" />
				</div>
			</fieldset>
			<div class="whead">
				<h6><span class="icon-share-3"></span>Parameters</h6>
				<div class="clear"></div>
			</div>
			<fieldset>
				<div class="formRow">
					<div class="grid4">
						<input type="text" id="param" placeholder="Parameter" />
					</div>
					<div class="grid7">
						<input type="text" id="value" placeholder="Value" />
					</div>
					<div class="grid1">
						<button type="button" class="tablectrl_small bGold fluid" id="add"><i class="iconb" data-icon="&#xe099;"></i></button>
					</div>
					<div class="clear"></div>
					<div id="params">
						<?php if (@$form->param) : // has params ?>
						<?php foreach($form->param as $param => $value) : ?>
						<div class="mt10">
							<div class="grid4"><input type="text" value="<?=$param?>" readonly="readonly" class="param" /></div>
							<div class="grid7"><input type="text" value="<?=$value?>" name="param[<?=$param?>]" class="param" /></div>
							<div class="grid1"><button type="button" class="tablectrl_small bRed fluid delete"><i class="iconb" data-icon="&#xe136;"></i></button></div>
							<div class="clear"></div>
						</div>
						<?php endforeach; // end params ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="formRow">
					<button type="submit" class="bGreyish buttonL fluid" id="submit"><i class="iconb" data-icon="&#xe05a;"></i> &nbsp; Initiate</button>
				</div>
			</fieldset>
		</div>

	</div>
	<div class="grid6"><!-- Column Right -->
	<?php if (@$output) : ?>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-document"></span>Output</h6>
				<div class="clear"></div>
			</div>
			<div class="tOptions text-center" style="width: 20px">
				<a id="copy" href="#" rel="tooltipL" title="select output"><span class="icon-clipboard" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
			</div>
			<div class="formRow">
				<textarea rows="25" readonly="readonly" id="code"><?=CMS::pretty_json($output)?></textarea>
			</div>
		</div>
	<?php elseif (@$raw) : ?>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-denied"></span>Error</h6>
				<div class="clear"></div>
			</div>
			<div class="tOptions text-center" style="width: 20px">
				<a id="copy" href="#" rel="tooltipL" title="select output"><span class="icon-clipboard" style="margin: 0; padding: 0; line-height: 16px; font-size: 14px; color: #555555"></span></a>
			</div>
			<div class="formRow">
				<textarea rows="25" readonly="readonly" id="code"><?=$raw?></textarea>
			</div>
		</div>
	<?php endif; ?>
	</div>
</div>
</form>