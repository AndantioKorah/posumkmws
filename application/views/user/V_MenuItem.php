<?php if($result){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" style="width:100%;" id="data_table">
            <thead>
                <th class="text-center">No</th>
                <th>Nama Menu</th>
                <th>URL</th>
                <th>Parent</th>
                <th>Icon</th>
                <th>Keterangan</th>
                <th>Pilihan</th>
            </thead>
            <tbody>
                <?php $no = 1; foreach($result as $rs){ ?>
                    <tr>
                        <td align="center"><?=$no++;?></td>
                        <td><?=$rs['nama_menu'];?></td>
                        <td><?=$rs['url'];?></td>
                        <td><?=$rs['nama_menu_parent'];?></td>
                        <td><i class="<?=$rs['icon'] ? $rs['icon'] : 'far fa-circle'?>"></i> <?=$rs['icon'] ? $rs['icon'] : 'default-icon';?></td>
                        <td><?=$rs['keterangan'];?></td>
                        <td>
                            <button type="button" data-toggle="modal" href="#add_role_modal" onclick="openAddRoleModal('<?=$rs['id']?>')" class="btn btn-sm btn-info"
                            data-tooltip="tooltip" title="Tambah Role"><i class="fa fa-user"></i> Role</button>

                            <button type="button" onclick="hapus('<?=$rs['id']?>')" class="btn btn-sm btn-danger"
                            data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="add_role_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div id="modal-dialog" class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="add_role_modal_content">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            let table = $('#data_table').DataTable({
                responsive: false
            });
            $('[data-tooltip="tooltip"]').tooltip();
        })

        function openAddRoleModal(id){
            $('#add_role_modal_content').html('')
            $('#add_role_modal_content').append(divLoaderNavy)
            $('#add_role_modal_content').load('<?=base_url("user/C_User/addRoleForMenu")?>'+'/'+id, function(){

            })
        }

        function hapus(id){
            if(confirm('Apakah Anda yakin ingin menghapus data ini ?')){
                $.ajax({
                    url: '<?=base_url("user/C_User/deleteMenu/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        window.location=""
                        // loadMenu()
                    }, error: function(e){
                        alert('Terjadi Kesalahan')
                    }
                })   
            }
        }
    </script>
<?php } else { ?>
    <div class="col-12 text-center"><h6>Belum ada Data Menu</h6></div>
<?php } ?>