// JavaScript Document
$(function() {
	$('#add').click(function () {
		add_item();
	});

	$('#param, #value').keydown(function (e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { e.preventDefault(); add_item(); }
	});

	$('.delete').live('click', function () {
		$(this).parent().parent().remove();
	});

	$('.param').live('keydown', function (e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { e.preventDefault(); }
	});

	$('#copy').click(function () {
		$('#code').select();
	});

	function add_item() {
		var p = $('#param').val();
		var v = $('#value').val();

		if (!p || !v) { return; }

		var tag = '<div class="mt10">';
		tag += '<div class="grid4"><input type="text" value="'+p+'" readonly="readonly" class="param" /></div>';
		tag += '<div class="grid7"><input type="text" value="'+v+'" name="param['+p+']" class="param" /></div>';
		tag += '<div class="grid1"><button type="button" class="tablectrl_small bRed fluid delete"><i class="iconb" data-icon="&#xe136;"></i></button></div>';
		tag += '<div class="clear"></div>';
		tag += '</div>';

		$('#params').prepend(tag);
		$('#param').val('');
		$('#value').val('');
		$('#param').focus();
	}
});