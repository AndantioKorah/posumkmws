<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?=base_url('assets/new_login/fonts/icomoon/style.css')?>">

    <link rel="stylesheet" href="<?=base_url('assets/new_login/css/owl.carousel.min.css')?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=base_url('assets/new_login/css/bootstrap.min.css')?>">
    
    <!-- Style -->
    <link rel="stylesheet" href="<?=base_url('assets/new_login/css/style.css')?>">

    <title><?=TITLES?></title>
    <link rel="shortcut icon" href="<?=base_url('assets/img/logo-putih-biru.png')?>" />
    <link rel="stylesheet" href="<?=base_url('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')?>">
  </head>
  <body>
  

  
  <div class="content" style="height: 100vh;">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img src="<?=base_url('assets/img/logo-biru-transparent.png')?>" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
              <h3>Selamat Datang di <br>NiKita Cashier</h3>
              <p class="mb-4">Silahkan Log In untuk melanjutkan</p>
            </div>
            <form action="<?=base_url('login/C_Login/authenticateAdmin')?>" method="post">
              <div class="form-group first">
                <label for="username">Username</label>
                <input name="username" type="text" class="form-control" autocomplete="off" id="username">

              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control" autocomplete="off" id="password">
                
              </div>
              <input type="submit" value="Log In" class="btn btn-block btn-primary">
              <span class="d-block text-left my-4 text-muted"><?=COPYRIGHT?></span>
              </div>
            </form>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

  
    <script src="<?=base_url('assets/new_login/js/jquery-3.3.1.min.js')?>"></script>
    <script src="<?=base_url('assets/new_login/js/popper.min.js')?>"></script>
    <script src="<?=base_url('assets/new_login/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('assets/new_login/js/main.js')?>"></script>
  </body>
</html>
<script>
  $(function(){
    <?php if($this->session->flashdata('message')){ ?>
      errortoast('<?=$this->session->flashdata('message')?>')
      // $('#error_div').show()
      // $('#error_div').append('<label>'+'<?=$this->session->flashdata('message')?>'+'</label>')
    <?php
      $this->session->set_flashdata('message', null);
    } ?>

<?php if($this->session->userdata('apps_error')){ ?>
      errortoast('<?=$this->session->userdata('apps_error')?>')
      // $('#error_div').show()
      // $('#error_div').append('<label>'+'<?=$this->session->userdata('apps_error')?>'+'</label>')
    <?php
      $this->session->set_userdata('apps_error', null);
    } ?>
  })

  function errortoast(message = '', timertoast = 3000){
    const Toast = Swal.mixin({
      toast: true,
      position: 'top',
      showConfirmButton: false,
      timer: timertoast
    });

    Toast.fire({
      icon: 'error',
      title: message
    })
  }

  function hideError(){
    $('#error_div').hide()
    $('#error_div').html('')
  }
</script>
<script src="<?=base_url('plugins/sweetalert2/sweetalert2.min.js')?>"></script>