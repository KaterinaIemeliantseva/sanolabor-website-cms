<div id="footer">

  <div class="clear"></div>
</div>
<script type="text/javascript">
	$("a.link").button();
	
	$(document).tooltip({
		items : ".thumbnail",
		position : {
			my : "left top+45",
			at : "center top"
		},
		content : function() {'use strict';
	
			var element = $(this), nsrc = '';
			nsrc = element.attr('src').replace("width=50", "width=250");
			return "<img class='map'  src='" + nsrc + "' />";
		}
	});
</script>