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

    .class_td_success{
        background-color: #b8ffc8 !important;
    }

    .class_td_danger{
        background-color: #f4d9db !important;
    }
</style>
<?php if($result){
    $presentase_penjualan = $result['total_penjualan'] > 0 ? (($result['total_penjualan_lunas'] / $result['total_penjualan']) * 100) : 0;
?>
    <div class="row">
        <div class="col-lg-4">
            <div class="info-box bg-gradient-success" style="height: 15vh;">
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
            <div class="info-box bg-gradient-warning" style="height: 15vh;">
                <span class="info-box-icon"><i class="fa fa-boxes fa-2x"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">TOTAL PENGELUARAN</span>
                    <span class="info-box-number"><?=formatCurrency($result['total_pengeluaran'])?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box bg-gradient-info" style="height: 15vh;">
                <span class="info-box-icon"><i class="fa fa-hand-holding-usd fa-2x"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PENDAPATAN BERSIH</span>
                    <span class="info-box-number"><?=formatCurrency($result['pendapatan_bersih'])?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-2 p-3 card card-default table-responsive">
        <table class="table table-hover" id="table_list_transaksi">
            <thead>
                <th class="text-center">No</th>
                <th class="text-left">Nama Transaksi</th>
                <th class="text-center">Tanggal Transaksi</th>
                <th class="text-center">Nominal</th>
            </thead>
            <tbody>
                <?php if(isset($result['result']) && $result['result']){ $no = 1; foreach($result['result'] as $r){ ?>
                    <tr class="<?=isset($r['status_transaksi']) ? $r['status_transaksi'] == 'Lunas' ? 'class_td_success' : '' : 'class_td_danger'?>">
                        <td class="text-center"><?=$no++;?></td>
                        <td class="text-left">
                            <span class="badge <?=isset($r['status_transaksi']) ? $r['status_transaksi'] == 'Lunas' ? 'badge-success' : 'badge-warning' : ''?>"><?=isset($r['status_transaksi']) ? $r['status_transaksi'] : ''?></span>
                            <?=$r['nama_transaksi']?>
                        </td>
                        <td class="text-center"><?=formatDateNamaBulan($r['tanggal_transaksi'], 1)?></td>
                        <td class="text-center">
                            <span style="font-weight: bold; font-size: 1rem;" class="description-percentage <?=isset($r['status_transaksi']) ? $r['status_transaksi'] == 'Lunas' ? 'text-success' : 'text-warning' : 'text-danger'?>">
                            <!-- <i class="fas <?=isset($r['status_transaksi']) ? $r['status_transaksi'] == 'Lunas' ? 'fa-chevron-circle-up' : 'fa-circle' : 'fa-chevron-circle-up'?> "></i> <?=formatCurrency($r['nominal'])?></span> -->
                            <i class="fas <?=isset($r['status_transaksi']) ? $r['status_transaksi'] == 'Lunas' ? 'fa-caret-up' : 'fa-minus' : 'fa-caret-down'?> "></i> <?=formatCurrency($r['nominal'])?></span>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
    <script>
        $(function(){
            $('#table_list_transaksi').dataTable()
        })
    </script>
<?php } ?>