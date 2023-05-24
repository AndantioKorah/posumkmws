<style>
  /* .image{
    width: 5px !important;
    height: 100px !important;
  } */
  .main-sidebar{
    position: fixed !important;
    /* margin-top: 50px; */
  }
</style>

<aside class="main-sidebar elevation-4 sidebar-light-navy">
  <!-- Brand Logo -->
  <a href="<?=base_url('welcome')?>" class="brand-link navbar-red">
    <img src="<?=base_url('assets/img/logo-putih-biru.png')?>" alt="SMS Gateway" class="brand-image img-circle elevation-3"
          style="opacity: .8">
    <span class="brand-text" style="font-weight: bold; color: white;"><?=TITLE_SECOND?></span>
  </a>
  
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?=$this->general_library->getProfilePicture()?>" style="height: 33px;" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="<?=base_url('user/setting')?>" class="d-block"><?=$this->general_library->getNamaUser();?></a>
      </div>
    </div>
    <?php 
    $active_role = $this->session->userdata('active_role');
    // $list_menu = $this->session->userdata('list_menu');
    $list_menu = $this->general_library->getListMenu($active_role['id'], $active_role['role_name']);
    if($list_menu){  
    ?>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach($list_menu as $l){ 
        ?>
          <li class="nav-item">
            <a href="<?=$l['url'] == '#' || $l['url'] == '' ? '#' : base_url($l['url'])?>" class="nav-link">
              <i class="<?=$l['icon']?> nav-icon"></i>
              <p>
                <?=$l['nama_menu']?>
                <?=$l['child'] ? '<i class="fas fa-angle-left right"></i>' : '' ?>
              </p>
            </a>
            <?php if($l['child']){ ?>
              <ul class="nav nav-treeview">
                <?php foreach($l['child'] as $ch){ ?>
                  <li class="nav-item">
                    <a href="<?=base_url($ch['url'])?>" class="nav-link">
                      <i class="<?=$ch['icon'] != '' ? $ch['icon'] : 'far fa-circle'?> nav-icon"></i>
                      <p><?=$ch['nama_menu']?></p>
                    </a>
                  </li>
                <?php } ?> 
              </ul>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </nav>
    <?php } ?>
</aside>