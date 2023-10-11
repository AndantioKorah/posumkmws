<?php
  $params_exp_app = $this->general_library->getParams('PARAM_EXP_APP');
  $list_role = $this->general_library->getListRole();
  $active_role = $this->general_library->getActiveRole();
?>
<style>
#profile_pict {
    max-width: 150px;
    max-height: 150px;
    animation: zoom-in-zoom-out 5s ease infinite;
}

@keyframes zoom-in-zoom-out {
  0% {
    transform: scale(1, 1);
  }
  50% {
    transform: scale(1.1, 1.1);
  }
  100% {
    transform: scale(1, 1);
  }
}
</style>
<!-- <div class="row" style="position: fixed;
  top: 50%;
  left: 53%;
  transform: translate(-50%, -50%);">
    <div class="col-12 text-center">
        <h3>Welcome to <?=TITLE_SECOND?></h3>
        <h4><strong><?=$this->general_library->getNamaUser();?></strong></h4>
        <img class="img-circle elevation-2" id="profile_pict" style="max-width: 150px; max-height: 150px;" src="<?=$this->general_library->getProfilePicture()?>" alt="User Image">
    </div>
    <div class="col-12 text-center">
        <h4 style="font-weight: bold;" id="live_date_time_welcome" class="nav-link"></h4>
    </div>
    <div class="col-12 text-center mt-3">
        <div class="row">
            <?php foreach($list_role as $lr){ ?>
                <div class="col-2">
                    <button class="btn btn-navy btn-block"><?=$lr['nama']?></button>
                </div>
            <?php } ?>
        </div>
    </div>
</div> -->
<div class="row">
  <div class="col-lg-12">
    <div class="row" style="height: 15vh;">
      <div class="col-lg-1">
        <img class="img-circle elevation-2" id="profile_pict" style="max-width: 100px; max-height: 100px;" src="<?=$this->general_library->getProfilePicture()?>" alt="User Image">
      </div>
      <div class="col-lg-11">
        <h3>Welcome to <?=TITLE_SECOND?></h3>
        <h4><strong><?=$this->general_library->getNamaUser();?></strong></h4>
        <h6><strong>Expired Date: <?=formatDateNamaBulan($this->general_library->getExpDateMerchant())?></strong></h6>
      </div>
    </div>
    <div class="col-lg-12"><hr style="color: black;"></div>
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <h5 class="card-title">Transaksi Hari Ini</h5>
            </div>
            <div class="col-lg-6 text-right">
              <button id="btn_laporan_welcome" class="btn btn-sm btn-navy">Lebih Banyak <i class="fa fa-arrow-right"></i></button>
            </div>
          </div>
        </div>
        <div class="card-bdoy p-3" style="height: 20vh;">
          <div class="laporan_hari_ini">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12" style="display: none;">
      <div class="card card-default" style="height: 30vh;">
        <div class="card-header">
          <h5 class="card-title">Quick Access</h5>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(function(){
    loadLaporanToday()
  })

  $('#btn_laporan_welcome').on('click', function(){
    window.open('<?=base_url('laporan/penjualan')?>');
  })

  function loadLaporanToday(){
    $.ajax({
        url: '<?=base_url("laporan/C_Laporan/searchLaporanPenjualan/1")?>',
        method: 'post',
        data: {
          'range_tanggal': '<?=date('d/m/Y').' - '.date('d/m/Y')?>'
        },
        success: function(data){
            $('.laporan_hari_ini').html('')
            $('.laporan_hari_ini').append(divLoaderNavy)
            $('.laporan_hari_ini').html(data)
        }, error: function(e){
            errortoast('Terjadi Kesalahan')
        }
    })
  }
</script>