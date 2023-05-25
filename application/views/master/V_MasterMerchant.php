    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH MERCHANT</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_jenis_pesan" action="<?=base_url("master/C_Master/createMasterMerchant")?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Merchant</label>
                        <input class="form-control" autocomplete="off" name="nama_merchant" id="nama_merchant" required/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Alamat</label>
                        <input class="form-control" autocomplete="off" name="alamat" id="alamat"/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Logo</label>
                        <input class="form-control" accept="image/x-png,image/gif,image/jpeg" type="file" name="logo_merchant" id="logo_merchant">
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
        <h3 class="card-title">LIST MERCHANT</h3>
    </div>
    <div class="card-body">
        <div id="list_jenis_pesan" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
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