<?php
    if($user){
?>
    <div class="row p-2">
        <div class="col-12">
            <h5><strong><?=$user['username']?></strong></h5>
        </div>
        <div class="col-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#role_tab" data-toggle="tab">Role</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#merchant_tab" data-toggle="tab">Merchant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#password_tab" data-toggle="tab">Password</a>
                </li>
            </ul>
        </div>
        <div class="tab-content col-12" id="myTabContent">
            <div class="tab-pane show active" id="role_tab">
                <div class="row">
                    <div class="col-12">
                        <form id="form_tambah_role">
                            <label>Pilih Role:</label>
                            <select class="form-control form-control-sm select2_this select2-navy" data-dropdown-css-class="select2-navy" name="id_m_role" id="id_m_role">
                                <option value="0" disabled selected>Pilih Item</option>
                                <?php
                                    $exlcude = ['programmer', 'walikota', 'setda']; 
                                    if($roles){ foreach($roles as $r){ 
                                        if((!$this->general_library->isProgrammer() && !in_array($r['role_name'], $exlcude)) || $this->general_library->isProgrammer()){ 
                                    ?>
                                    <!-- <option value="<?=$r['id']?>"><?=$r['nama'].' ('.$r['role_name'].')'?></option> -->
                                    <option value="<?=$r['id']?>"><?=$r['nama']?></option>
                                <?php } } } ?>
                            </select>
                            <input style="display: none;" class="form-control form-control-sm" name="id_m_user" value="<?=$user['id_m_user']?>"/>
                            <button class="btn btn-sm btn-navy float-right mt-3"><i class="fa fa-save"></i> Simpan</button>
                        </form>
                    </div>
                    <div class="col-12"><hr></div>
                    <div class="col-12">
                        <label>Role:</label>
                        <div id="list_role" class="table-responsive"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="merchant_tab">
                <div class="row">
                    <div class="col-12">
                        <form id="form_ganti_merchant">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Merchant Saat Ini:</label><br>
                                    <input id="nama_merchant_now" class="form-control" disabled value="<?=$user['nama_merchant']?>" />
                                </div>
                                <div class="col-lg-6">
                                    <label>Pilih Merchant:</label><br>
                                    <select style="width: 100%;" class="form-control select2_this select2-navy" 
                                        data-dropdown-css-class="select2-navy" name="id_m_merchant" id="id_m_merchant">
                                            <?php foreach($list_merchant as $m){ ?>
                                                <option <?=$m['id'] == $user['id_m_merchant'] ? 'selected' : '';?> value="<?=$m['id'].';'.$m['nama_merchant']?>"><?=$m['nama_merchant']?></option>
                                            <?php } ?>
                                    </select>
                                <button class="btn btn-sm btn-navy float-right mt-3"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="password_tab">
                <div class="row">
                    <div class="col-12">
                        <form id="form_ganti_password">
                            <div class="row">
                                <div class="col-6">
                                    <label>Password Baru:</label>
                                    <input class="form-control password_input" name="new_password" type="password" />
                                </div>
                                <div class="col-6">
                                    <label>Konfirmasi Password Baru:</label>
                                    <input class="form-control password_input" name="confirm_new_password" type="password" />
                                </div>
                                <div class="col-9"></div>
                                <div class="col-3 text-right">
                                    <input style="display: none;" class="form-control form-control-sm" name="id_m_user" value="<?=$user['id_m_user']?>"/>
                                    <button class="btn btn-sm btn-navy float-right mt-3"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('.select2_this').select2()
        loadListRole('<?=$user['id_m_user']?>')
    })

    function loadListRole(id){
        $('#list_role').html('')
        $('#list_role').append(divLoaderNavy)
        $('#list_role').load('<?=base_url("user/C_User/loadRoleForUser")?>'+'/'+id, function(){

        })
    }

    $('#form_ganti_merchant').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("user/C_User/changeMerchantUser/".$user['id_m_user'])?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                $('#nama_merchant_now').val(rs.nama_merchant)                
                $('#nama_merchant_user_list_'+'<?=$user['id_m_user']?>').html(rs.nama_merchant)                
                successtoast('Berhasil')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    $('#form_ganti_password').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("user/C_User/userChangePassword")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                $('.password_input').val('')
                if(rs.code == 0){
                    successtoast('Berhasil mengganti Password')
                } else {
                    errortoast(rs.message)
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    $('#form_tambah_role').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("user/C_User/addRoleForUser")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                if(rs.code == 0){
                    successtoast('Berhasil menambahkan role')
                } else {
                    errortoast(rs.message)
                }
                loadListRole('<?=$user['id_m_user']?>')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>