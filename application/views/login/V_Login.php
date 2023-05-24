<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=TITLES?></title>
  <link rel="shortcut icon" href="<?=base_url('assets/img/logo-biru-putih.png')?>" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?=base_url('plugins/fontawesome-free/css/all.min.css')?>">
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet" href="<?=base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/adminlte.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/general.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/font.css')?>">
    <link rel="stylesheet" href="<?=base_url('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')?>">
    <style>
        .login-page {
            background-image: url('assets/img/login.jpg');
            background-size: 100%;
            width: 100%;
            background-attachment: scroll;
            background-size: cover;
            background-repeat: repeat;
        }

        .btn-navy{
          color: white;
          background-color: #001f3f !important;
          text-decoration: none;
        }

        .btn-navy:hover{
          color: white;
          background-color: #05519e !important;
          text-decoration: none;
        }

        .input-group-text{
          color: #001f3f !important;
        }

        .text-navy{
          color: #001f3f !important;
        }

        .card:hover{
          opacity: 1 !important;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- <div class="login-logo">
    <a href="#"><b><?=TITLES?></b></a>
  </div> -->
  <!-- /.login-logo -->
  <div class="card shadow-lg mb-5 bg-white rounded" style="opacity: 1; transition: .2s">
    <div class="card-body login-card-body">
      <center>
      <img src="<?=base_url('assets/img/logo-putih-biru.png')?>" style="height: 250px; width: 250px;
      margin-top: -20px;
      margin-bottom: -50px;"/>
      <br>
      <span style="font-weight: bold; font-size: 25px; color: black; font-family: Verdana;"><?=TITLE_SECOND?></span>
      <br>
      <span style="font-weight: bold; font-size: 14px; color: black">BKPSDM Kota Manado</span>
      <br>
      </center>
      <form action="<?=base_url('login/C_Login/authenticateAdmin')?>" method="post">
        <div class="input-group mb-3 mt-3">
          <input type="text" class="form-control" onclick="hideError()" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" onclick="hideError()" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-7">
          </div>
          <div class="col-5 text-right">
            <button type="submit" class="btn btn-navy btn-sm">Sign In <i class="fas fa-sign-in-alt"></i></button>
          </div>
        </div>
      </form>

      <div class="col-12 text-center text-red mt-3" id="error_div" style="display: none;"></div>
    </div>
  </div>
</div>

<script src="<?=base_url('plugins/jquery/jquery.min.js')?>"></script>
<script src="<?=base_url('plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/js/adminlte.min.js')?>"></script>

</body>
</html>
<script>
  $(function(){
    <?php if($this->session->flashdata('message')){ ?>
      $('#error_div').show()
      $('#error_div').append('<label>'+'<?=$this->session->flashdata('message')?>'+'</label>')
    <?php
      $this->session->set_flashdata('message', null);
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