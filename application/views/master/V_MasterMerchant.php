    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH MERCHANT</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_merchant" action="<?=base_url("master/C_Master/createMasterMerchant")?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Merchant</label>
                        <input class="form-control" autocomplete="off" name="nama_merchant" id="nama_merchant" required/>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Alamat</label>
                        <input class="form-control" autocomplete="off" name="alamat" id="alamat"/>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Expire Date</label>
                        <input type="date" value="<?=date('Y-m-d')?>" class="form-control" autocomplete="off" name="expire_date" id="expire_date"/>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="bmd-label-floating">Logo</label>
                        <input class="form-control" accept="image/x-png,image/gif,image/jpeg" type="file" name="logo_merchant" id="logo_merchant">
                    </div>
                </div>
                <div class="col-3"></div>
                <div class="col-9 text-right">
                    <label class="bmd-label-floating" style="color: white;">..</label>
                    <button class="btn btn-sm btn-navy" type="submit"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_data_merchant" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div id="modal-dialog" class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">EDIT DATA MERCHANT</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div id="edit_data_merchant_content">
          </div>
      </div>
  </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">LIST MERCHANT</h3>
    </div>
    <div class="card-body">
        <div id="list_jenis_pesan" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        // $('#expire_date').datetimepicker({
        //     format: 'yyyy-mm-dd',
        //     autoclose: true,
        //     todayHighlight: true,
        //     todayBtn: true,
        // })

        loadAllMerchant()

        $(function(){
            <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
                errortoast('<?=$this->session->flashdata('message')?>')
            <?php } ?>
            <?php if($this->session->flashdata('message') == '0'){ ?>
                successtoast('UPDATE BERHASIL')
            <?php } ?>
        })
    })

    function loadAllMerchant(){
        $('#list_jenis_pesan').html('')
        $('#list_jenis_pesan').append(divLoaderNavy)
        $('#list_jenis_pesan').load('<?=base_url("master/C_Master/loadAllMerchant")?>', function(){
            $('#loader').hide()
        })
    }

    // $('#form_tambah_merchant').on('submit', function(e){
    //     e.preventDefault();
    //     $.ajax({
    //         url: '<?=base_url("master/C_Master/createMasterMerchant")?>',
    //         method: 'post',
    //         data: $(this).serialize(),
    //         success: function(){
    //             successtoast('Data berhasil ditambahkan')
    //             loadAllMerchant()
    //         }, error: function(e){
    //             errortoast('Terjadi Kesalahan')
    //         }
    //     })
    // })

</script>