    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH JENIS MENU</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_jenis_menu">
            <div class="row">
                <?php if($this->general_library->isProgrammer()){ ?>
                <div class="col-4">
                    <div class="form-group">
                        <label>Pilih Merchant</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_merchant" data-dropdown-css-class="select2-navy" name="id_m_merchant">
                            <option value="0" selected>Pilih Merchant</option>
                            <?php if($list_merchant){
                                foreach($list_merchant as $lkm){
                                ?>
                                <option value="<?=$lkm['id']?>">
                                    <?=$lkm['nama_merchant']?>
                                </option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <?php } else { ?>
                    <input name="id_m_merchant" id="id_m_merchant" value="<?=$this->general_library->getIdMerchant()?>" style="display: none;" />
                <?php } ?>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Jenis Menu</label>
                        <input class="form-control" autocomplete="off" name="nama_jenis_menu" id="nama_jenis_menu"/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Keterangan</label>
                        <input class="form-control" autocomplete="off" name="deskripsi" id="deskripsi"/>
                    </div>
                </div>
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
        <h3 class="card-title">LIST JENIS MENU</h3>
    </div>
    <div class="card-body">
        <div id="list_jenis_menu" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        <?php if($this->general_library->isProgrammer()){ ?>
            $('#id_m_merchant').select2()
        <?php } ?>
        
        loadJenisMenuByMerchant()

        $(function(){
            <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
                errortoast('<?=$this->session->flashdata('message')?>')
            <?php } ?>
            <?php if($this->session->flashdata('message') == '0'){ ?>
                successtoast('UPDATE BERHASIL')
            <?php } ?>
        })
    })

    $('#id_m_merchant').on('change', function(){
        loadJenisMenuByMerchant()
    })

    function loadJenisMenuByMerchant(){
        $('#list_jenis_menu').html('')
        $('#list_jenis_menu').append(divLoaderNavy)
        $('#list_jenis_menu').load('<?=base_url("master/C_Master/loadJenisMenuByMerchant/")?>'+$('#id_m_merchant').val(), function(){
            $('#loader').hide()
        })
    }

    $('#form_tambah_jenis_menu').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("master/C_Master/createMasterJenisMenu")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                successtoast('Data berhasil ditambahkan')
                loadJenisMenuByMerchant()
                $('#nama_jenis_menu').val('')
                $('#deskripsi').val('')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

</script>