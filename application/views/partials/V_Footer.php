<?php
  $params_exp_app = $this->general_library->getParams('PARAM_EXP_APP');
  $params_merchant_code = $this->general_library->getParams('PARAM_MERCHANT_CODE');
?>
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <!-- <label><i class="fa fa-stopwatch"></i> <?=formatDate($params_exp_app['parameter_value'])?> (<a class="count_down_exp_app"></a>) / <?=$params_merchant_code['parameter_value']?></label> -->
    </div>
    <strong><?=COPYRIGHT?>
    <!-- <a target="_blank" href="<?=base_url('assets/user_manual/').VERSION.'.pdf'?>">User Manual <?=VERSION?> </a></strong> -->
</footer>