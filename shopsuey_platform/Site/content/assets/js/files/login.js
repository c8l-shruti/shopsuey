$(function(){
	// Checking for CSS 3D transformation support
	$.support.css3d = supportsCSS3D();
	
	var formContainer = $('.loginWrapper');
	
	// Listening for clicks on the ribbon links
	$('.flip').click(function(e){
		
		// Flipping the forms
		formContainer.toggleClass('flipped');
		
		// If there is no CSS3 3D support, simply
		// hide the login form (exposing the recover one)
		if(!$.support.css3d){
			$('#login').toggle();
		}
		e.preventDefault();
	});
	
	// Login pic hover animation
	$(".loginPic").hover(function () { loginOver(); }, function () { loginOut(); });
	
	function loginOver() {
		$('.logleft, .logback').animate({left:10, opacity:1},200); 
		$('.logright').animate({right:10, opacity:1},200);
	}
	
	function loginOut() {
		$('.logleft, .logback').animate({left:0, opacity:0},200);
		$('.logright').animate({right:0, opacity:0},200);
	}
	
	// A helper function that checks for the 
	// support of the 3D CSS3 transformations.
	function supportsCSS3D() {
		var props = [
			'perspectiveProperty', 'WebkitPerspective', 'MozPerspective'
		], testDom = document.createElement('a');
		  
		for(var i=0; i<props.length; i++){
			if(props[i] in testDom.style){
				return true;
			}
		}
		
		return false;
	}
	
	//===== Form elements styling =====//
	$("select, .check, .check :checkbox, input:radio, input:file").uniform();
	
	// Document ready
	$(document).ready(function() {
		
		loginOver();
		
		$('.slideDown').slideDown('fast');
		
	});
	
	
});
