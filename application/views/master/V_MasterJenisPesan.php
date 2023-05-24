<div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH JENIS PESAN</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_jenis_pesan">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Jenis Pesan</label>
                        <input class="form-control" autocomplete="off" name="jenis_pesan" id="jenis_pesan" required/>
                    </div>
                </div>
                
                <div class="col-2 text-left">
                    <label class="bmd-label-floating" style="color: white;">..</label>
                    <button class="btn btn-block btn-navy" type="submit"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">LIST JENIS PESAN</h3>
    </div>
    <div class="card-body">
        <div id="list_jenis_pesan" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        loadJenisPesan()
    })

    function loadJenisPesan(){
        $('#list_jenis_pesan').html('')
        $('#list_jenis_pesan').append(divLoaderNavy)
        $('#list_jenis_pesan').load('<?=base_url("master/C_Master/loadJenisPesan")?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_tambah_jenis_pesan').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("master/C_Master/createMasterJenisPesan")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                successtoast('Data berhasil ditambahkan')
                loadJenisPesan()
                $('#nama_jenis_pesan').val('')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

</script>