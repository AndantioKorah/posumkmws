<style>
    .val_selected_nama_menu{
        color: black;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .val_selected_qty{
        color: grey;
        font-size: 1rem;
        font-weight: 500;
    }

    .val_selected_total_harga{
        color: black;
        font-size: 1rem;
        font-weight: bold;
    }

    .fa-delete:hover{
        cursor: pointer;
        transition: .2s;
        color: var(--red-delete) !important;
    }
</style>
<?php if($result){ foreach($result as $rs){ ?>
    <div class="col-lg-12" id="row_<?=$rs['id_m_menu_merchant']?>">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-left">
                <i onclick="deleteSelectedItem('<?=$rs['id_m_menu_merchant']?>')" style="color: pink; font-size: 12px;" class="fa fa-trash fa-delete"></i> 
                <span class="val_selected_nama_menu"><?=$rs['nama_menu_merchant']?></span>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 text-left">
                <span class="val_selected_qty"><?=$rs['qty'].' x '.formatCurrencyWithoutRp($rs['harga'])?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                <span class="val_selected_total_harga"><?=formatCurrencyWithoutRp($rs['total_harga'])?></span>
            </div>
        </div>
    </div>
<?php } } ?>
<script>
    $(function(){
    })

    function deleteSelectedItem(id){
        if(confirm('Apakah Anda yakin ingin menghapus item tersebut?')){
            $('#row_'+id).hide()
            removeAll(id)
        }
    }
</script>