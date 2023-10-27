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
        <?php $total_harga = 0; if($result){ foreach($result as $rs){ 
            $total_harga += $rs['total_harga'];
            ?>
            <div class="col-lg-12" id="row_<?=$rs['id_m_menu_merchant']?>">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-left">
                        <?php if(!$pembayaran){ ?>
                            <i onclick="deleteSelectedItem('<?=$rs['id_m_menu_merchant']?>', '<?=$rs['total_harga']?>', '<?=$rs['id']?>')" style="color: pink; font-size: 12px;" class="fa fa-trash fa-delete"></i> 
                        <?php } ?>
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

    function deleteSelectedItem(id, total_harga, id_t_transaksi_detail){
        if(confirm('Apakah Anda yakin ingin menghapus item tersebut?')){
            $.ajax({
                url: '<?=base_url("kasir/C_Kasir/deleteSelectedMenu")?>',
                method: 'post',
                data: {
                    id: id_t_transaksi_detail
                },
                success: function(data){
                    let total_harga_transaksi = parseInt(data)
                    $('#row_'+id).hide()
                    removeAll(id)
                    // let new_total_harga_selected = parseInt(total_harga_transaksi) - parseInt(total_harga)
                    let new_total_harga_selected = parseInt(total_harga_transaksi)
                    // console.log("total_harga_transaksi "+total_harga_transaksi)
                    // console.log("total_harga_sent "+total_harga)
                    $('.val_detail_total_harga').html("Rp "+formatRupiah(new_total_harga_selected))      
                    $('#input_total_bayar').val(formatRupiah(new_total_harga_selected))
                    $('#input_kembalian').val("0")
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    }
</script>