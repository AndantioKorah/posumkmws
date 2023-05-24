<?php
  $list_role = $this->general_library->getListRole();
  $active_role = $this->general_library->getActiveRole();
?>
<style>
  .dropdown-item:hover{
    cursor: pointer !important;
    background-color: #001f3f !important;
    color: white !important;
  }

  #search_navbar{
    color: white !important;
    font-weight: bold !important;
    width: 300px;
    transition: .3s;
  }

  #search_navbar:focus{
    color: white !important;
    background-color: rgb(255, 255, 255, .1) !important;
    font-weight: bold !important;
    font-size: 15px !important;
    font-family: Arial !important;
    width: 500px !important;
    transition: .3s;
    box-shadow: 0 0 10px rgba(255,255,255, 1);
  }

  #div_search_result{
    position: fixed;
    width: 500px;
    background-color: white;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;
    border-bottom-right-radius: 5px;
    border-bottom-right-radius: 5px;
    z-index: 10;
    top: 5%;
    box-shadow: 5px 10px 20px #888888;
    max-height: 300px;
    overflow-y: scroll;
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }

  #div_search_result::-webkit-scrollbar {
    display: none;
  }
</style>
<nav class="main-header navbar navbar-expand navbar-dark navbar-red">
    <!-- <form class="form-inline ml-3">
      <div class="row">
        <div class="input-group input-group-sm div_search_bar">
          <input id="search_navbar" style="width: 300px" autocomplete="off" class="form-control form-control-navbar" type="text" placeholder="Cari Pasien" aria-label="Search">
          <div class="input-group-append">
            <button id="button_fa_search" class="btn btn-navbar" type="button">
              <i class="fas fa-search"></i>
            </button>
            <button style="display: none;" id="button_fa_loading" class="btn btn-navbar" type="button">
              <i class="fas fa-spin fa-spinner"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="row" id="div_search_result">
      </div>
    </form> -->
    <ul class="navbar-nav">
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li> -->
      <li class="nav-item">
        <a style="font-weight: bold; color: white;" id="live_date_time" class="nav-link"></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Nofications</a>
        </div>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="<?=countDiffDateLengkap($params_exp_app['parameter_value'], date('Y-m-d H:i:s'), ['tahun', 'bulan', 'hari', 'jam', 'menit'])?>">
          <i class="fa fa-stopwatch"></i> <?=formatDate($params_exp_app['parameter_value'])?>
        </a>
      </li> -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="font-weight: bold; color: white;">
          <i class="fa fa-id-badge"></i>
          <?=$active_role['nama']?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <?php foreach($list_role as $lr){ ?>
              <a onclick="setActiveRole('<?=$lr['id']?>')" class="dropdown-item">
                  <?=$lr['id'] == $this->session->userdata('active_role_id') ? '<i class="fa fa-check-circle"></i> '.$lr['nama'] : $lr['nama']?>
              </a>
              <div class="dropdown-divider"></div>
            <?php } ?>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="font-weight: bold; color: white;">
            <img src="<?=$this->general_library->getProfilePicture()?>" style="height: 25px; width:25px; margin-right: 1px;" class="img-circle elevation-2" alt="User Image">
            <?=$this->general_library->getNamaUser()?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <!-- <div class="dropdown-divider"></div> -->
              <a href="<?=base_url('user/setting')?>" class="dropdown-item">
                  <i class="fa fa-users mr-2"></i> Account
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?=base_url('logout')?>" class="dropdown-item">
                  <i class="fa fa-sign-out-alt mr-2"></i> Keluar
              </a>
        </div>
      </li>
    </ul>
  </nav>
  <script>
    $(document).mouseup(function(e) 
    {
        var container = $("#search_navbar");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
          $('#div_search_result').hide()
        } else {
          $('#div_search_result').show()
        }
    });

    function setActiveRole(id){
      $.ajax({
          url: '<?=base_url("user/C_User/setActiveRole")?>'+'/'+id,
          method: 'post',
          data: $(this).serialize(),
          success: function(data){
              window.location=data.trim()
          }, error: function(e){
              errortoast('Terjadi Kesalahan')
          }
      })
    }

    $('#search_navbar').on('input', function(){
      $('#div_search_result').html('')
      if($(this).val() != ''){
        $('#button_fa_search').hide()
        $('#button_fa_loading').show()
        $.ajax({
            url: '<?=base_url("pendaftaran/C_Pendaftaran/searchPasien")?>',
            method: 'post',
            data: {
              search_param: $(this).val()
            },
            success: function(datares){
                $('#div_search_result').html('')
                $('#div_search_result').append(datares)
                $('#button_fa_search').show()
                $('#button_fa_loading').hide()
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
      }
    })

    
  </script>