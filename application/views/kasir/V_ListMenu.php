<style>
    .val_jenis_kategori{
        color: grey;
        font-size: .6rem;
        font-style: italic;
        font-weight: 500;
    }

    .val_nama_menu{
        color: black;
        font-size: .9rem;
        font-weight: bold;
    }

    .val_harga_item{
        color: var(--primary);
        font-weight: 600;
        font-size: 1.33rem;
    }

    .btn-min:hover{
        cursor: pointer;
        color: var(--red-delete) !important;
        transition: .2s;
    }

    .btn-plus:hover{
        cursor: pointer;
        color: green !important;
        transition: .2s;
    }
</style>
<div class="row">
    <?php if($list_menu){ foreach($list_menu as $lm){ ?>
        <div class="col-lg-3" style="display: inline-block">
            <div class="card-menu-item card card-default">
                <div class="card-header p-0">
                    <div class="row pl-1 pr-1">
                        <div class="col-lg-12 text-right" style="margin-bottom: -5px;">
                            <span class="val_jenis_kategori"><?=$lm['nama_jenis_menu'].' / '.$lm['nama_kategori_menu']?></span>
                        </div>
                        <div class="col-lg-12 text-left">
                            <h5 id="badge_qty_<?=$lm['id']?>" class="badge <?=isset($detail[$lm['id']]) ? 'badge-success' : '' ?>">
                                <?=isset($detail[$lm['id']]) ? $detail[$lm['id']]['qty'] : 0;?>
                            </h5>
                            <span class="val_nama_menu"><?=$lm['nama_menu_merchant']?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body pl-1 pr-1 pt-0 pb-0">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 text-left">
                            <span class="val_harga_item">
                                <?=formatCurrency($lm['harga'])?>
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 text-right mt-1">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <i onclick="minusMenu('<?=$lm['id']?>')" style="color: grey;" class="btn-min fa fa-minus-circle"></i>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <i onclick="plusMenu('<?=$lm['id']?>')" style="color: grey;" class="btn-plus fa fa-plus-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-0" style="background-color: white !important; border-top: 1px solid #dfdfdf;">
                    <div class="row">
                        
                    </div>
                </div>
            </div>
        </div>
    <?php } } ?>
</div>
<script>
    function minusMenu(id){
        var cur_val = parseInt($('#badge_qty_'+id).html());
        var new_val = cur_val - 1;
        if(new_val < 0){
            new_val = 0;
        }
        if(new_val == 0){
            $('#badge_qty_'+id).removeClass('badge-success')
            // $('#badge_qty_'+id).addClass('badge-secondary')
        }
        $('#badge_qty_'+id).html(new_val);
        // getListSelectedMenuFromListMenu()
        changeSelectedMenu(id, 'minus')
    }

    function plusMenu(id){
        var cur_val = parseInt($('#badge_qty_'+id).html());
        var new_val = cur_val + 1;
        if(new_val == 1){
            // $('#badge_qty_'+id).removeClass('badge-secondary')
            $('#badge_qty_'+id).addClass('badge-success')
        }
        $('#badge_qty_'+id).html(new_val);
        // getListSelectedMenuFromListMenu()
        changeSelectedMenu(id, 'plus')
    }

    function getListSelectedMenuFromListMenu(){
        // $('#div_selected_menu').html('')
        $('#div_selected_menu').load('<?=base_url('kasir/C_Kasir/getListSelectedMenu/'.$id_t_transaksi)?>', function(){

        })
    }

    function formatRupiah(angka, prefix = "Rp ") {
        var number_string = angka.toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? rupiah : "";
    }

    function changeSelectedMenu(id, type){
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/changeSelectedMenu")?>',
            method: 'post',
            data: {
                id_m_menu_merchant: id,
                type: type,
                id_t_transaksi: '<?=$id_t_transaksi?>'
            },
            success: function(data){
                let rs = JSON.parse(data)
                if(rs.code == 0){
                    $('.val_detail_total_harga').html("Rp "+formatRupiah(rs.total_harga))
                    getListSelectedMenuFromListMenu()
                } else {
                    errortoast(rs.message)
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    }

    function removeAll(id){
        $('#badge_qty_'+id).html('0')
        $('#badge_qty_'+id).removeClass('badge-success')
    }
</script>