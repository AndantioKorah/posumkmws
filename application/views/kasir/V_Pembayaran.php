<style>
    .lbl_pembayaran{
        color: grey;
        font-style: italic;
        font-weight: 600;
        font-size: .6rem;
    }

    .input_pembayaran{
        height: 2rem;
        font-size: ;
        font-weight: bold;
        color: black;
        border: 0px;
        border-bottom: 1px solid var(--primary);
        text-align: right;
        border-radius: 0px !important;
        padding: 0;
    }

    .input_pembayaran:hover{
        cursor: pointer;
    }

    #input_kembalian{
        
    }

    .val_pembayaran_exists{
        color: black;
        font-size: 1.3rem;
        font-weight: bold;
        border-bottom: 1px solid black;
        padding-bottom: 5px;
    }

    @media  (min-width: 992px) {
        #div_bottom_button_pembayaran{
            bottom: 0;
            position: absolute;
            padding-bottom: 2.5rem;
        }
    }
</style>
<form id="form_pembayaran">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Nama</label>
                </div>
                <div class="col-lg-8 <?=$pembayaran ? 'text-right' : ''?>">
                    <?php if($pembayaran){ ?>
                        <h5 class="val_pembayaran_exists"><?=$pembayaran['nama_pembayar']?></h5>
                    <?php } else { ?>
                        <input name="nama_pembayar" autocomplete="off" class="input_pembayaran form-control form-control-sm" value="<?=$transaksi['nama']?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Cara Bayar</label>
                </div>
                <div class="col-lg-8 <?=$pembayaran ? 'text-right' : ''?>">
                    <?php if($pembayaran){ ?>
                        <h5 class="val_pembayaran_exists"><?=$pembayaran['nama_jenis_pembayaran']?></h5>
                    <?php } else { ?>
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
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Catatan</label>
                </div>
                <div class="col-lg-8 <?=$pembayaran ? 'text-right' : ''?>">
                    <?php if($pembayaran){ ?>
                        <h5 class="val_pembayaran_exists"><?=$pembayaran['keterangan'] != "" && $pembayaran['keterangan'] != null ? $pembayaran['keterangan'] : '-'?></h5>
                    <?php } else { ?>
                        <input name="keterangan" autocomplete="off" class="input_pembayaran form-control form-control-sm" value="" />
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Total Bayar</label>
                </div>
                <div class="col-lg-8 <?=$pembayaran ? 'text-right' : ''?>">
                    <?php if($pembayaran){ ?>
                        <h5 class="val_pembayaran_exists"><?=formatCurrency($pembayaran['total_pembayaran'])?></h5>
                    <?php } else { ?>
                        <input name="total_pembayaran" autocomplete="off" id="input_total_bayar" class="input_pembayaran form-control form-control-sm format_currency_this" 
                        value="<?=formatCurrencyWithoutRp($transaksi['total_harga'])?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Diskon</label>
                </div>
                <div class="col-lg-8 <?=$pembayaran ? 'text-right' : ''?>">
                    <?php if($pembayaran){ ?>
                        <h5 class="val_pembayaran_exists"><?=formatCurrency($pembayaran['diskon_nominal'])?></h5>
                    <?php } else { ?>
                        <input name="diskon" autocomplete="off" id="input_diskon" class="input_pembayaran form-control form-control-sm format_currency_this" value="0" />
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4">
                    <label class="lbl_pembayaran">Kembalian</label>
                </div>
                <div class="col-lg-8 text-right">
                    <h5 name="kembalian" class="val_pembayaran_exists" id="input_kembalian"><?=$pembayaran ? formatCurrency($pembayaran['kembalian']) : "0"?></h5>
                </div>
            </div>
        </div>
        <div id="div_bottom_button_pembayaran" class="col-lg-12 text-right mt-2">
            <div class="row">
                <div class="col-lg-6">
                    <button onclick="printBill()" type="button" class="btn btn-block btn-info"><strong><i class="fa fa-print"></i> Cetak Bill</strong></button>
                </div>
                <div class="col-lg-6">
                <?php if (!$pembayaran) { ?>
                    <button id="btn_pembayaran" type="submit" class="btn btn-block btn-success"><strong>Submit Pembayaran</strong></button>
                    <button id="btn_loading_pembayaran" style="display: none;" type="button" disabled
                    class="btn btn-block btn-success"><strong><i class="fa fa-spin fa-spinner"></i> Mohon Menunggu...</strong></button>
                <?php } ?>
                <div id="div_pembayaran_exists" style="display: <?=$pembayaran ? 'block' : 'none'?>;">
                    <div class="row">
                        <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                            <button onclick="printBill()" type="button" class="btn btn-block btn-info"><strong><i class="fa fa-print"></i> Cetak Bill</strong></button>
                        </div> -->
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <button href="#modal_need_password" data-toggle="modal" id="btn_batal_pembayaran" type="button" class="btn btn-block btn-danger">
                                <strong><i class="fa fa-trash"></i> Batal Bayar</strong>
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="print_div" style="display:none;"></div>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>

<div class="modal fade" id="modal_need_password" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div id="modal-dialog" class="modal-dialog modal-md">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">MASUKKAN PASSWORD</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <form id="form_need_password">
              <div class="col-lg-12">
                <label>Massukkan Password Admin</label>
                <input type="password" class="form-control" id="password_admin" name="password_admin" />
              </div>
              <div class="col-lg-12 text-right mt-2">
                <button type="submit" class="btn btn-navy btn-sm">Submit</button>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>

<script>
    $('.format_currency_this').on('keypress', function(event){
        if(event.charCode >= 48 && event.charCode <= 57){
            return true;
        } else {
            return false;
        }
    })

    function printBill() {
        // window.open('<?=base_url('kasir/C_Kasir/printBill/'.$transaksi['id'])?>', '_blank');

        $("#print_div").load('<?=base_url('kasir/C_Kasir/printBill/'.$transaksi['id'])?>', function(){
                printSpace('print_div');
            }
        );
    }

    function printSpace(elementId) {
        var isi = document.getElementById(elementId).innerHTML;
        window.frames["print_frame"].document.title = document.title;
        window.frames["print_frame"].document.body.innerHTML = isi;
        setTimeout(function() {
            window.frames["print_frame"].window.focus();
            window.frames["print_frame"].window.print();
        }, 250);
    }

    $('#form_need_password').on('submit', function(e){
        e.preventDefault(e)
        $.ajax({
            url: '<?=base_url("user/C_User/validatePasswordAdmin")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                $(':password').val('')
                if(rs.code == 1){
                    errortoast(rs.message)
                } else {
                    $('#modal_need_password').modal('hide')
                    deletePembayaran()
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

    function deletePembayaran(){
        if(confirm('Apakah Anda yakin?')){
            $.ajax({
                url: '<?=base_url("kasir/C_Kasir/deletePembayaran/".$transaksi['id'])?>',
                method: 'post',
                data: $(this).serialize(),
                success: function(data){
                    successtoast('Hapus Pembayaran Berhasil')
                    $('#btn_pembayaran').show()
                    $('#div_pembayaran_exists').hide()
                    detailTransaksi('<?=$transaksi['id']?>')
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                    $('#btn_loading_pembayaran').hide()
                }
            })
        }
    }

    // $('#btn_batal_pembayaran').on('click', function(){
        
    // })

    $('#form_pembayaran').on('submit', function(e){
        e.preventDefault()
        var kembalian = countTotal()
        var total_harga = clearString($('.val_detail_total_harga').html())
        if(kembalian < 0 || isNaN(kembalian)){
            errortoast('Total Bayar kurang dari Total Harga')
            return
        }
        if(total_harga == "0"){
            errortoast('Belum ada Menu yang dipilih')
            return
        }

        $('#btn_pembayaran').hide()
        $('#btn_loading_pembayaran').show()

        $.ajax({
            url: '<?=base_url("kasir/C_Kasir/createPembayaran/".$transaksi['id'])?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                successtoast('Pembayaran Berhasil')
                $('#btn_loading_pembayaran').hide()
                $('#div_pembayaran_exists').show()
                detailTransaksi('<?=$transaksi['id']?>')
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
                $('#btn_loading_pembayaran').hide()
            }
        })
    })

    function clearString(string){
        return string.trim().replace(/^\D+/g, '').split('.').join("");
    }

    $('#input_total_bayar').on('keyup', function(){
        countTotal()
    })

    $('#input_diskon').on('keyup', function(){
        countTotal()
    })

    function countTotal(){
        var total_harga = clearString($('.val_detail_total_harga').html())
        var diskon = clearString($('#input_diskon').val())
        if(diskon == ''){
            diskon = 0;
        }
        var total_bayar = clearString($('#input_total_bayar').val())
        var total_bayar_and_diskon = parseInt(total_bayar) + parseInt(diskon);
        if(isNaN(total_bayar_and_diskon)){
            total_bayar_and_diskon = 0;
        } 
        var kembalian = total_bayar_and_diskon - parseInt(total_harga)
        var real_kembalian = kembalian;
        if(kembalian < 0 || isNaN(kembalian)){
            kembalian = 0
        }
        $('#input_kembalian').html(formatRupiahPembayaran(String(kembalian)))
        return real_kembalian;
    }

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