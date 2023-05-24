<?php if($list_jenis_pesan){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" id="table_jenis_pesan">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Nama Jenis Pesan</th>
                <th class="text-center">Pilihan</th>
            </thead>
            <tbody>
                <?php $no=1; foreach($list_jenis_pesan as $lp){ ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left"><?=$lp['jenis_pesan']?></td>
                        <td class="text-center">
                            <button onclick="deleteJenisPesan('<?=$lp['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $(function(){
            $('#table_jenis_pesan').DataTable({
                responsive: false
            });
        })

        function deleteJenisPesan(id){
            if(confirm('Apakah Anda yakin ingin menghapus data?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteJenisPesan/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        successtoast('Data sudah terhapus')
                        loadJenisPesan()
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