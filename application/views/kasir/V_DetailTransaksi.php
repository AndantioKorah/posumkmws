<style>
    .lbl_detail{
        color: grey;
        font-weight: 500;
        font-style: italic;
        font-size: .7rem;
    }

    .val_detail{
        color: black;
        font-weight: bold;
        font-size: .9rem;
    }

    .val_detail_total_harga{
        color: black;
        font-weight: bold;
        font-size: 3rem;
    }

    .lbl_total_harga{
        color: grey;
        font-weight: 500;
        font-size: .8rem;
        font-style: italic;
    }
</style>
<div class="col-lg-6">
    <button id="btn_back" class="btn btn-sm btn-outline-navy"><i class="fa fa-arrow-left"></i> Kembali</button>
</div>
<div class="col-lg-6 text-right">
    <button id="btn_refresh" class="btn btn-sm btn-outline-success"><i class="fa fa-redo"></i> Refresh</button>
</div>
<?php if($transaksi){ ?>
    <div class="col-lg-12 mt-2">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-4 text-left">
                                <span class="lbl_detail">Nama:</span><br>
                                <span class="val_detail"><?=$transaksi['nama']?></span>
                            </div>
                            <div class="col-lg-4 text-center">
                                <span class="lbl_detail">Status:</span><br>
                                <span class="val_detail"><?=$transaksi['status_transaksi']?></span>
                            </div>
                            <div class="col-lg-4 text-right">
                                <span class="lbl_detail">Tanggal:</span><br>
                                <span class="val_detail"><?=formatDate($transaksi['tanggal_transaksi'])?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="input_cari_menu" placeholder="Cari Menu" aria-label="Cari Menu" aria-describedby="basic-addon1">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2" id="div_list_menu">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-default" style="height: 80vh !important;">
                    <div class="card-header pl-0 pr-0 pt-1 pb-1">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h5 class="lbl_total_harga">TOTAL TRANSAKSI</h5>
                            </div>
                            <div class="col-lg-12 text-center">
                                <h3 class="val_detail_total_harga">
                                    <?=formatCurrency($transaksi['total_harga'])?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body row p-2">
                        <div class="col-lg-12" id="div_selected_menu" style="
                                height: 30vh !important;
                                overflow-y: scroll;
                                overflow-x: hidden    
                            "></div>
                        <div class="col-lg-12 p-0">
                            <hr style="">
                        </div>
                        <div id="div_pembayaran" class="col-lg-12" style="
                            height: 35vh;
                        ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?> 
<script>
    $(function(){
        loadListMenu()
        getListSelectedMenu()
        loadPembayaran()
    })

    $('#input_cari_menu').on('keyup', function(){
        $('#div_list_menu').html('')
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/searchMenuMerchant")?>',
            method: 'post',
            data: {
                search: $('#input_cari_menu').val()
            },
            success: function(data){
                $('#div_list_menu').html(data)
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    function loadPembayaran(){
        $('#div_pembayaran').load('<?=base_url('kasir/C_Kasir/getPembayaranTransaksi/'.$transaksi['id'])?>', function(){
        })
    }
    
    function getListSelectedMenu(){
        // $('#div_selected_menu').html('')
        $('#div_selected_menu').load('<?=base_url('kasir/C_Kasir/getListSelectedMenu/'.$transaksi['id'])?>', function(){

        })
    }

    function loadListMenu(){
        $('#div_list_menu').html('')
        $('#div_list_menu').load('<?=base_url('kasir/C_Kasir/loadListMenu/'.$transaksi['id'])?>', function(){

        })
    }

    $('#btn_back').on('click', function(){
        $('#main_view_kasir').show()
        $('#view_detail_transaksi').html('')
        $('#view_detail_transaksi').hide()
        loadTransaksi()
    })

    $('#btn_refresh').on('click', function(){
        detailTransaksi('<?=$transaksi['id']?>')
    })
</script>