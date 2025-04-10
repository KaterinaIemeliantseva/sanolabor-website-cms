<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
$_POST['name'] = 'statistika_sifre';

//print_r($_GET); 
$from = $handler->slo_to_date($_POST['data']['from']);
$to = $handler->slo_to_date($_POST['data']['to']);


$placilo_kreditna_kartica = $foo->getNarociloPoNacinuPlacila(45, $from, $to);
$placilo_po_povzetju = $foo->getNarociloPoNacinuPlacila(39, $from, $to);
$placilo_predracun = $foo->getNarociloPoNacinuPlacila(41, $from, $to);

if(isset($_POST['checked']) && !empty($_POST['checked'])){
    $narocila_postavke = $foo->getNarocilaPostavke($from, $to, $_POST['checked']);
}else{
    $narocila_postavke = $foo->getNarocilaPostavke($from, $to);
}

?>
<button class="btn btn-secondary" id="export_excel">Izvoz</button>
<div class="row" id="stat_table">
    <div class="col-lg-12">

        <script>
        jQuery(document).ready(function($) {
            $('#statistika').DataTable({
                 aLengthMenu: [
                    [25, 50, -1],
                    [25, 50, "Vse"]
                ],
                iDisplayLength: 25,
                order: [[ 4, "desc" ]],
            //    columnDefs: [{ "targets": [ 4 ], "visible": false }],
               "language": {
                    "paginate": {
                    "previous": "Pred.",
                    "next": "Nasl.",
                    },
                "info": "Prikazujem _START_ to _END_ od _TOTAL_ zapisov",
                "lengthMenu": "Prikaži _MENU_ zapisov",
                "search": "Iskanje:"
                }
            });

            $('.filter').on('change', function(){
                var table = $('#statistika').DataTable();
                var elements = "";
                if($('.filter')[0].checked == true || $('.filter')[1].checked == true || $('.filter')[2].checked == true){
                    $('.filter').each(function(index, elem){
                        if(elem.checked){
                            if(elements == "")
                            {
                                elements = elem.value;
                            }
                            else{
                                elements += '|'+elem.value;              
                            }
                        }
                    })
                    <?php $dostava_check = ', nacin_dostave_opis'; $narocila_postavke = $foo->getNarocilaPostavke($from, $to, $dostava_check); ?>
                    var dostava = table
                            .columns(3).search(elements, true, false)
                            .draw();
                }else{
                   
                    var dostava = table
                        .columns(3).search('', true, false)
                        .draw();
                        console.log(dostava);
                }
            });
        } );

        </script>

        <table class="display" id="statistika">

            &emsp;
            <input type="checkbox" id="dostava_posta_slovenije" class="filter" name="posta_slovenije" value="Dostava s pošto slovenije">
            <label for="dostava_posta_slovenije"> &nbsp;Dostava s pošto slovenije</label>
            &emsp;
            <input type="checkbox" id="osebni_prevzem" class="filter" name="osebni_prevzem" value="Osebni prevzem">
            <label for="osebni_prevzem"> &nbsp;Osebni prevzem</label>
            &emsp;
            <input type="checkbox" id="paketomat" class="filter" name="paketomat" value="Dostava na izbrani Paketomat">
            <label for="paketomat"> &nbsp;Dostava na izbrani Paketomat</label>


            <thead>
                <tr>
                    <th style="width:60px; font-weight:bold;"><strong>Šifra</strong></th> 
                    <th style=" font-weight:bold;"><strong>Naziv</strong></th>  
                    <th style="width:100px; font-weight:bold;"><strong>Znesek</strong></th> 
                    <th style="width:100px; font-weight:bold;"><strong>Dostava</strong></th> 
                    <th style="width:60px; font-weight:bold;"><strong>Količina</strong></th>
                    <th style="width:60px; font-weight:bold;"><strong>Kategorija</strong></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $sum_znesek = 0;
            $sum_kolicina = 0;
            if($narocila_postavke):
                foreach ($narocila_postavke as $key => $value):
                    $sum_znesek += $value['placilo'];
                    $sum_kolicina += $value['kolicina'];
                ?>
                
                <tr>
                    <td><?php echo $value['sifra']; ?> </td>
                    <td style="padding-right:10px;"><?php echo $value['naziv']; ?> </td>
                    <td><?php echo number_format($value['placilo'], 2, ',', '.'); ?> € </td>
                    <td><?php echo $value['nacin_dostave']; ?> </td>
                    <td><?php echo $value['kolicina']; ?> </td>
                    <td><?php echo $value['kategorija_naziv']; ?> </td>
                </tr>
                
                <?php 
                endforeach;
            endif; ?>

            </tbody>
        </table>
        <table class="stat_table">
            <tr>
                <td><strong>Skupaj:</strong></td> 
                <td></td>
                <td></td>
                <td style ="text-align:end;"><?php echo number_format($sum_znesek, 2, ',', '.'); ?> € </td>
                <td></td>
                <td style ="text-align:center;"><?php echo $sum_kolicina; ?></td>
                <td></td>

            </tr>  
        </table>


    </div>
    

</div>
