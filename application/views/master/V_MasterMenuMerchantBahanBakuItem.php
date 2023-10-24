<div class="p-3">
    <table class="table table-striped table-hover" id="table_bahan_baku_menu_merchant">
        <thead>
            <th class="text-center">No</th>
            <th class="text-left">Bahan Baku</th>
            <th class="text-center">Satuan</th>
            <th class="text-center">Takaran</th>
            <th class="text-center">Pilihan</th>
        </thead>
        <tbody>
            <?php if($result){$no = 1; foreach($result as $rs){ ?>
                <tr>
                    <td class="text-center"><?=$no++;?></td>
                    <td class="text-left"><?=$rs['nama_bahan_baku']?></td>
                    <td class="text-center"><?=$rs['satuan']?></td>
                    <td class="text-center"><?=formatCurrencyWithoutRp($rs['takaran'])?></td>
                    <td class="text-center">
                        <button onclick="deleteBahanBakuMenuMerchant('<?=$rs['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>
<script>
    $(function(){
        $('#table_bahan_baku_menu_merchant').dataTable()
    })

    function deleteBahanBakuMenuMerchant(id){
        if(confirm('Apakah Anda yakin ingin menghapus data?')){
            $.ajax({
                url: '<?=base_url("master/C_Master/deleteBahanBakuMenuMerchant/")?>'+id,
                method: 'post',
                data: null,
                success: function(res){
                    loadBahanBakuMenuMerchant()
                    successtoast('Data berhasil dihapus')
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    }
</script>