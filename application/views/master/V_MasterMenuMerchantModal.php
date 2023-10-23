<div class="row p-3">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h4><?=$result['nama_menu_merchant']?></h4>
    </div>
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#edit_harga_tab" data-toggle="tab">Edit Harga</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="openStock()" href="#stock_tab" data-toggle="tab">Stock</a>
            </li>
        </ul>
    </div>
    <div class="tab-content col-12 mt-2" id="myTabContent">
        <div class="tab-pane show active" id="edit_harga_tab">
            <form id="form_edit_harga">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Harga</label>
                        <input type="number" class="form-control input_format_currency" name="harga" id="input_harga" value="<?=formatCurrencyWithoutRp($result['harga'])?>" />
                    </div>
                    <div class="col-lg-12 text-right mt-2">
                        <button id="btn_update_harga" class="btn btn-sm btn-navy"><i class="fa fa-save"></i> Update Harga</button>
                        <button style="display: none;" disabled id="btn_loading_update_harga" class="btn btn-sm btn-navy">
                            <i class="fa fa-spin fa-spinner"></i> Menyimpan...</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane" id="stock_tab">
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.input_format_currency').on('keypress', function(event){
            if(event.charCode >= 48 && event.charCode <= 57){
                return true;
            } else {
                return false;
            }
        })
    })

    function formatRupiahEditMenu(angka, prefix = "Rp ") {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
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

    $('.input_format_currency').on('keyup', function(){
        $(this).val(formatRupiahEditMenu($(this).val()))
    })

    function openStock(){
        $('#stock_tab').html('')
        $('#stock_tab').append(divLoaderNavy)
        $('#stock_tab').load('<?=base_url('master/C_Master/openStockMenuMerchant/'.$id_menu)?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_edit_harga').on('submit', function(e){
        $('#btn_update_harga').hide()
        $('#btn_loading_update_harga').show()
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("master/C_Master/editHargaMenuMerchant/".$id_menu)?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(res){
                successtoast('Harga berhasil disimpan')
                $('.harga_item_<?=$id_menu?>').html("Rp " + $('#input_harga').val())
                $('#btn_update_harga').show()
                $('#btn_loading_update_harga').hide()
            }, error: function(e){
                $('#btn_update_harga').show()
                $('#btn_loading_update_harga').hide()
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>