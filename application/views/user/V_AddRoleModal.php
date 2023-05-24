<?php
    if($user){
?>
    <div class="row p-2">
        <div class="col-12">
            <h5>Tambah Role untuk username: <strong><?=$user['username'].' ('.$user['nama'].')'?></strong></h5>
        </div>
        <div class="col-12"><hr></div>
        <div class="col-12">
            <form id="form_tambah_role">
                <label>Pilih Role:</label>
                <select class="form-control form-control-sm select2_this select2-navy" data-dropdown-css-class="select2-navy" name="id_m_role" id="id_m_role">
                    <option value="0" disabled selected>Pilih Item</option>
                    <?php if($roles){ foreach($roles as $r){ ?>
                        <option value="<?=$r['id']?>"><?=$r['nama'].' ('.$r['role_name'].')'?></option>
                    <?php } } ?>
                </select>
                <input style="display: none;" class="form-control form-control-sm" id="id_m_user" name="id_m_user" value="<?=$user['id']?>"/>
                <button class="btn btn-sm btn-navy float-right mt-3"><i class="fa fa-save"></i> Simpan</button>
            </form>
        </div>
        <div class="col-12"><hr></div>
        <div class="col-12">
            <label>Role:</label>
            <div id="list_role"></div>
        </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('.select2_this').select2()
        loadListRole('<?=$user['id']?>')
    })

    function loadListRole(id){
        $('#list_role').html('')
        $('#list_role').append(divLoaderNavy)
        $('#list_role').load('<?=base_url("user/C_User/loadRoleForUser")?>'+'/'+id, function(){

        })
    }

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
                loadListRole('<?=$user['id']?>')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>