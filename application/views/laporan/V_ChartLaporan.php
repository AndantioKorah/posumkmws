<div class="col-lg-7">
  <div class="chart chart-sm" style="max-height: 150px !important; min-height: 150px !important;">
    <canvas id="<?=$id_chart?>"></canvas>
  </div>
</div>
<div class="col-lg-5">
  <table>
    <?php 
    
    $i = 0;
    $colors = CHART_COLORS;
    foreach($result as $rs){
      if($rs['total'] > 0){
    ?>
      <tr>
          <td><span style="background-color: <?=$colors[$i]?>">&nbsp;&nbsp;</span></td>
          <td><span style="font-size: .7rem;"><?=$rs['nama']?></span></td>
          <td><span style="font-size: .7rem;">:&nbsp;&nbsp;</span></td>
          <td class="text-right"><span style="font-size: .7rem; font-weight: bold;"><?=formatCurrencyWithoutRp($rs['total'])?></span></td>
      </tr>
    <?php $i++; } } ?>
  </table>
</div>
<script>
    // $(function(){
    //     renderChart('<?=json_encode($result)?>')
    // })

</script>