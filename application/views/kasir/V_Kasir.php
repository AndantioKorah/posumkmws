<div class="row" id="main_view_kasir" style="display: block;">
    <div class="col-lg-12">
        <button id="btn_new_transaksi" class="btn btn-block btn-navy"><i class="fa fa-plus"></i> Transaksi Baru</button>
        <button style="display: none;" id="btn_loading_new_transaksi" disabled class="btn btn-block btn-navy"><i class="fa fa-spin fa-spinner"></i> Loading...</button>
    </div>
    <div class="col-lg-12 mt-3">
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <h1 class="card-title">LIST TRANSAKSI</h1>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" format="dd/mm/YYYY" value="<?=date('Y-m-d')?>" class="form-control form-control-sm" autocomplete="off" name="date_transaksi" id="date_transaksi"/>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" id="search_field" placeholder="Cari" aria-label="Cari" aria-describedby="basic-addon1">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                    <div class="col-lg-12" id="div_list_transaksi"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="view_detail_transaksi" style="display: none;">

</div>
<script>
    let pembayaran;

    $(function(){
        loadTransaksi()
    })

    $('#date_transaksi').on('change', function(){
        loadTransaksi()
    })

    $('#btn_new_transaksi').on('click', function(){
        $('#btn_new_transaksi').hide()
        $('#btn_loading_new_transaksi').show()
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/saveTransaksi/0")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                $('#btn_new_transaksi').show()
                $('#btn_loading_new_transaksi').hide()
                let rs = JSON.parse(data)
                $('#main_view_kasir').hide()
                $('#view_detail_transaksi').show()
                $('#view_detail_transaksi').html('')
                $('#view_detail_transaksi').append(divLoaderNavy)
                $('#view_detail_transaksi').load('<?=base_url('kasir/C_Kasir/detailTransaksi/')?>'+rs.id, function(){
                    $('#loader').hide()
                })
            }, error: function(e){
                $('#btn_new_transaksi').show()
                $('#btn_loading_new_transaksi').hide()
                errortoast('Terjadi Kesalahan')
            }
        })  
    })

    $('#search_field').on('keyup', function(){
        $('#div_list_transaksi').html('')
        $('#div_list_transaksi').append(divLoaderNavy)
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/searchTransaksi/")?>'+$('#date_transaksi').val(),
            method: 'post',
            data: {
                search_field: $('#search_field').val()
            },
            success: function(data){
                $('#div_list_transaksi').html(data)
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    function loadTransaksi(){
        $('#div_list_transaksi').html('')
        $('#div_list_transaksi').append(divLoaderNavy)
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/loadListTransaksi/")?>'+$('#date_transaksi').val(),
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                $('#div_list_transaksi').html(data)
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    }
</script>