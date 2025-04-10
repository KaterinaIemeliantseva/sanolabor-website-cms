<div id="sidebar"><div id="sidebar-wrapper">
<!-- Sidebar Profile links -->
<div id="profile-links"> <br />
	<img src="/public/resources/images/hkl_logo.png" style="width:150px;" />
	<br /><br />
    Pozdravljeni, <a href="#" title="Edit your profile"><?=$this->user->get_property('ime_priimek')?></a><br />
    <br />
    <a href="http://lek24.ha2net.com" target="_blank" title="Poglej spletno mesto">Spletno mesto</a> | <a href="/logout" title="Odjava">Odjava</a>
</div>
<?php

	$user_id = $this->user->get_property('id');
	$this->base->mainMenuCMS2(0, $user_id);
	//$this->vsebina->mainMenuCMS(0);


?>
</div></div>
