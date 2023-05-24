<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">TAMBAH USER</h3>
    </div>
    <div class="card-body">
        <form id="form_tambah_user">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama</label>
                        <input required class="form-control" autocomplete="off" name="nama" id="nama"/>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nomor HP</label>
                        <input required class="form-control" autocomplete="off" name="no_hp" id="no_hp"/>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Username</label>
                        <input required class="form-control" autocomplete="off" name="username" id="username"/>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="bmd-label-floating">Password</label>
                        <input required class="form-control" autocomplete="off" type="password" name="password" id="password"/>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="bmd-label-floating">Konfirmasi Password</label>
                        <input required class="form-control" autocomplete="off" type="password" name="konfirmasi_password" id="konfirmasi_password"/>
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
        <h3 class="card-title">LIST USER</h3>
    </div>
    <div class="card-body">
        <div id="list_users" class="row">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_auth" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div id="modal-dialog" class="modal-dialog modal-sm">
		<div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Need Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="need_password_form">
                    <div class="row">
                        <div class="col-12">
                            <label>Password:</label>
                            <input class="form-control form-control-sm" autocomplete="off" name="password" type="password" />
                        </div>
                        <div class="col-12 text-right mt-3">
                            <button type="submit" class="btn btn-sm btn-navy">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
<script>
    $(function(){
        loadUsers()
    })

    function loadUsers(){
        $('#list_users').html('')
        $('#list_users').append(divLoaderNavy)
        $('#list_users').load('<?=base_url("user/C_User/loadUsers")?>', function(){
            $('#loader').hide()
        })
    }

    $('#need_password_form').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("user/C_User/needPassword")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let resp = JSON.parse(data)
                if(resp['message'] == 'true'){
                    createUser()
                } else {
                    errortoast('Mo ba apa so?')
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    // $('#form_tambah_user').on('submit', function(e){
    //     e.preventDefault();
    //     let role = $('#id_m_role').val().split(';')
    //     if(role[1] == 'programmer'){
    //         $('#modal_auth').modal()
    //     } else {
    //         createUser()
    //     }
    // })

    $('#form_tambah_user').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("user/C_User/createUser")?>',
            method: 'post',
            data: $('#form_tambah_user').serialize(),
            success: function(data){
                let resp = JSON.parse(data)
                if(resp['message'] == '0'){
                    loadUsers()
                    $('#nama').val('')
                    $('#username').val('')
                    $('#password').val('')
                    $('#no_hp').val('')
                    $('#konfirmasi_password').val('')
                } else {
                    errortoast(resp['message'])
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>