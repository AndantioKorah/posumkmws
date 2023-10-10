<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Laporan Penjualan</h3>
    </div>
    <div class="card-body">
        <form id="form_search_laporan_penjualan">
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
        $('#range_tanggal').daterangepicker()
        $('#form_search_laporan_penjualan').submit()
    })

    $('#range_tanggal').on('change', function(){
        $('#form_search_laporan_penjualan').submit()
    })

    $('#form_search_laporan_penjualan').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("laporan/C_Laporan/searchLaporanPenjualan")?>',
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