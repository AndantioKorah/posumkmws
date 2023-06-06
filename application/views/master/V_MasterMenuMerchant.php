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
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Kategori Menu</label>
                        <select class="form-control select2-navy" style="width: 100%"
                        id="id_m_kategori_menu" data-dropdown-css-class="select2-navy" name="id_m_kategori_menu">
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
        <div id="list_menu_merchant" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        $('.select2-navy').select2();

        getJenisMenuByMerchant()
        loadAllMenuMerchant()

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
        loadAllMenuMerchant()
        getJenisMenuByMerchant()
    })

    function getJenisMenuByMerchant(){
        $.ajax({
            url: '<?=base_url("master/C_Master/getJenisMenuByMerchant/")?>'+$('#id_m_merchant').val(),
            method: 'post',
            data: $(this).serialize(),
            success: function(res){
                let rs = JSON.parse(res)
                $('#id_m_jenis_menu').find('option').remove();
                $('#id_m_jenis_menu').append('<option value="0">Pilih Jenis Menu</option>')
                for(let i = 0; i < rs.length ; i++){
                    $('#id_m_jenis_menu').append('<option value="'+rs[i].id+'">'+rs[i].nama_jenis_menu+'</option>')
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    }

    $('#id_m_jenis_menu').on('change', function(){
        getKategoriMenuByJenisMenu()
    })

    function getKategoriMenuByJenisMenu(){
        $.ajax({
            url: '<?=base_url("master/C_Master/getKategoriMenuByJenisMenu/")?>'+$('#id_m_jenis_menu').val(),
            method: 'post',
            data: $(this).serialize(),
            success: function(res){
                let rs = JSON.parse(res)
                $('#id_m_kategori_menu').find('option').remove();
                $('#id_m_kategori_menu').append('<option value="0">Pilih Kategori Menu</option>')
                for(let i = 0; i < rs.length ; i++){
                    $('#id_m_kategori_menu').append('<option value="'+rs[i].id+'">'+rs[i].nama_kategori_menu+'</option>')
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    }

    function loadAllMenuMerchant(){
        $('#list_menu_merchant').html('')
        $('#list_menu_merchant').append(divLoaderNavy)
        $('#list_menu_merchant').load('<?=base_url("master/C_Master/loadAllMenuMerchant/")?>'+$('#id_m_merchant').val(), function(){
            $('#loader').hide()
        })
    }

    $('#form_tambah_menu_merchant').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("master/C_Master/createMasterMenuMerchant")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                successtoast('Data berhasil ditambahkan')
                loadAllMenuMerchant()
                $('#nama_jenis_pesan').val('')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

</script>