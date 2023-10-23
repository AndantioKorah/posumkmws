<form id="form_input_stock">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h6 class="jumlah_stock"></h6>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <label>Tanggal Masuk</label>
            <input readonly value="<?=date('Y-m-d H:i:s')?>" name="tanggal" class="form-control form-control-sm" id="tanggal" />
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <label>Jumlah Barang</label>
            <input type="number" name="jumlah_barang" class="form-control form-control-sm" required id="jumlah" />
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <button id="btn_submit_stock" class="btn btn-navy btn-sm" style="margin-top: 30px;"><i class="fa fa-save"></i> Simpan</button>
            <button id="btn_loading_stock" disabled style="display: none;" class="btn btn-navy btn-sm" style="margin-top: 30px;"><i class="fa fa-spin fa-spinner"></i> Menyimpan...</button>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-lg-12" id="div_stock"></div>
</div>
<script>
    $(function(){
        $('#tanggal').datetimepicker({
            format: 'yyyy-mm-dd H:i:s',
            todayBtn: true,
            autoclose: true,
        })

        loadStock()
    })

    function loadStock(){
        $('#div_stock').html('')
        $('#div_stock').append(divLoaderNavy)
        $('#div_stock').load('<?=base_url('master/C_Master/loadStockMenuMerchant/'.$id_menu)?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_input_stock').on('submit', function(e){
        e.preventDefault()
        $('#btn_submit_stock').hide()
        $('#btn_loading_stock').show()
        $.ajax({
            url: '<?=base_url("master/C_Master/inputStockMenuMerchant/".$id_menu)?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                loadStock()
                $('#jumlah').val('')
                successtoast('Data sudah tersimpan')
                $('#btn_submit_stock').show()
                $('#btn_loading_stock').hide()
            }, error: function(e){
                $('#btn_submit_stock').show()
                $('#btn_loading_stock').hide()
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>