    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH KATEGORI MENU</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_kategori_menu">
            <div class="row">
            <?php if($this->general_library->isProgrammer()){ ?>
                <div class="col-3">
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
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Jenis Menu</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_jenis_menu" data-dropdown-css-class="select2-navy" name="id_m_jenis_menu">
                            <?php if(!$this->general_library->isProgrammer()){ foreach($list_jenis as $lj) {?>
                                <option value="<?=$lj['id']?>"><?=$lj['nama_jenis_menu']?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Kategori Menu</label>
                        <input class="form-control" autocomplete="off" name="nama_kategori_menu" id="nama_kategori_menu"/>
                    </div>
                </div>
                <div class="col-3">
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
        <h3 class="card-title">LIST KATEGORI MENU</h3>
    </div>
    <div class="card-body">
        <div id="list_kategori_menu" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        <?php if($this->general_library->isProgrammer()){ ?>
            $('#id_m_merchant').select2()
        <?php } ?>
        $('#id_m_jenis_menu').select2()

        $(function(){
            <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
                errortoast('<?=$this->session->flashdata('message')?>')
            <?php } ?>
            <?php if($this->session->flashdata('message') == '0'){ ?>
                successtoast('UPDATE BERHASIL')
            <?php } ?>
            <?php if(!$this->general_library->isProgrammer()){ ?>
                loadKategoriMenuMerchant()
            <?php } ?>
        })
    })

    $('#id_m_merchant').on('change', function(){
        $.ajax({
            url: '<?=base_url("master/C_Master/loadJenisMenuByForMasterKategori/")?>'+$(this).val(),
            method: 'post',
            data: $(this).serialize(),
            success: function(res){
                $('#id_m_jenis_menu').find('option').remove();
                let rs = JSON.parse(res)
                for(let i = 0; i < rs.length; i++){
                    $('#id_m_jenis_menu').append('<option value="'+rs[i].id+'">'+rs[i].nama_jenis_menu+'</option>')
                }
                // loadKategoriMenuMerchant($('#id_m_jenis_menu').val())
                loadKategoriMenuMerchant()
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    // $('#id_m_jenis_menu').on('change', function(){
    //     loadKategoriMenuMerchant($(this).val())
    // })

    function loadKategoriMenuMerchant(){
        $('#list_kategori_menu').html('')
        $('#list_kategori_menu').append(divLoaderNavy)
        <?php if($this->general_library->isProgrammer()){ ?>
            $('#list_kategori_menu').load('<?=base_url("master/C_Master/loadKategoriMenuMerchant/")?>'+$('#id_m_merchant').val(), function(){
                $('#loader').hide()
            })
        <?php } else { ?>
            $('#list_kategori_menu').load('<?=base_url("master/C_Master/loadKategoriMenuMerchant")?>', function(){
                $('#loader').hide()
            })
        <?php } ?>
    }

    $('#form_tambah_kategori_menu').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("master/C_Master/createMasterKategoriMenu")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                successtoast('Data berhasil ditambahkan')
                loadKategoriMenuMerchant()
                $('#nama_kategori_menu').val('')
                $('#deskripsi').val('')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

</script>