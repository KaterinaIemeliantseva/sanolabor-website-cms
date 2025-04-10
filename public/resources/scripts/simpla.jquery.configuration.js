$(document).ready(function(){
	


    //Minimize Content Box
		
		$(".content-box-header h3").css({ "cursor":"s-resize" }); // Give the h3 in Content Box Header a different cursor
		$(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $(this).parent().next().toggle(); // Toggle the Content Box
			  $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

    // Content box tabs:
		
		$('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
		$('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
		$('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"
		
		$('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);

    //Close button:
		
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
		);

    // Alternating table rows:
    function getUrlVal(url)
	{
		url = url.substring(1); 
		var nicename = url.split('/');
		$.url = { 
			nivo0 : nicename[1], 
			nivo1 : nicename[2], 
			nivo2 : nicename[3], 
			nivo3 : nicename[4], 
			nivo4 : nicename[5], 
			nivo5 : nicename[6] 
		}; 
		
		return $.url;
	}
	var url = window.location.pathname;
	$.url2 = getUrlVal(url);

    //console.log(document.location);
	if($.url2.nivo0 != 'spletna_trgovina')
	{
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows
	}
	
    // Check all checkboxes when the one in a table head is checked:
		
		$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);

    // Initialise Facebox Modal window:
		
		$('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

  
  	//fix za gumbe
	var f = $('input.button[value="Potrdi"]');
	f.val('Shrani & zapri');
	
	var f2 = $('input.button[value="Uveljavi"]');
	f2.val('Shrani');


});
  
  
  