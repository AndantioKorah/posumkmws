<style>
.foto_container {
  position: relative;
  /* width: 50%; */
}

.image-settings {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.foto_container:hover .image {
  opacity: 0.3;
  cursor:pointer;
}

.foto_container:hover .middle {
  opacity: 1;
  cursor:pointer;
}

</style>
<div class="row">
    <div class="col-12">
        <div class="card card-navy">
            <div class="card-header card_header" style="cursor: pointer;" data-card-widget="collapse">
                <h3 class="card-title">USER PROFILE</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <center>
                        <div class="foto_container">
                            <img src="<?=$this->general_library->getProfilePicture()?>" style="height: 350px; width: 350px; margin-right: 1px;" 
                            class="img-circle elevation-2 image-settings" alt="User Image">
                                <div class="middle">
                                    <form id="form_profile_pict" action="<?=base_url('user/C_User/updateProfilePict')?>" method="post" enctype="multipart/form-data">
                                        <input class="form-control" accept="image/x-png,image/gif,image/jpeg" type="file" name="profilePict" id="profilePict">
                                    </form>
                                    <!-- <button class="btn btn-sm btn-navy"><i class="fa fa-image"></i> Ganti Foto</button> -->
                                </div>
                        </div>
                        <?php if($this->general_library->getDataProfilePicture()){ ?>
                            <form action="<?=base_url('user/C_User/deleteProfilePict')?>" method="post">
                                <button class="btn bt-sm btn-danger mt-3"><i class="fa fa-trash"></i> Hapus Foto</button>
                            </form>
                        <?php } ?>
                        </center>                  
                    </div>
                    <div class="col-6">
                        <form action="<?=base_url('user/C_User/updateProfile')?>" method="POST">
                            <div class="row">
                                <div class="col-12">
                                    <label>Username</label>
                                    <input class="form-control form-control-sm" readonly value="<?=$this->general_library->getUserName()?>" />
                                </div>
                                <div class="col-12">
                                    <label>Role</label>
                                    <input class="form-control form-control-sm" readonly value="<?=$this->general_library->getRole()?>" />
                                </div>
                                <div class="col-12">
                                    <label>Nama</label>
                                    <input class="form-control form-control-sm" name="nama" value="<?=$this->general_library->getNamaUser()?>" />
                                </div>
                                <div class="col-9"></div>
                                <div class="col-3 text-right mt-3">
                                    <button type="submit" class="btn btn-sm btn-navy"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                </div>
                                <div class="col-12"><hr></div>
                            </div>
                        </form>
                        <form action="<?=base_url('user/C_User/changePassword')?>" method="POST">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h5><i class="fa fa-key"></i> Change Password</h5>
                                </div>
                                <div class="col-12">
                                    <label>Password Lama</label>
                                    <input class="form-control form-control-sm" autocomplete="off" type="password" name="password_lama"/>
                                </div>
                                <div class="col-12">
                                    <label>Password Baru</label>
                                    <input class="form-control form-control-sm" autocomplete="off" type="password" name="password_baru"/>
                                </div>
                                <div class="col-12">
                                    <label>Konfirmasi Password Baru</label>
                                    <input class="form-control form-control-sm" autocomplete="off" type="password" name="konfirmasi_password"/>
                                </div>
                                <div class="col-9"></div>
                                <div class="col-3 text-right mt-3">
                                    <button type="submit" class="btn btn-sm btn-navy"><i class="fa fa-save"></i>&nbsp;&nbsp;Ganti Password</button>
                                </div>
                                <div class="col-12"><hr></div>
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
        <?php if($this->session->flashdata('message') && $this->session->flashdata('message') != '0'){ ?>
            errortoast('<?=$this->session->flashdata('message')?>')
        <?php } ?>
        <?php if($this->session->flashdata('message') == '0'){ ?>
            successtoast('UPDATE BERHASIL')
        <?php } ?>
    })

    $('#profilePict').on('change', function(){
        $('#form_profile_pict').submit()
    })
</script>