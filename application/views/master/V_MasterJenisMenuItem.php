<?php if($result){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" id="table_merchant">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Nama Merchant</th>
                <th class="text-left">Merchant</th>
                <th class="text-left">Keterangan</th>
                <th class="text-center">Pilihan</th>
            </thead>
            <tbody>
                <?php $no=1; foreach($result as $lp){ ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left"><?=$lp['nama_jenis_menu']?></td>
                        <td class="text-left"><?=$lp['nama_merchant']?></td>
                        <td class="text-left"><?=$lp['deskripsi']?></td>
                        <td class="text-center">
                            <button onclick="deleteJenisMenu('<?=$lp['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
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

        function deleteJenisMenu(id){
            if(confirm('Apakah Anda yakin ingin menghapus data?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteJenisMenu/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        successtoast('Data sudah terhapus')
                        loadJenisMenuByMerchant()
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