<?php if($list_jenis_pesan){ ?>
    <div class="col-12">
        <table class="table table-hover table-striped" id="table_merchant">
            <thead>
                <th class="text-center">No</th>
                <th class="text-center">Logo</th>
                <th class="text-left">Nama Merchant</th>
                <th class="text-left">Expire Date</th>
                <th class="text-left">Alamat</th>
                <th class="text-center">Pilihan</th>
            </thead>
            <tbody>
                <?php $no=1; foreach($list_jenis_pesan as $lp){ ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-center">
                            <?php if($lp['logo']){ ?>
                                <a href="<?=base_url('assets/logo_merchant/'.$lp['logo'])?>" target="_blank">
                                    <img style="width: 50px; height: 50px;" src="<?=base_url('assets/logo_merchant/'.$lp['logo'])?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td class="text-left"><?=$lp['nama_merchant']?></td>
                        <td class="text-left"><?=formatDateNamaBulan($lp['expire_date'])?></td>
                        <td class="text-left"><?=$lp['alamat']?></td>
                        <td class="text-center">
                            <button onclick="openEdit('<?=$lp['id']?>')" href="#edit_data_merchant"
                                data-toggle="modal" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteMerchant('<?=$lp['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
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

        function openEdit(id){
            $('#edit_data_merchant_content').html('')
            $('#edit_data_merchant_content').append(divLoaderNavy)
            $('#edit_data_merchant_content').load('<?=base_url("master/C_Master/openModalEditMerchant/")?>'+id, function(){
                $('#loader').hide()
            })
        }

        function deleteMerchant(id){
            if(confirm('Apakah Anda yakin ingin menghapus data?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteMerchant/")?>'+id,
                    method: 'post',
                    data: null,
                    success: function(){
                        successtoast('Data sudah terhapus')
                        loadAllMerchant()
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