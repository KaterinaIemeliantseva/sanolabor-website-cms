//get hash value
function getUrlVal(url)
{
    url = url.substring(1);
    var nicename = url.split('/');
    $.url = {
        nivo0 : nicename[0],
        nivo1 : nicename[1],
        nivo2 : nicename[2],
        nivo3 : nicename[3],
        nivo4 : nicename[4],
        nivo5 : nicename[5]
    };
    //console.log($.url);
    return $.url;
}

function getHashVal(hash)
{
    hash = hash.substring(1);
    var nicename = hash.split('/');
    $.hash = {
        nivo0 : nicename[0],
        nivo1 : nicename[1],
        nivo2 : nicename[2],
        nivo3 : nicename[3],
        nivo4 : nicename[4],
        nivo5 : nicename[5]
    };

    return $.hash;
}

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
  }

var hash = window.location.hash;
var url = window.location.pathname;

$.url = getUrlVal(url);
$.hash = getHashVal(hash);
//console.log($.url);



var Helper = {
    s4: function() {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1);
    },
    guid: function() {
        return this.s4() + this.s4() + '-' + this.s4() + '-' + this.s4() + '-' + this.s4() + '-' + this.s4() + this.s4() + this.s4();
    },
    hash: function() {
        var hash_s = hash.substring(1);
        var nicename = hash_s.split('/');
        var res = {
            nivo0 : nicename[0],
            nivo1 : nicename[1],
            nivo2 : nicename[2],
            nivo3 : nicename[3],
            nivo4 : nicename[4],
            nivo5 : nicename[5]
        };

        return res;
    },
    isNumeric: function(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

};

$(document).ready(function () {

  var ST_ARTIKLOV_TOTAL_MODUL = 4;

  //facebox
  $('a[rel*=facebox]').facebox();


  //login
  var options_login = {
    //target:     '#out',
    type: 'POST',
    data: { c: 'Prijava', m: 'preveri' },
    url:        '/library/ajax.php',
    success:    function(data) {
        
        if(data == 'true') {
            window.location.replace("/");
        } else {
			//console.log(data); return false;
            alertify.error('Napačno uporabniško ime ali geslo', 2);
        }
    }
  };
  $('#login_form').ajaxForm(options_login);

  //validacija login
  // $("#login_form").validate({
  //   rules: {
  //     uname: "required",
  //     pwd: "required"
  //   },
  //   messages: {
  //     uname: false,
  //     pwd: false
  //   }
  // });



	/*URL to array - start*/
	var queryObj = {};
	  var url = window.location.pathname;
	  var querystring = url.split('&');

	  //loop through each name-value pair and populate object
	  for ( var i=0; i<querystring.length; i++ )
	  {
		// get name and value
		var name = querystring[i].split('=')[0];
		var value = querystring[i].split('=')[1];
		// populate object

		queryObj[name] = value;
	  }



    //menu barve
  $("table tr.nivo1:even").addClass("n1_barva1");
  $("table tr.nivo1:odd").addClass("n1_barva2");

  $("table tr.nivo2:even").addClass("n2_barva1");
  $("table tr.nivo2:odd").addClass("n2_barva2");

  $("table tr.nivo3:even").addClass("n3_barva1");
  $("table tr.nivo3:odd").addClass("n3_barva2");

  $("table tr.nivo4:even").addClass("4_barva1");
  $("table tr.nivo4:odd").addClass("n4_barva2");

  $("table tr.nivo5:even").addClass("n5_barva1");
  $("table tr.nivo5:odd").addClass("n5_barva2");

  //fix
 /*if($('#cbr:empty'))
  	$('#cbr').css('border', 'none');*/



  //Sidebar Accordion Menu:
  $("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu

		$("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
			function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
		);

		$("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		);

    // Sidebar Accordion Menu Hover Effect:

		$("#main-nav li .nav-top-item").hover(
			function () {
				$(this).stop().animate({ paddingRight: "25px" }, 200);
			},
			function () {
				$(this).stop().animate({ paddingRight: "15px" });
			}
		);

	//$('#galerija a.izbrisi_sliko').on('click', function(){
	$(document).on('click', '#galerija a.izbrisi_sliko', function() {
		//alert($(this).attr('id'));
		if(confirm('Ali ste prepričane, da želite izbrisati sliko?')){
			$(this).parent().addClass('hide');

			var input = $(this).next().next();
			var val = input.val();
			input.val('remove+' + val);
		}
	});

    //https://igorescobar.github.io/jQuery-Mask-Plugin/docs.html
    var mask_options =  {
      onComplete: function(cep) {
        //alert('CEP Completed!:' + cep);
      },
      onKeyPress: function(cep, event, currentField, options){
        //console.log('A key was pressed!:', cep, ' event: ', event,
                //    'currentField: ', currentField, ' options: ', options);
      },
      onChange: function(cep){
        //console.log('cep changed! ', cep);
      },
      onInvalid: function(val, e, f, invalid, options){
        //var error = invalid[0];
        //console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
      }
    };
    $('.cas_od_do').mask('00:00-00:00',{placeholder: "__:__-__:__"});
    $('.cas').mask('00:00',{placeholder: "__:__"});

	/****************************************/


});

    function loadDatePicker() {
        $.datepicker.setDefaults($.datepicker.regional['']);
        $(".datepicker").datepicker($.datepicker.regional['sl']);
    }
    loadDatePicker();

	function dateSiToEn(date)
	{ //alert(date);
		var nd = date.split('.');
		//return nd[1] + '.' + nd[0] + '.' + nd[2];
		//alert(new Date(nd[1], nd[0], nd[2]));
		return new Date(nd[2], nd[1], nd[0]);
	}

	function diffDate(start_date, end_date)
	{
		//var start_date = new Date(dateSiToEn(start_date)).getTime();
   		//var end_date = new Date(dateSiToEn(end_date)).getTime();
		var start_date = dateSiToEn(start_date).getTime();
   		var end_date = dateSiToEn(end_date).getTime();
   		//alert(start_date + ' - ' + end_date);
   		var diff = start_date - end_date;
   		diff = parseInt(diff);

   		return diff;
	}

	function checkVecjiDatumOdDanes(datum)
	{
		var start_date = dateSiToEn(datum).getTime();
		var end_date = currentTime();

		//alert(datum + ' - ' + end_date);
		//alert(start_date + ' - ' + end_date);
		var diff = start_date - end_date;
   		diff = parseInt(diff);

   		return diff;
	}

	function currentTime()
	{
		var currentTime = new Date();
		var curr_date = currentTime.getDate();
		var curr_month = currentTime.getMonth();
		var curr_year = currentTime.getFullYear();
		//alert(curr_date + ' ' + curr_month + ' ' + curr_year);
		var novidatum = curr_date + '.' + (curr_month + 1) + '.' + curr_year;
		//alert(dateSiToEn(novidatum));
		//currentTime.format("dd.m.yy");
		return dateSiToEn(novidatum).getTime();
	}

	function myIP() {
	    if (window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
	    else xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	    xmlhttp.open("GET","http://api.hostip.info/get_html.php",false);
	    xmlhttp.send();

	    hostipInfo = xmlhttp.responseText.split("\n");

	    for (i=0; hostipInfo.length >= i; i++) {
	        ipAddress = hostipInfo[i].split(":");
	        if ( ipAddress[0] == "IP" ) return ipAddress[1];
	    }

	    return false;
	}


	function javascript_abort()
	{
	   throw new Error('Abort javascript');
	}


	function headerRedirect(root, params)
	{
		var queryObj = {};
		var url = window.location.pathname;
		var querystring = url.split('&');

		//loop through each name-value pair and populate object
		for ( var i=0; i<querystring.length; i++ )
		{
			// get name and value
			var name = querystring[i].split('=')[0];
			var value = querystring[i].split('=')[1];
			// populate object

			queryObj[name] = value;
		}

		var povezava = root;
		$.each( params, function(index, item){
			//alert(item);
			if(queryObj[item]) povezava += '&' + item + '=' + queryObj[item];
		});

		window.location = povezava;
	}


	function getUrlParamValue(url, q)
	{
		var queryObj = {};
		//var url = window.location.pathname;
		var querystring = url.split('&');

		//loop through each name-value pair and populate object
		for ( var i=0; i<querystring.length; i++ )
		{
			// get name and value
			var name = querystring[i].split('=')[0];
			var value = querystring[i].split('=')[1];
			// populate object

			queryObj[name] = value;
		}

		var res = queryObj[q];

		return res;
	}


	function dateToSlo(input) {
		var newDate = input.split("-");
		return newDate[2]+'. '+newDate[1]+'. '+newDate[0];
	}
