<style>
    .tr_menu_merchant:hover{
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card card-default p-3">
            <table class="table table-hover table-striped" id="table_stock_opname">
                <thead>
                    <th class="text-center">No</th>
                    <th class="text-left">Nama Menu</th>
                    <th class="text-center">Stock Awal</th>
                    <th class="text-center">Stock Akhir</th>
                </thead>
                <tbody>
                    <?php if($result['menu']){ $no = 1; foreach($result['menu'] as $m){ 
                        // dd(json_encode($result['menu'])); 
                        ?>
                        <tr onclick="openDetailStockOpname('<?=($m['id'])?>')" href="#modal_stock_opname" data-toggle="modal" class="tr_menu_merchant">
                            <td class="text-center"><?=$no++;?></td>
                            <td class="text-left"><?=$m['nama_menu_merchant']?></td>
                            <td class="text-center"><?=formatCurrencyWithoutRp($m['transaksi']['stock_awal'])?></td>
                            <td class="text-center"><?=formatCurrencyWithoutRp($m['transaksi']['total'])?></td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_stock_opname" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div id="modal-dialog" class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">Detail Stock Opname <span class="nama_detail_stock_opname"></span></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div id="modal_stock_opname_content">
          </div>
      </div>
  </div>
</div>
<script>
    $(function(){
        $('#table_stock_opname').dataTable()
    })

    function openDetailStockOpname(id){
        $('#modal_stock_opname_content').html('')
        $('#modal_stock_opname_content').append(divLoaderNavy)
        $('#modal_stock_opname_content').load('<?=base_url('laporan/C_Laporan/openDetailStockOpname/')?>'+id, function(){
            $('#loader').hide()
        })
    }
</script>