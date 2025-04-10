<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');

//print_r($_GET); 
$from = $handler->slo_to_date($_POST['data']['from']);
$to = $handler->slo_to_date($_POST['data']['to']);

$kuponi_postavke = $foo->getKuponiStatistika($from, $to);

?>
    <button class="btn btn-secondary" id="export_excel">Izvoz</button>
    <div class="row" id="stat_table">
        <div class="col-lg-7">
            <table class="stat_table">
            <tr>
                <th style="width:60px; font-weight:bold;"><strong>Šifra</strong></th> 
                <th style=" font-weight:bold;"><strong>Naziv</strong></th>  
                <th style="width:60px; font-weight:bold;"><strong>Količina</strong></th>
            </tr>
            <?php 
            $sum_kolicina = 0;
            if($kuponi_postavke):
                foreach ($kuponi_postavke as $key => $value):
                    $sum_kolicina += $value['kolicina'];
                ?>
                <tr>
                    <td><?php echo $value['sifra_kupona']; ?> </td>
                    <td style="padding-right:10px;"><?php echo $value['naziv']; ?> </td>
                    <td><?php echo $value['kolicina']; ?> </td>
                </tr>
                <?php 
                endforeach;
            endif; ?>
            <tr class="noExl"><td colspan="5"><div style="border-bottom:1px solid #000;"></div></td></tr>
            <tr>
                <td><strong>Skupaj:</strong></td> 
                <td></td>
                <td><?php echo $sum_kolicina; ?></td>
            </tr>
        </table>
        </div>
        <!-- <div class="col-lg-5">
        </div> -->
        