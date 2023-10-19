<table class="table table-hover table-striped" id="table_stock">
    <thead>
        <th class="text-center">No</th>
        <th class="text-center">Tanggal Masuk</th>
        <th class="text-center">Jumlah Barang</th>
        <th class="text-center">Pilihan</th>
    </thead>
    <tbody>
        <?php $stock_saat_ini = 0; if($result){ $no = 1; foreach($result as $rs){ $stock_saat_ini += $rs['jumlah_barang'] ?>
            <tr>
                <td class="text-center"><?=$no++;?></td>
                <td class="text-center"><?=formatDateNamaBulan($rs['tanggal'], 1)?></td>
                <td class="text-center"><?=formatCurrencyWithoutRp($rs['jumlah_barang'])?></td>
                <td class="text-center">
                    <button onclick="hapusStock('<?=$rs['id']?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                </td>
            </tr>
        <?php } } ?>
    </tbody>
</table>
<script>
    $(function(){
        $('.jumlah_stock').html('Stock Masuk: '+'<?=formatCurrencyWithoutRp($stock_saat_ini)?>')
        $('#table_stock').dataTable()
    })

    function hapusStock(id){
        if(confirm('Apakah Anda yaking ingin menghapus data?')){
            $.ajax({
                url: '<?=base_url("master/C_Master/deleteStockMenuMerchant/")?>'+id,
                method: 'post',
                data: null,
                success: function(){
                    loadStock()
                    successtoast('Data sudah terhapus')
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    }
</script>