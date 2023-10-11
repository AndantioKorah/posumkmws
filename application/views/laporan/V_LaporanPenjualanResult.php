<style>
    .progress-description{
        font-weight: bold;
        font-size: 1rem;
    }

    .progress-description-sm{
        font-weight: bold;
        font-size: .8rem;
    }

    .info-box-number{
        font-size: 1.5rem;
    }

    .td_list_transaksi_belum_lunas{
        background-color: #f99d9d;
    }

    .tr_class_list_transaksi:hover{
        cursor:pointer;
    }
</style>
<?php if($result){
    $presentase_penjualan = $result['total_penjualan'] > 0 ? (($result['total_penjualan_lunas'] / $result['total_penjualan']) * 100) : 0;
    $presentase_transaksi = $result['total_transaksi'] > 0 ? (($result['total_transaksi_lunas'] / $result['total_transaksi']) * 100) : 0;
    $presentase_item = $result['total_item'] > 0 ? (($result['total_item_lunas'] / $result['total_item']) * 100) : 0;
?>
    <?php if($flag_welcome == 1){ ?>
        <div class="row">
            <div class="col-lg-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fa fa-money-bill fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL PENJUALAN</span>
                        <span class="info-box-number"><?=formatCurrency($result['total_penjualan'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_penjualan)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=formatCurrency($result['total_penjualan_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=formatCurrency($result['total_penjualan_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fa fa-file-alt fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL TRANSAKSI</span>
                        <span class="info-box-number"><?=($result['total_transaksi'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_transaksi)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=($result['total_transaksi_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=($result['total_transaksi_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box bg-gradient-secondary">
                    <span class="info-box-icon"><i class="fa fa-mug-hot fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL ITEM</span>
                        <span class="info-box-number"><?=($result['total_item'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_item)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=($result['total_item_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=($result['total_item_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-lg-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fa fa-money-bill fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL PENJUALAN</span>
                        <span class="info-box-number"><?=formatCurrency($result['total_penjualan'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_penjualan)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=formatCurrency($result['total_penjualan_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=formatCurrency($result['total_penjualan_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fa fa-file-alt fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL TRANSAKSI</span>
                        <span class="info-box-number"><?=($result['total_transaksi'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_transaksi)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=($result['total_transaksi_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=($result['total_transaksi_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box bg-gradient-secondary">
                    <span class="info-box-icon"><i class="fa fa-mug-hot fa-2x"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL ITEM</span>
                        <span class="info-box-number"><?=($result['total_item'])?></span>
                        <div class="progress mt-2 mb-2">
                            <div class="progress-bar" style="width: <?=formatDecimal($presentase_item)?>%"></div>
                        </div>
                        <span class="progress-description">
                            <?=($result['total_item_lunas'])?> Lunas
                        </span>
                        <span class="progress-description-sm">
                            <?=($result['total_item_belum_lunas'])?> Belum Lunas
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h5 class="card-title">Jenis Menu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="chart">
                                    <canvas id="chart_jenis_menu" style="min-height: 15rem; height: 15rem; max-height: 15rem; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h5 class="card-title">Kategori Menu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="chart">
                                    <canvas id="chart_kategori_menu" style="min-height: 15rem; height: 15rem; max-height: 15rem; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-default p-3">
                    <table border=1 class="table table-sm table-hover table-striped">
                        <thead>
                            <th class="text-center">No</th>
                            <th class="text-center">Nomor Transaksi</th>
                            <th class="text-left">Nama</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-left">Total</th>
                            <th class="text-left">Status</th>
                        </thead>
                        <tbody>
                            <?php if($result['data_transaksi']){ $no = 1; foreach($result['data_transaksi'] as $dt){ ?>
                                <tr class="tr_class_list_transaksi" style="font-weight: bold;" onclick="openDetailBlank('<?=$dt['id']?>')">
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-center"><?=$no++;?></td>
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-center"><?=$dt['nomor_transaksi']?></td>
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-left"><?=$dt['nama'] != '' || $dt['nama'] != null ? $dt['nama'] : '-'?></td>
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-center"><?=formatDate($dt['tanggal_transaksi'])?></td>
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-left"><?=formatCurrency($dt['total_harga'])?></td>
                                    <td class="<?=$dt['status_transaksi'] == 'Belum Lunas' ? 'td_list_transaksi_belum_lunas' : ''?> text-center"><?=$dt['status_transaksi']?></td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6" id="table_list_item">
                <div class="card card-default p-3">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th class="text-center">No</th>
                            <th class="text-left">Menu</th>
                            <th class="text-center">Qty</th>
                        </thead>
                        <tbody>
                            <?php $no=1; if($result['list_item']){ $max = $result['list_item'][0]['penjualan']['qty'];
                            foreach($result['list_item'] as $li){ ?>
                                <tr>
                                    <td class="text-center" style="vertical-align: middle !important;"><strong><?=$no++?></strong></td>
                                    <td class="text-left" style="vertical-align: middle !important;">
                                        <span style="
                                            color: black;
                                            font-weight: bold;
                                            font-size: 1rem;
                                        ">
                                            <?=$li['nama_menu_merchant']?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span style="
                                            color: black;
                                            font-weight: bold;
                                            font-size: 1.3rem;
                                        ">
                                            <?=$li['penjualan']['qty']?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar" style="background-color: <?=getProgressBarColor(formatDecimal(($li['penjualan']['qty'] / $max) * 100))?>; 
                                            width: <?=formatDecimal(($li['penjualan']['qty'] / $max) * 100)?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        $(function(){
            renderChart('<?=json_encode($result['jenis_menu'])?>', 'chart_jenis_menu')
            renderChart('<?=json_encode($result['kategori_menu'])?>', 'chart_kategori_menu')
        })

        function renderChart(rs, id_chart){
            let dt = JSON.parse(rs)
            let labels = [];
            let values = [];    
            for (var item in dt) {
                labels.push(dt[item].nama+': '+dt[item].total)
                values.push(dt[item].total)
            }

            var donutData        = {
                labels: labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor : JSON.parse('<?=json_encode(CHART_COLORS)?>'),
                    }
                ]
            }

            var pieChartCanvas = $('#'+id_chart).get(0).getContext('2d')
            var pieData        = donutData;
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions
            })
        }

        function openDetailBlank(id){
            $.ajax({
                url: '<?=base_url("kasir/C_Kasir/openDetailBlank/")?>'+id,
                method: 'post',
                data: $(this).serialize(),
                success: function(data){
                    window.open('<?=base_url('kasir')?>', '_blank');
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
        }
    </script>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12 text-center">
            <h5>Data Tidak Ditemukan <i class="fa fa-exclamation"></i></h5>
        </div>
    </div>
<?php } ?>