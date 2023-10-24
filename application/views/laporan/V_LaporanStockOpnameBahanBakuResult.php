<style>
    .tr_bahan_baku:hover{
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-lg-12 table-responsive">
        <table class="table table-hover table-striped" id="table_stock_opname_bahan_baku">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Nama Bahan Baku</th>
                <th class="text-left">Satuan</th>
                <th class="text-center">Stock Awal</th>
                <th class="text-center">Stock Akhir</th>
            </thead>
            <tbody>
                <?php if($result && $result['bahan_baku']){ $no = 1; foreach($result['bahan_baku'] as $m){ 
                    // dd(json_encode($m)); 
                    ?>
                    <tr onclick="openDetailStockOpnameBahanBaku('<?=($m['id'])?>')" href="#modal_stock_opname_bahan_baku" data-toggle="modal" class="tr_bahan_baku">
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left"><?=$m['nama_bahan_baku']?></td>
                        <td class="text-left"><?=$m['satuan']?></td>
                        <td class="text-center"><?=formatCurrencyWithoutRp($m['stock_awal'])?></td>
                        <td class="text-center"><?=formatCurrencyWithoutRp($m['stock_akhir'])?></td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modal_stock_opname_bahan_baku" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div id="modal-dialog" class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">Detail Stock Opname <span class="nama_detail_stock_opname_bahan_baku"></span></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div id="modal_stock_opname_bahan_baku_content">
          </div>
      </div>
  </div>
</div>
<script>
    $(function(){
        $('#table_stock_opname_bahan_baku').dataTable()
    })

    function openDetailStockOpnameBahanBaku(id){
        $('#modal_stock_opname_bahan_baku_content').html('')
        $('#modal_stock_opname_bahan_baku_content').append(divLoaderNavy)
        $('#modal_stock_opname_bahan_baku_content').load('<?=base_url('laporan/C_Laporan/openDetailStockOpnameBahanBaku/')?>'+id, function(){
            $('#loader').hide()
        })
    }
</script>