<?php
    if($list_user_role){
?>
    <table class="table table-hover table-striped table-sm data_table_role_modal">
        <thead>
            <th class="text-center">No</th>
            <th>Nama Role</th>
            <th>Role</th>
            <th>Keterangan</th>
            <th class="text-center">Pilihan</th>
        </thead>
        <?php $no = 1; foreach($list_user_role as $lr){ ?>
            <tr>
                <td class="text-center"><?=$no?></td>
                <td><?=$lr['is_default'] == 1 ? $lr['nama_role'].' <strong>(default)</strong>' : $lr['nama_role']?></td>
                <td><?=$lr['role']?></td>
                <td><?=$lr['keterangan']?></td>
                <td class="text-center">
                    <?php if($lr['is_default'] == 0){ ?>
                        <button onclick="setDefault('<?=$lr['id']?>')" class="btn btn-sm btn-info"
                        data-tooltip-role="tooltip" title="Set Default"><i class="fa fa-check"></i></button>
                    <?php } ?>
                    <button onclick="deleteRole('<?=$lr['id']?>')" class="btn btn-sm btn-danger"
                    data-tooltip-role="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        <?php $no++; } ?>
    </table>
<?php } else { ?>
    <center><label><i class="fa fa-exclamation"></i> Belum ada role</label></center>
<?php } ?>
<script>
    $(function(){
        let table = $('.data_table_role_modal').DataTable({
            responsive: false
        });
        // $('[data-tooltip-role="tooltip"]').tooltip();
    })

    function deleteRole(id){
        if(confirm('Apakah Anda yakin ingin menghapus data?')){
            $.ajax({
                url: '<?=base_url("user/C_User/deleteRoleForUser")?>'+'/'+id,
                method: 'post',
                data: $(this).serialize(),
                success: function(data){
                    successtoast('Berhasil menghapus Role')
                    loadListRole('<?=$id_m_user?>')
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    }

    function setDefault(id){
        $.ajax({
            url: '<?=base_url("user/C_User/setDefaultRoleForUser")?>'+'/'+id+'/'+'<?=$id_m_user?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                if(rs.code == 0){
                    successtoast('Berhasil menjadikan Role sebagai Default')
                    loadListRole('<?=$id_m_user?>')
                } else {
                    errortoast('Terjadi Kesalahan')
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    }
</script>