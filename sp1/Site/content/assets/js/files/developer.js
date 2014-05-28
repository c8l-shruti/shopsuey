// JavaScript Document
$(function() {

	//===== Dynamic data table =====//
	$('.search-toggle').click(function (e) {
		e.preventDefault();
		$('.tablePars').slideToggle('fast');
	});
});