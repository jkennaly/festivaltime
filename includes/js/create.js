/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 

$(document).ready(	
	
	function() {
		$('#newfestseries').click(function(){
			$("#overlay_form").css('visibility', 'visible');
//			alert('It coming');
			positionPopup();
		});
				 
		//close popup
		$("#cancel").click(function(){
			$("#overlay_form").css('visibility', 'hidden');
		});
		
		$("#show-complete").click(function(){
			$("#festivalstatuscompleted").toggle();
		});
		
		$("#show-incomplete").click(function(){
			$("#festivalstatusincomplete").toggle();
		});
		
		$("#show-verifreq").click(function(){
			$("#festivalstatusverifreq").toggle();
		});
		
		$(".feststatususer button[type=button]").click(function(){
			window.location = $(this).data('target');
		});
		
		
		
		});
		
$('#festselectdates').click(function() {
   window.location = basepage + "?disp=select_dates";
});

//position the popup at the center of the page
function positionPopup(){
	if(!$("#overlay_form").is(':visible')){
		return;
	}
	
	$("#overlay_form").css({
		left: ($(window).width() - $('#overlay_form').width()) / 2,
		top: ($(window).width() - $('#overlay_form').width()) / 7,
		position:'absolute'
	});
}


//maintain the popup at center of the page when browser resized
//$(window).bind('resize',positionPopup);


