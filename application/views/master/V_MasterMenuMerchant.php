    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH MENU MERCHANT</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_menu_merchant">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Pilih Merchant</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_merchant" data-dropdown-css-class="select2-navy" name="id_m_merchant">
                            <?php if($list_merchant){
                                foreach($list_merchant as $lm){
                                ?>
                                <option value="<?=$lm['id']?>">
                                    <?=$lm['nama_merchant']?>
                                </option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Jenis Menu</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_jenis_menu" data-dropdown-css-class="select2-navy" name="id_m_jenis_menu">
                            <?php if($list_jenis_menu){
                                foreach($list_jenis_menu as $ljm){
                                ?>
                                <option value="<?=$ljm['id']?>">
                                    <?=$ljm['nama_jenis_menu']?>
                                </option>
                            <?php } } ?>
                        </select>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Kategori Menu</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_kategori_menu" data-dropdown-css-class="select2-navy" name="id_m_kategori_menu">
                            <option value="0" selected>Pilih Kategori</option>
                            <?php if($list_kategori_menu){
                                foreach($list_kategori_menu as $lkm){
                                ?>
                                <option value="<?=$lkm['id']?>">
                                    <?=$lkm['nama_jenis_menu']?>
                                </option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Menu</label>
                        <input class="form-control" autocomplete="off" name="nama_menu_merchant" id="nama_menu_merchant" required/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Harga</label>
                        <input class="form-control" autocomplete="off" name="harga" id="harga" required/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Deskripsi</label>
                        <input class="form-control" autocomplete="off" name="deskripsi" id="deskripsi"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-8 text-right">
                    <label class="bmd-label-floating" style="color: white;">..</label>
                    <button class="btn btn-sm btn-navy" type="submit"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">LIST MENU MERCHANT</h3>
    </div>
    <div class="card-body">
        <div id="list_jenis_pesan" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        $('.select2-navy').select2();

        loadAllMerchant()

        $(function(){
            <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
                errortoast('<?=$this->session->flashdata('message')?>')
            <?php } ?>
            <?php if($this->session->flashdata('message') == '0'){ ?>
                successtoast('UPDATE BERHASIL')
            <?php } ?>
        })
    })

    function loadAllMerchant(){
        $('#list_jenis_pesan').html('')
        $('#list_jenis_pesan').append(divLoaderNavy)
        $('#list_jenis_pesan').load('<?=base_url("master/C_Master/loadAllMerchant")?>', function(){
            $('#loader').hide()
        })
    }

    // $('#form_tambah_jenis_pesan').on('submit', function(e){
    //     e.preventDefault();
    //     $.ajax({
    //         url: '<?=base_url("master/C_Master/createMasterMerchant")?>',
    //         method: 'post',
    //         data: $(this).serialize(),
    //         success: function(){
    //             successtoast('Data berhasil ditambahkan')
    //             loadAllMerchant()
    //             $('#nama_jenis_pesan').val('')
    //         }, error: function(e){
    //             errortoast('Terjadi Kesalahan')
    //         }
    //     })
    // })

</script>