<div id="login-wrapper" class="png_bg">
    <div id="login-top">
    	<!-- <img src="/public/resources/images/hakl_logo.png" /> -->
		<img src="/public/resources/images/orka_logo.png" style="margin-bottom: 10px" />
    </div>
    <div id="login-content">
	<form action="#" id="login_form" method="post">
	    <p>
		<label>Uporabniško ime</label>
		<input class="text-input" id="uname" required name="uname" type="text" />
	    </p>
	    <div class="clear"></div>
	    <p>
		<label>Geslo</label>
		<input class="text-input" id="pwd" name="pwd" required type="password" />
	    </p>
	    <div class="clear"></div>
	    <p style="display: none;" id="remember-password">
		<input checked="checked" type="checkbox" name="remember" value="1" />Zapomni me
	    </p>
	    <div class="clear"></div>
	    <p>
		<input class="button" type="submit" value="Prijava" />
	    </p>
	</form>
	<div id="out"></div>
    </div>
</div>
