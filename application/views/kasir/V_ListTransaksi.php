<style>
    .card-lunas{
        background-color: var(--primary);
        color: white;
    }

    .val-date{
        font-size: .8rem;
        font-weight: 500;
        color: grey;
    }

    .val-nama{
        font-size: 1rem;
        font-weight: bold;
        line-height: 1px !important;
    }

    .val-no-trans{
        font-size: .7rem;
        font-weight: bold;
        line-height: 1px !important;
        color: grey;
        font-style: italic;
    }

    .lbl_rp{
        color: var(--primary);
        font-weight: bold;
        font-size: .8rem;
    }

    .val_total_harga{
        color: var(--primary);
        font-weight: bold;
        font-size: 1.5rem;
    }

    .card-transaksi:hover{
        cursor: pointer;
        background-color: #e4e5e5;
        transition: .2s;
    }

</style>
<?php if($result){  ?>
    <div class="row">
        <?php foreach($result as $rs){ ?>
            <div class="col-lg-2 p-3">
                <div class="card card-default card-transaksi" onclick="detailTransaksi('<?=$rs['id']?>')">
                    <div class="card-header text-center <?=$rs['status_transaksi'] == 'Lunas' ? 'card-lunas' : 'card-belum-lunas'?>">
                        <div class="row">
                            <div style="margin-bottom: -10px;" class="col-lg-12 text-right">
                                <span class="val-no-trans">
                                    <?=$rs['nomor_transaksi']?>
                                </span>
                            </div>
                            <div class="col-lg-12 text-left">
                                <span class="val-nama">
                                    <?=$rs['nama'] != null && trim($rs['nama']) != "" ? $rs['nama'] : "-"?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-body-transaksi">
                        <div class="row p-0">
                            <div class="col-lg-12 text-center">
                                <span class="val_total_harga"><?=formatCurrency($rs['total_harga'])?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-lg-12 p-0 text-right">
                            <span class="val-date"><?=formatDate($rs['tanggal_transaksi'])?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>
        $(function(){
            // if(idBlank != 0){
            //     detailTransaksi(idBlank)
            // }
        })

        // function detailTransaksi(id){
        //     $('#main_view_kasir').hide()
        //     $('#view_detail_transaksi').show()
        //     $('#view_detail_transaksi').html('')
        //     $('#view_detail_transaksi').append(divLoaderNavy)
        //     $('#view_detail_transaksi').load('<?=base_url('kasir/C_Kasir/detailTransaksi/')?>'+id, function(){
        //         $('#loader').hide()
        //     })
        // }
    </script>
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12 text-center">
            <!-- <h6>BELUM ADA DATA <i class="fa fa-exclamation"></i></h6> -->
        </div>
    </div>
<?php } ?>