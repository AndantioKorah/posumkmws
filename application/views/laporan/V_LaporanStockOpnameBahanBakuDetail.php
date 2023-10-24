<style>
    .tr_menu_merchant:hover{
        cursor: pointer;
    }
</style>
<div class="row p-3">
    <div class="col-lg-12">
        <h5>Nama Item: <strong><?=$result['nama_bahan_baku']?></strong></h5>
        <h5>Stock Akhir: <strong><?=formatCurrencyWithoutRp($result['stock_akhir'])?></strong></h5>
    </div>
    <div class="col-lg-12 table-responsive">
        <table class="table table-hover table-striped" id="table_stock_opname_detail">
            <thead>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Masuk</th>
                <th class="text-center">Keluar</th>
            </thead>
            <tbody>
                <?php if($result['transaksi']){ $no = 1; foreach($result['transaksi'] as $rs){ 
                    ?>
                    <tr>
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-center"><?=formatDateNamaBulan($rs['tanggal'])?></td>
                        <td class="text-center"><?=isset($rs['masuk']) ? formatCurrencyWithoutRp($rs['masuk']) : 0;?></td>
                        <td class="text-center"><?=isset($rs['keluar']) ? formatCurrencyWithoutRp($rs['keluar']) : 0;?></td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('.nama_detail_stock_opname').html('<?=$result['nama_bahan_baku']?>')
    })
</script>