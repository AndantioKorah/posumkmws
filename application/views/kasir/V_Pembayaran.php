<style>
    .lbl_pembayaran{
        color: grey;
        font-style: italic;
        font-weight: 600;
        font-size: .8rem;
    }

    .input_pembayaran{
        height: 2rem;
        font-size: 1rem;
        font-weight: bold;
        color: black;
        border: 0px;
        border-bottom: 1px solid var(--primary);
        text-align: right;
        border-radius: 0px !important;
    }

    .input_pembayaran:hover{
        cursor: pointer;
    }
</style>
<form id="form_pembayaran">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Nama</label>
                </div>
                <div class="col-lg-8">
                    <input class="input_pembayaran form-control form-control-sm" value="<?=$transaksi['nama']?>" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Cara Bayar</label>
                </div>
                <div class="col-lg-8">
                    <select class="input_pembayaran form-control form-control-sm select2-navy" style="width: 100%"
                    id="id_m_jenis_pembayaran" data-dropdown-css-class="select2-navy" name="id_m_jenis_pembayaran">
                        <?php if($jenis_pembayaran){
                            foreach($jenis_pembayaran as $jp){
                            ?>
                            <option <?=$jp['id'] == 1 ? 'selected' : ''?> value="<?=$jp['id']?>">
                                <?=$jp['nama_jenis_pembayaran']?>
                            </option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Total Bayar</label>
                </div>
                <div class="col-lg-8">
                    <input class="input_pembayaran form-control form-control-sm format_currency_this" value="<?=formatCurrencyWithoutRp($transaksi['total_harga'])?>" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Diskon</label>
                </div>
                <div class="col-lg-8">
                    <input class="input_pembayaran form-control form-control-sm format_currency_this" value="0" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Catatan</label>
                </div>
                <div class="col-lg-8">
                    <input class="input_pembayaran form-control form-control-sm" value="" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Kembalian</label>
                </div>
                <div class="col-lg-8">
                    <input class="input_pembayaran form-control form-control-sm" value="0" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 text-right">
                    <button type="submit" class="btn btn-block btn-success"><strong>Submit Pembayaran</strong></button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('.format_currency_this').on('keypress', function(event){
        if(event.charCode >= 48 && event.charCode <= 57){
            return true;
        } else {
            return false;
        }
    })

    function formatRupiahPembayaran(angka, prefix = "Rp ") {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? rupiah : "";
    }

    $('.format_currency_this').on('keyup', function(){
      $(this).val(formatRupiahPembayaran($(this).val()))
    })
</script>