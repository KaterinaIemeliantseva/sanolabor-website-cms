<?php
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(dirname(dirname(__FILE__))));
	//define('ROOT', dirname(__FILE__));
	include (ROOT . DS . 'config.php'); 
	
	include (ROOT . DS . 'library' . DS . 'SuperClass.class.php'); 
	include (ROOT . DS . 'library' . DS . 'Database.class.php'); 
	$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
  	$db->connect();
	include (ROOT . DS . 'application' . DS . 'bal' . DS . 'VsebinaBAL.php'); 
	
	include (ROOT . DS . 'application' . DS . 'bal' . DS . 'ArtikelBAL.php'); 
	$_art = new ArtikelBAL();
?>
<!DOCTYPE HTML>
<html> 
  <head>
    <title>CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Generator" content="CMS" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="robots" content="index,follow" />
    <link rel="stylesheet" type="text/css" href="/public/resources/css/reset.css" /> 
    <link rel="stylesheet" type="text/css" href="/public/resources/css/style.css" />  
    <link rel="stylesheet" type="text/css" href="/public/resources/css/ui-lightness/jquery-ui-1.8.16.custom.css" />  
     
    <script type="text/javascript" src="/public/resources/scripts/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.form.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery-ui-1.8.16.custom.min.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.livequery.min.js"></script>
    
	<link rel="stylesheet" type="text/css" href="/public/resources/css/imgareaselect-default.css" />  
    <script type="text/javascript" src="/public/resources/scripts/jquery.imgareaselect-0.9.min.js"></script>
    
	<!-- <script type="text/javascript" src="/public/resources/scripts/jquery.imgareaselect-0.3.min.js"></script> -->
	
     <script type="text/javascript">
   		
		$(document).ready(function(){
			
			//edit
		    $('.edit_form_crop').livequery(function() { 
				$(this).ajaxForm({
				  	target:     '#out-slika',
				    type: 'POST',
				    url:        '/library/ajax.php', 
				    beforeSubmit:  function() {
				    	$('img.ajaxLoader').removeClass('hide');	
				    },
				    success:  function() { 
				    	$('img.ajaxLoader').addClass('hide');
				    	$('#thumbnail').imgAreaSelect( {aspectRatio: '1:1', handles: false, resizable: true, minWidth: 300, minHeight: 300, onSelectChange: preview }); 
				    	$('#potrdi').css('display', 'inline');
				    	//$('#thumbnail').imgAreaSelect( {handles: false, resizable: true, minWidth: 100, minHeight: 100, onSelectChange: preview }); 
				    } 
				});
			 return false;
			});
			
			 
			
		  	
		  	
		  	$('#save_thumb').livequery('click', function(){
				var x1 = $('#x1').val();
				var y1 = $('#y1').val();
				var x2 = $('#x2').val();
				var y2 = $('#y2').val();
				var w = $('#w').val();
				var h = $('#h').val();
				if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
					alert("Izberite polje");
					return false;
				}
				else
					ajaxCrop();
			});
			
			$('#potrdi').live('click', function(){
		   		var pot = $('#parent-pot').val();
		   		$('#pot').val(pot);
		   		
		  	});
			
			$('#potrdi').live('click', function(){
		   		var pot = $('#final_location').val(); 
		   		parent.$('#galerija').append('<li class="ui-state-default row"><a title="Uredi sliko" class="uredi_sliko" href="/public/html/controller/artikli/uredi_sliko.php?url='+ pot +'&id=<?=$_GET['id']?>"><i class="icon-edit icon-1x"></i></a><a title="Izbriši" class="izbrisi_sliko" href="#"><i class="icon-trash icon-1x"></i></a><img style="height:80px;" class="mains" src="' + pot + '?rand=' + Math.floor(Math.random()*101) + '" /><input type="hidden" class="slike" name="slike[]" value="' + pot + '" /></li>');
		   		parent.$.fancybox.close();
		   		
		   		$('#pot').val($('#parent-pot').val());
		   		
		   		return false;
		  	});
		  	
		  	$('#zapri').live('click', function(){
				parent.$.fancybox.close();
		  	});
		  	

		  	
		  	$('#save_thumb').livequery('click', function(){
				var x1 = $('#x1').val();
				var y1 = $('#y1').val();
				var x2 = $('#x2').val();
				var y2 = $('#y2').val();
				var w = $('#w').val();
				var h = $('#h').val();
				if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
					alert("Izberite polje");
					return false;
				}else{
					ajaxCrop();
					
				}
			});
			
		});
		
		function ajaxCrop()
		{
			$('.edit_form_crop_t').ajaxForm({
				target:     '#out-slika',
				type: 'POST',
				url:        '/library/ajax.php', 
				beforeSubmit:  function() {
					$('img.ajaxLoader').removeClass('hide');	
				},
				success:  function() { 
					$('img.ajaxLoader').addClass('hide');
					$('#thumbnail').imgAreaSelect(); 
				} 
			});
			
			return false;
		}
		
		
		function preview(img, selection) { 
			$('#x1').val(selection.x1);
			$('#y1').val(selection.y1);
			$('#x2').val(selection.x2);
			$('#y2').val(selection.y2);
			$('#w').val(selection.width);
			$('#h').val(selection.height);
		} 
	 		

    </script>

  </head>
<body style="background: none;">

<div id="ajax_inner_content">
	<?php if(!isset($_GET['url'])): ?>
    <small><strong>1. Izberite datoteko na računalniku.</strong></small><br />
    <small><strong>2. Kliknite na "Prenesi sliko na strežnik".</strong></small><br />
    <small><strong>3. Po želji lahko sliko obrežete.</strong></small><br />
    <small><strong>4. ko končate kliknite na gumb "Potrdi".</strong></small><br /><br />
    <form action="#" method="post" class="edit_form_crop">
		<fieldset> 								
			<p>
    			<!-- <label>Dodaj sliko</label> -->
   				<input type="file" id="file" name="pot" /> 
			</p>
			<p>
				<input type="hidden" name="c" value="ImageUpload" />
				<input type="hidden" name="m" value="imageUpload" />
				<?php if(isset($_GET['keepratio'])): ?><input type="hidden" name="keepratio" value="1" /><?php endif; ?>
				<input type="hidden" name="folder" value="<?=$_GET['folder']?>" />

				<input class="button" type="submit" name="potrdi" value="Prenesi sliko na strežnik" /> <input class="button" type="submit" id="potrdi" name="potrdi" value="Potrdi" style="display:none;"  />
				<img src="/public/resources/images/ajax-loader.gif" class="ajaxLoader hide" />
			</p>
		</fieldset>
		<div class="clear"></div>
	 </form>	
	 <?php endif; ?> 
	  <small><strong>Kliknite na sliko in označite polje, ki ga želite obrezati. Ko zaključite kliknite na gumb "Obreži".</strong></small><br /><br />
	  <?php if(isset($_GET['url'])): ?>
	  	
	  	<input class="button" type="submit" id="zapri" name="potrdi" value="Zapri"  /> <br />
	  	<small>Spremembe se shranijo brez potrjevanja.</small><br />
	  	<small>Spremembe na seznamu bodo vidne po prvi osvežitvi strani.</small>
	 	<div class="clear" style="height:20px;"></div>
	  <?php endif; ?> 	 	
	 <div id="out-slika">
		<?php
		if(isset($_GET['url']))
		{
			?>
			    <script type="text/javascript">
			    $('#thumbnail').livequery(function(){
  					$('#thumbnail').imgAreaSelect( {aspectRatio: '1:1', handles: false, resizable: true, minWidth: 300, minHeight: 300, onSelectChange: preview });
    			});
    			</script>
			<?
			$_art->formThumbnail($_GET['url']);
		}	
		?> 
	 </div>
	 
	 <br />
	 	
</div>


</body>
</html>