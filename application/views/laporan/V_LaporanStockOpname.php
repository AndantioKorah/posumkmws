<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Stock Opname</h3>
    </div>
    <div class="card-body">
        <form id="form_search_stock_opname">
            <div class="row">
                <div class="col-lg-8">
                    <label>Pilih Tanggal</label>
                    <input name="range_tanggal" class="form-control" id="range_tanggal" />
                </div>
                <div class="col-lg-4">
                    <button type="submit" style="margin-top: 30px;" class="btn btn-block btn-navy"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="div_result">
</div>
<script>
    $(function(){
        $('#range_tanggal').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        })
        $('#form_search_stock_opname').submit()
    })

    $('#range_tanggal').on('change', function(){
        $('#form_search_stock_opname').submit()
    })

    $('#form_search_stock_opname').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("laporan/C_Laporan/searchLaporanStockOpname")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                $('.div_result').html('')
                $('.div_result').append(divLoaderNavy)
                $('.div_result').html(data)
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>