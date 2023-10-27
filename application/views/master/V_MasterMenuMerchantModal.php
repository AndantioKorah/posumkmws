<div class="row p-3">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h4 class="title_nama_menu_merchant"><?=$result['nama_menu_merchant']?></h4>
    </div>
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#data_menu_merchant_tab" data-toggle="tab">Data Menu Merchant</a>
            </li>
            <?php if($result['stock'] == 1){ ?>            
                <li class="nav-item">
                    <a class="nav-link" onclick="openStock()" href="#stock_tab" data-toggle="tab">Stock</a>
                </li>
            <?php } ?>
            <?php if($result['stock'] == 0){ ?>
                <li class="nav-item">
                    <a class="nav-link" onclick="openBahanBaku()" href="#bahan_baku_tab" data-toggle="tab">Bahan Baku</a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="tab-content col-12 mt-2" id="myTabContent">
        <div class="tab-pane show active" id="data_menu_merchant_tab">
            <form id="form_edit_harga">
                <div class="row">
                    <div class="col-lg-6">
                        <label>Nama Menu Merchant</label>
                        <input class="form-control" name="nama_menu_merchant" id="input_nama_menu_merchant" value="<?=($result['nama_menu_merchant'])?>" />
                    </div>
                    <div class="col-lg-6">
                        <label>Harga</label>
                        <input type="number" class="form-control input_format_currency" name="harga" id="input_harga" value="<?=formatCurrencyWithoutRp($result['harga'])?>" />
                    </div>
                    <div class="col-lg-12 text-right mt-2">
                        <button id="btn_update_harga" class="btn btn-sm btn-navy"><i class="fa fa-save"></i> Simpan</button>
                        <button style="display: none;" disabled id="btn_loading_update_harga" class="btn btn-sm btn-navy">
                            <i class="fa fa-spin fa-spinner"></i> Menyimpan...</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane" id="stock_tab">
        </div>
        <div class="tab-pane" id="bahan_baku_tab">
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

    function openBahanBaku(){
        $('#bahan_baku_tab').html('')
        $('#bahan_baku_tab').append(divLoaderNavy)
        $('#bahan_baku_tab').load('<?=base_url('master/C_Master/openBahanBakuMenuMerchant/'.$id_menu)?>', function(){
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
                $('.nama_menu_merchant_item_<?=$id_menu?>').html($('#input_nama_menu_merchant').val())
                $('.title_nama_menu_merchant').html($('#input_nama_menu_merchant').val())
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