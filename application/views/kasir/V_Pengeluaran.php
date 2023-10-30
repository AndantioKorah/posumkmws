<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h5 class="card-title">TRANSAKSI PENGELUARAN</h5>
            </div>
            <div class="card-body">
                <form id="form_pengeluaran">
                    <div class="row">
                        <div class="col-lg-4">
                            <label>Tanggal Transaksi</label>
                            <input readonly value="<?=date('Y-m-d H:i:s')?>" class="form-control" name="tanggal_transaksi" id="tanggal_transaksi" />
                        </div>
                        <div class="col-lg-4">
                            <label>Nama Transaksi</label>
                            <input class="form-control" name="nama_transaksi" id="input_nama_transaksi" />
                        </div>
                        <div class="col-lg-4">
                            <label>Nominal</label>
                            <input type="number" class="form-control format_currency_this" name="nominal" id="input_nominal" />
                        </div>
                        <div class="col-lg-12 mt-2 text-right">
                            <button type="submit" class="btn btn-sm btn-navy"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h5 class="card-title">List Transaksi Pengeluaran</h5>
                    </div>
                    <div class="card-body">
                        <form id="form_search_list_transaksi">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Pilih Tanggal</label>
                                    <input name="range_tanggal" class="form-control" id="range_tanggal" />
                                </div>
                                <div class="col-lg-12 mt-2 table-responsive" id="div_list_pengeluaran"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#tanggal_transaksi').datetimepicker()
        $('#range_tanggal').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        })
        $('#form_search_list_transaksi').submit()
    })

    $('#range_tanggal').on('change', function(){
        $('#form_search_list_transaksi').submit()
    })

    $('#form_search_list_transaksi').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/loadListPengeluaran/")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                $('#div_list_pengeluaran').html('')
                $('#div_list_pengeluaran').append(divLoaderNavy)
                $('#div_list_pengeluaran').html(data)
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    $('#form_pengeluaran').on('submit', function(e){
        e.preventDefault()
        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/createTransaksiPengeluaran/")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                $('#input_nama_transaksi').val('')
                $('#input_nominal').val('')
                $('#form_search_list_transaksi').submit()
                successtoast('Data Berhasil Disimpan')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })
</script>