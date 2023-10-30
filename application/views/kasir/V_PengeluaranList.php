<table class="table table-hover table-striped" id="result_table_pengeluaran">
    <thead>
        <th class="text-center">No</th>
        <th class="text-center">Tanggal Transaksi</th>
        <th class="text-left">Nama Transaksi</th>
        <th class="text-left">Nominal</th>
        <th class="text-center">Pilihan</th>
    </thead>
    <tbody>
        <?php if($result){ $no = 1; foreach($result as $rs){  ?>
            <tr>
                <td class="text-center"><?=$no++;?></td>
                <td class="text-center"><?=formatDateNamaBulan($rs['tanggal_transaksi'], 1)?></td>
                <td class="text-left"><?=($rs['nama_transaksi'])?></td>
                <td class="text-left"><?=formatCurrency($rs['nominal'])?></td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm" onclick="hapusTransaksi('<?=$rs['id']?>')"><i class="fa fa-trash"></i> Hapus</button>
                </td>
            </tr>
        <?php } } ?>
    </tbody>
</table>
<script>
    $(function(){
        $('#result_table_pengeluaran').dataTable()
    })

    function hapusTransaksi(id){
        if(confirm('Apakah Anda yakin?')){
            $.ajax({
                url: '<?=base_url("kasir/C_Kasir/deleteTransaksiPengeluaran/")?>'+id,
                method: 'post',
                data: null,
                success: function(data){
                    successtoast('Data Berhasil Dihapus')
                    $('#form_search_list_transaksi').submit()
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    }
</script>