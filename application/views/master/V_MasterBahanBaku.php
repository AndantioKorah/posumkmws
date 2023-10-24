    <div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">TAMBAH BAHAN BAKU</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_tambah_bahan_baku">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nama Bahan Baku</label>
                        <input class="form-control" autocomplete="off" name="nama_bahan_baku" id="nama_bahan_baku" required/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="bmd-label-floating">Satuan</label>
                        <input class="form-control" autocomplete="off" name="satuan" id="satuan" required/>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <label class="bmd-label-floating" style="color: white;">..</label>
                    <button class="btn btn-sm btn-navy" type="submit"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_edit_bahan_baku" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div id="modal-dialog" class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title">EDIT BAHAN BAKU</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div id="modal_edit_bahan_baku_content" class="p-3">
          </div>
      </div>
  </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">LIST BAHAN BAKU</h3>
    </div>
    <div class="card-body">
        <div id="list_bahan_baku" class="row">
        </div>
    </div>
</div>


<script>
    $(function(){
        loadAllBahanBaku()

        $(function(){
            <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
                errortoast('<?=$this->session->flashdata('message')?>')
            <?php } ?>
            <?php if($this->session->flashdata('message') == '0'){ ?>
                successtoast('UPDATE BERHASIL')
            <?php } ?>
        })
    })

    function loadAllBahanBaku(){
        $('#list_bahan_baku').html('')
        $('#list_bahan_baku').append(divLoaderNavy)
        $('#list_bahan_baku').load('<?=base_url("master/C_Master/loadAllBahanBaku")?>', function(){
            $('#loader').hide()
        })
    }

    $('#form_tambah_bahan_baku').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=base_url("master/C_Master/createMasterBahanBaku")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(){
                successtoast('Data berhasil ditambahkan')
                $('#nama_bahan_baku').val('')
                $('#satuan').val('')
                loadAllBahanBaku()
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
    })

</script>