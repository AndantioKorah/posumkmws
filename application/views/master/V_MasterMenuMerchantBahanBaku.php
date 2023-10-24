<div class="row">
    <div class="col-lg-12">
        <form id="form_input_bahan_baku">
            <div class="row">
                <div class="col-lg-6">
                    <label>Pilih Bahan Baku</label>
                    <select class="form-control select2-navy" style="width: 100%"
                    id="id_m_bahan_baku" data-dropdown-css-class="select2-navy" name="id_m_bahan_baku">
                        <?php if($list_bahan_baku){
                            foreach($list_bahan_baku as $lkm){
                            ?>
                            <option value="<?=$lkm['id']?>">
                                <?=$lkm['nama_bahan_baku'].' ('.$lkm['satuan'].')'?>
                            </option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label>Takaran</label>
                    <input class="form-control" type="number" name="takaran" id="takaran" />
                </div>
                <div class="col-lg-12 text-right mt-2">
                    <button class="btn btn-sm btn-navy"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12" id="list_takaran">
    </div>
</div>
<script>
    $(function(){
        $('#id_m_bahan_baku').select2()
        loadBahanBakuMenuMerchant()
    })

    function loadBahanBakuMenuMerchant(){
        $('#list_takaran').html('')
        $('#list_takaran').append(divLoaderNavy)
        $('#list_takaran').load('<?=base_url('master/C_Master/loadBahanBakuMenuMerchant/').$id_m_menu_merchant?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_input_bahan_baku').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("master/C_Master/saveBahanBakuMenuMerchant/".$id_m_menu_merchant)?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(res){
                loadBahanBakuMenuMerchant()
                $('#takaran').val('')
                successtoast('Data berhasil disimpan')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>