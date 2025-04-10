<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/application/bal/ArtikelRevizijaBAL.php');

$bal = new ArtikelRevizijaBAL();
?>

<div id="magic_box" data-c="ArtikelRevizija" class="content-box">
    <div class="content-box-header"><h3><?php echo $this->dobiKategorijaNaziv(); ?></h3> </div>
    <div class="content-box-content">
    	<div class="tab-content default-tab" >
        <table id="seznam" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Naziv artikla</th>
                        <th>Dobavitelj (naziv)</th>
                        <th>Dobavitelj (up. ime)</th>
                        <th>Čas spremembe</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
