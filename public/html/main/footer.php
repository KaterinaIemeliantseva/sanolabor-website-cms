<?php include (ROOT . DS . 'public' . DS . 'html' . DS . 'modules' . DS . 'footer.inc.php'); ?>
<script type="text/x-jqote-template" id="fileUploadRowHeader">
<![CDATA[
    <tr id="f<*=this.id*>" class="" >
        <td class="title"><label>Naziv</label></td>
        <td class="sort"><label>Vrstni red</label></td>
        <td>
        </td>
        <td></td>
    </tr>
]]>
</script>
<script type="text/x-jqote-template" id="fileUploadRow">
<![CDATA[
    <tr id="f<*=this.id*>" data-uid="<*=this.id*>" class="" g="<*=this.type*>">
        <* if ( this.type != 13) { *>
        <td class="title"><label><input value="<*=this.title*>" class="file_title" data-fid="<*=this.id*>" /></label></td>
        <td class="sort"><label><input value="<*=this.sort*>" class="file_sort" data-fid="<*=this.id*>" /></label></td>
        <* } *>
        <td>
            <* if ( this.thumbnail != '' &&  this.thumbnail !== undefined &&  this.thumbnail !== null) { *>
                <a href="<*=this.url*>"><img src="<*=this.thumbnail*>" /></a>
            <* } else { *>
                <a href="https://www.sanolabor.si<*=this.url*>"><img style="width:150px;" src="<*=this.url*>" /></a>
            <* } *>
        </td>
        <td><a href="#" class="odstraniFileRow" data-url="<*=this.url*>"   data-uid="<*=this.id*>" data-itemId="<*=this.item_id*>" data-type="<*=this.type*>" data-boxid="f<*=this.id*>" title="Izbriši"><i class="icon-trash icon-1x"></i></a></td>
    </tr>
]]>

</script>

<script type="text/javascript" src="/public/resources/scripts/administracija.js?t=7"></script>
<?php if(defined('NIVO1_NICENAME')): ?>
<script type="text/javascript" src="/public/html/controller/<?php  echo NIVO0_NICENAME; ?>/<?php  echo NIVO1_NICENAME; ?>.js?t=1"></script>
<?php endif; ?>
</body>
</html>
