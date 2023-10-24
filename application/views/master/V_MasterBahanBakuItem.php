<?php if($result){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" id="table_bahan_baku">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Nama Bahan Baku</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Pilihan</th>
            </thead>
            <tbody>
                <?php $no=1; foreach($result as $lp){ ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left"><?=($lp['nama_bahan_baku'])?></td>
                        <td class="text-center"><?=$lp['satuan']?></td>
                        <td class="text-center">
                            <button href="#modal_edit_bahan_baku" onclick="openModalEdit('<?=$lp['id']?>')" data-toggle="modal"
                            class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</button>
                            <button onclick="deleteBahanBaku('<?=$lp['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $(function(){
            $('#table_bahan_baku').DataTable({
                responsive: false
            });
        })

        function openModalEdit(id){
            $('#modal_edit_bahan_baku_content').html('')
            $('#modal_edit_bahan_baku_content').append(divLoaderNavy)
            $('#modal_edit_bahan_baku_content').load('<?=base_url('master/C_Master/openModalEditBahanBaku/')?>'+id, function(){
                $('#loader').hide()
            })
        }

        function deleteBahanBaku(id){
            if(confirm('Apakah Anda yakin ingin menghapus data?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteBahanBaku/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        successtoast('Data sudah terhapus')
                        loadAllBahanBaku()
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