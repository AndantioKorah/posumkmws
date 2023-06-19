<?php if($result){ ?>
    <form id="form_edit_merchant" method="POST" enctype="multipart/form-data">
        <div class="row p-3">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Nama Merchant</label>
                        <input class="form-control" name="nama_merchant" value="<?=$result['nama_merchant']?>" />
                    </div>
                    <div class="col-lg-12">
                        <label>Alamat</label>
                        <input class="form-control" name="alamat" value="<?=$result['alamat']?>" />
                    </div>
                    <div class="col-lg-12 mt-2">
                        <label>Expire Date</label>
                        <input type="date" class="form-control" name="expire_date" value="<?=formatDateOnlyForEdit($result['expire_date'])?>" />
                    </div>
                    <div class="col-lg-12 mt-2">
                        <label style="line-height: .1rem;">Logo</label>
                        <label style="font-weight: normal; font-size: 12px;">(Biarkan kosong jika tidak ingin mengganti logo)</label>
                        <input type="file" class="form-control" name="logo_merchant"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12 text-center mt-2">
                        <center>
                            <?php if($result['logo']){ ?>
                                <image style="width: 250px; height: 250px;" src="<?=base_url('assets/logo_merchant/'.$result['logo'])?>" /><br>
                                <button onclick="hapusLogo('<?=$result['id']?>')" class="btn-sm btn-danger mt-2"><i class="fa fa-trash"></i> Hapus</button>
                            <?php } else { ?>
                                <h5>Tidak ada logo<h5>
                            <?php } ?>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-3">
                <button class="btn btn-block btn-navy" type="submit">Simpan</button>
            </div>
        </div>
    </form>
    <script>
        function hapusLogo(id){
            if(confirm('Apakah anda yakin ingin menghapus logo?')){
                $.ajax({
                    url: '<?=base_url("master/C_Master/deleteLogo/").$result['id']?>',
                    method: 'post',
                    data: null,
                    success: function(){
                        $('#edit_data_merchant').modal('hide')
                        successtoast('Logo sudah dihapus')
                        loadAllMerchant()
                    }, error: function(e){
                        errortoast('Terjadi Kesalahan')
                    }
                })
            }
        }

        $('#form_edit_merchant').on('submit', function(e){
            e.preventDefault();
            var formvalue = $('#form_edit_merchant');
            var form_data = new FormData(formvalue[0]);
            $.ajax({
                url: '<?=base_url("master/C_Master/editMasterMerchant/").$result['id']?>',
                method: 'post',
                data: form_data,
                contentType: false,  
                cache: false,
                processData:false,
                success: function(){
                    $('#edit_data_merchant').modal('hide')
                    successtoast('Data sudah terupdate')
                    loadAllMerchant()
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        })
    </script>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12 text-center">
            <h5>DATA TIDAK DITEMUKAN <i class="fa fa-exclamation"></i></h5>
        </div>
    </div>
<?php } ?>    