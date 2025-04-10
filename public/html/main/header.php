<!DOCTYPE HTML>
<html>
  <head>
    <title><?=$this->base->GetMetaTitle()?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=9 /">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Generator" content="CMS" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="robots" content="index,follow" />
    <meta name="token" content="<?php echo $_SESSION['token']; ?>">
    <meta name="auth" content="<?php echo $_SESSION['auth_key']; ?>">
    <meta name="main_url" content="<?php echo MAIN_URL; ?>">
    <link rel="stylesheet" type="text/css" href="/public/resources/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="/public/resources/fortawesome/css/font-awesome.min.css" />

    <script src="/public/resources/ckeditor3/ckeditor.js?t=4"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.css" />
    
    <!--[if IE 7]>
	  <link rel="stylesheet" href="/public/resources/fortawesome/css/font-awesome-ie7.min.css">
	<![endif]-->

    <link rel="stylesheet" type="text/css" href="/public/resources/css/smoothness/jquery-ui-1.10.3.custom.css" />

    <!--[if lt IE 8]>
    <style type="text/css">
    	span.ui-icon {display: block; width:16px; float:left;}
    </style>
    <![endif]-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.cookie.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/json2.js"></script>
	<script type="text/javascript" src="/public/resources/scripts/simpla.jquery.configuration.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.form.js"></script>
    <script type="text/javascript" src="/public/resources/facebox/facebox.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <script type="text/javascript" src="/public/resources/scripts/jquery.ui.datepicker-sl.js"></script>
    <script type="text/javascript" src="/public/resources/scripts/jquery.ui.timepicker.js"></script>

	<script type="text/javascript" src="/public/resources/fancybox2/jquery.fancybox.js"></script>
	<link rel="stylesheet" type="text/css" href="/public/resources/fancybox2/jquery.fancybox.css" media="screen" />
	<!-- <script type="text/javascript" src="/public/resources/scripts/purl.js"></script> -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    


   <link rel="stylesheet" type="text/css" href="/public/resources/alertify/css/alertify.css" />
   <script type="text/javascript" src="/public/resources/alertify/alertify.min.js"></script>


   <link rel="stylesheet" type="text/css" href="/public/resources/css/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="/public/resources/css/jquery.dataTables.min.css" />


   <script type="text/javascript" src="/public/resources/scripts/pdfmake.min.js"></script>
   <script type="text/javascript" src="/public/resources/scripts/vfs_fonts.js"></script>
   <script type="text/javascript" src="/public/resources/scripts/datatables.min.js"></script>
    <script type="text/javascript" src="/public/resources/CellEdit-master/js/dataTables.cellEdit.js"></script>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.jqote2/0.9.8/jquery.jqote2.min.js"></script>

    <link rel="stylesheet" href="/library/file_upload2/css/jquery.fileupload.css">

    <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <script src="/library/file_upload2/js/vendor/jquery.ui.widget.js"></script>
    <script src="/library/file_upload2/js/jquery.iframe-transport.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload-process.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload-image.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload-audio.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload-video.js"></script>
    <script src="/library/file_upload2/js/jquery.fileupload-validate.js"></script>

    <script type="text/javascript" src="/public/resources/scripts/jquery.mask.js"></script>

    <script type="text/javascript" src="/public/resources/scripts/script.js?t=5"></script>

    
    <link rel="stylesheet" type="text/css" href="/public/resources/css/style2.css?t=5" />

    <!-- <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/api/fnMultiFilter.js"></script> -->
  </head>
<body <?php if(NIVO0_NICENAME == 'login') echo 'id="login"';?>>
<?php include (ROOT . DS . 'public' . DS . 'html' . DS . 'modules' . DS . 'header.inc.php');?>
