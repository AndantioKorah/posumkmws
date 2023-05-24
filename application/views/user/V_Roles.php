<div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH ROLE</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_role">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Role</label>
                        <input class="form-control" autocomplete="off" name="nama" id="nama"/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Role</label>
                        <input class="form-control" autocomplete="off" name="role_name" id="role_name"/>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Keterangan</label>
                        <input class="form-control" autocomplete="off" name="keterangan" id="keterangan"/>
                    </div>
                </div>
                    <div class="col-8"></div>
                <div class="col-4 text-right mt-2">
                    <button class="btn btn-sm btn-navy" type="submit"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">LIST ROLE</h3>
    </div>
    <div class="card-body">
        <div id="list_role" class="row">
        </div>
    </div>
</div>

<script>
    $(function(){
        loadRoles()
    })

    function loadRoles(){
        $('#list_role').html('')
        $('#list_role').append(divLoaderNavy)
        $('#list_role').load('<?=base_url("user/C_User/loadRoles")?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_tambah_role').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("user/C_User/createRole")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                loadRoles()
                $('#role_name').val('')
                $('#nama').val('')
                $('#keterangan').val('')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>