<?php if($result){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" id="table_merchant">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Jenis / Kategori</th>
                <th class="text-left">Nama Menu</th>
                <th class="text-left">Harga</th>
                <th class="text-left">Stock</th>
                <th class="text-left">Keterangan</th>
                <th class="text-center">Pilihan</th>
            </thead>
            <tbody>
                <?php $no=1; foreach($result as $lp){ ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left"><?=$lp['nama_jenis_menu'].' / '.$lp['nama_kategori_menu']?></td>
                        <td class="text-left"><?=$lp['nama_menu_merchant']?></td>
                        <td class="text-left">
                            <span class="harga_item_<?=$lp['id']?>"><?=formatCurrency($lp['harga'])?></span>
                        </td>
                        <td class="text-center">
                            <input style="width: 1.5rem; height: 1.5rem; cursor: pointer;" class="form-check-input"
                            type="checkbox" onclick="checkBoxClick('<?=$lp['id']?>')" id="checkBoxStock_<?=$lp['id']?>" <?=$lp['stock'] == 1 ? 'checked' : ''?>>
                        </td>
                        <td class="text-left"><?=$lp['deskripsi']?></td>
                        <td class="text-center">
                            <button href="#modal_edit_menu" onclick="openModalEdit('<?=$lp['id']?>')" data-toggle="modal"
                            class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</button>
                            <button onclick="deleteMenuMerchant('<?=$lp['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $(function(){
            $('#table_merchant').DataTable({
                responsive: false
            });
        })

        function checkBoxClick(id){
            let status = 0;
            if($('#checkBoxStock_'+id).prop("checked")){
                status = 1;
            }
            $.ajax({
                url: '<?=base_url("master/C_Master/changeStatusStockMenuMerchant/")?>'+id+'/'+status,
                method: 'post',
                data: null,
                success: function(){
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }

        function openModalEdit(id){
            $('#modal_edit_menu_content').html('')
            $('#modal_edit_menu_content').append(divLoaderNavy)
            $('#modal_edit_menu_content').load('<?=base_url('master/C_Master/openModalEditMenuMerchant/')?>'+id, function(){
                $('#loader').hide()
            })
        }

        function deleteMenuMerchant(id){
            if(confirm('Apakah Anda yakin ingin menghapus data?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteMenuMerchant/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        loadAllMenuMerchant()
                        successtoast('Data sudah terhapus')
                    }, error: function(e){
                        errortoast('Terjadi Kesalahan')
                    }
                })
            }
        }
    </script>
<?php } else { ?>
    <div class="row">
        <div class="col-12">
            <h5 style="text-center">DATA TIDAK DITEMUKAN <i class="fa fa-exclamation"></i></h5>
        </div>
    </div>
<?php } ?>