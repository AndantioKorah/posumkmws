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

    .input-detail-transaksi{
        height: 1.5rem;
        font-size: 1rem;
        padding: 0;
        /* color: black; */
        padding-bottom: .5rem;
        border: 0;
        font-weight: bold;
        border-bottom: 1px solid grey;
        border-radius: 0;
        background-color: transparent !important;
    }

    .input-detail-transaksi:hover{
        cursor: pointer;
    }

    .label-lunas-detail-transaksi{
        font-size: 1.1rem;
        font-weight: bold;
        color: black;
    }

    .card-lunas-detail-transaksi, .card-lunas-detail-transaksi span{
        background-color: var(--primary);
        color: white !important;
    }

    @media  (min-width: 992px) {
        #card_total_transaksi, .card-detail-transaksi{
            height: 80vh !important;
        }

        #div_pembayaran{
            height: 35vh;
        }
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
                <div class="card card-default card-detail-transaksi">
                    <div class="card-header <?=$pembayaran ? 'card-lunas-detail-transaksi' : '';?>">
                        <form id="form_data_transaksi">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 text-left">
                                    <span class="lbl_detail">Nama:</span><br>
                                    <?php if($pembayaran){ ?>
                                        <span class="label-lunas-detail-transaksi"><?=$transaksi['nama']?></span>
                                    <?php } else { ?>
                                        <input id="input_nama" name="nama" class="form-control input-detail-transaksi form-control-sm" value="<?=$transaksi['nama']?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 text-center">
                                    <span class="lbl_detail">No. Transaksi:</span><br>
                                    <span class="val_detail"><?=$transaksi['nomor_transaksi']?></span>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 text-center">
                                    <span class="lbl_detail">Status:</span><br>
                                    <span class="val_detail"><?=$transaksi['status_transaksi']?></span>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                                    <span class="lbl_detail">Tanggal:</span><br>
                                    <?php if($pembayaran){ ?>
                                        <span class="label-lunas-detail-transaksi"><?=formatDate($transaksi['tanggal_transaksi'])?></span>
                                    <?php } else { ?>
                                        <input id="input_tanggal_transaksi" name="tanggal_transaksi" readonly class="datetimepickermaxtodaythistransaksi input-detail-transaksi form-control form-control-sm" 
                                        style="text-align: right;" value="<?=formatDateForEdit($transaksi['tanggal_transaksi'])?>" />
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
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
                <div class="card card-default" id="card_total_transaksi">
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
                        <div id="div_pembayaran" class="col-lg-12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?> 
<script>
    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms, 5 seconds for example
    var input_nama = $('#input_nama');

    $(function(){
        pembayaran = JSON.parse('<?=json_encode($pembayaran)?>')
        loadListMenu()
        getListSelectedMenu()
        loadPembayaran()
        $('.datetimepickermaxtodaythistransaksi').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true,
            todayHighlight: true,
            todayBtn: true,
            endDate: new Date()
        })
    })

    //on keyup, start the countdown
    input_nama.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    input_nama.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping () {
        $('#form_data_transaksi').submit()    
    }

    $('.datetimepickermaxtodaythistransaksi').on('change', function(){
        $('#form_data_transaksi').submit()    
    })

    $('#form_data_transaksi').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/saveTransaksi/".$transaksi['id'])?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })    

    $('#input_cari_menu').on('keyup', function(){
        $('#div_list_menu').html('')
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/searchMenuMerchant/".$transaksi['id'])?>',
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