<html>
    <head>
        <style>
            /* @font-face {
                font-family: 'DotMatrix';
                src: url('<?=base_url('assets/fonts/DotMatrix/DOTMATRIX.ttf')?>') format('truetype');
                font-weight: normal;
                font-style: normal;
            } */

            /* @font-face {
                font-family: 'MerchantCopy';
                src: url('<?=base_url('assets/fonts/MerchantCopy/MerchantCopy.ttf')?>') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'ArrayPrint';
                src: url('<?=base_url('assets/fonts/Array_Complete/Fonts/OTF/Array-Regular.otf')?>') format('opentype');
                font-weight: normal;
                font-style: normal;
            } */

            @media print {
                body{
                    width: 58mm;
                    margin: 0;
                    padding: 0;
                    font-family: Tahoma;
                    /* font-family: "DotMatrix"; */
                    color: black;
                }
            }

            /* #body_receipt{
                width: 58mm !important;
                font-family: "MerchantCopy";
            } */
            
            .val_title{
                font-size: 1.1rem;
                font-weight: 500;
                color: black;
            }

            .val_sub_title{
                font-size: .7rem;
                font-weight: 500;
                color: black;
            }

            .val_nama_menu_bill{
                font-size: .8rem;
                font-weight: 500;
                color: black;
            }

            .val_qty_menu{
                font-size: .8rem;
                font-weight: normal;
                color: black;
            }
            
            .val_total_harga{
                font-size: .8rem;
                font-weight: 500;
                color: black;
            }

            .recap_table{
                font-size: .8rem;
                font-weight: 500;
                color: black;
            }

            .table_pembayaran{
                font-size: .6rem;
                color: black;
            }

            .val_header_text{
                font-size: .8rem;
            }

            .text-info{
                font-size: .7rem;
            }

        </style>
    </head>
    <body id="body_receipt">
        <table style="width: 100%;">
            <tr>
                <td colspan=1 style="width: 50%; text-align: left;">
                    <span class="val_header_text">
                        <?=$transaksi['nomor_transaksi'].' / '.$transaksi['status_transaksi'].' / '.($transaksi['nama'] != '' && $transaksi['nama'] != null ? $transaksi['nama'] : '-')?>
                    </span>
                </td>
                <td colspan=1 style="width: 50%; text-align: right;">
                    <span class="val_header_text"><?=formatDateNamaBulan($transaksi['tanggal_transaksi'], 1)?></span>
                </td>
            </tr>
            <tr><td colspan=2>
                <!-- <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;"> -->
            </td></tr>
            <tr style="text-align: center;">
                <!-- <td colspan=2 style="width: 100%;">
                    <img id="logo_merchant" style="width: 3rem; height: 3rem;" src="<?=base_url('assets/logo_merchant/'.$merchant['logo'])?>" />
                </td> -->
            </tr>
            <tr style="text-align: center; line-height: .5rem;">
                <td colspan=2 style="width: 100%;">
                    <span class="val_title">
                        <?=$merchant['nama_merchant']?>
                    </span>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td colspan=2 style="width: 100%;">
                    <span class="val_sub_title">
                        <?=$merchant['alamat']?>
                    </span>
                </td>
            </tr>
        </table>
        <?php if($pembayaran){ ?>
            <table style="width: 100%;" class="table_pembayaran">
                <tr>
                    <td colspan=3>
                        <!-- <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;"> -->
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">
                        Tgl. Bayar
                    </td>
                    <td style="width: 5%;">
                        :
                    </td>
                    <td style="width: 65%; text-align: right;">
                        <?=formatDateNamaBulan($pembayaran['tanggal_pembayaran'], 1)?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">
                        Cr. Bayar
                    </td>
                    <td style="width: 5%;">
                        :
                    </td>
                    <td style="width: 65%; text-align: right;">
                        <?=$pembayaran['nama_jenis_pembayaran']?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">
                        Nama
                    </td>
                    <td style="width: 5%;">
                        :
                    </td>
                    <td style="width: 65%; text-align: right;">
                        <?=($pembayaran['nama_pembayar'])?>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <?php if($detail){ ?>
            <table style="width: 100%;">
                <tr>
                    <td colspan=2>
                        <!-- <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;"> -->
                    </td>
                </tr>
                <?php foreach($detail as $d){ ?>
                    <tr>
                        <td style="width: 70%;">
                            <span class="val_nama_menu_bill"><?=$d['nama_menu_merchant']?></span><br>
                            <span class="val_qty_menu"><?=formatCurrencyWithoutRp($d['qty']).' x '.formatCurrencyWithoutRp($d['harga'])?></span>
                        </td>
                        <td style="width: 30%; text-align: right; vertical-align: bottom;">
                            <span class="val_total_harga"><?=formatCurrencyWithoutRp($d['total_harga'])?></span><br>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan=2>
                        <!-- <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;"> -->
                    </td>
                </tr>
            </table>
        <?php } ?>
        <table style="width: 100%;">
            <tr>
                <td style="width: 30%;"></td>
                <td style="width: 80%; padding: 0;">
                    <table style="border-spacing: 0; border-collapse: collapse; width: 100%;" class="recap_table">
                        <tr>
                            <td style="width: 45%;">
                                Total
                            </td>
                            <td style="width: 5%;">
                                :
                            </td>
                            <td style="width: 50%; text-align: right;">
                                <?=formatCurrencyWithoutRp($transaksi['total_harga'])?>
                            </td>
                        </tr>
                        <?php if($pembayaran){ ?>
                            <tr>
                                <td style="width: 45%;">
                                    Pembayaran
                                </td>
                                <td style="width: 5%;">
                                    :
                                </td>
                                <td style="width: 50%; text-align: right;">
                                    <?=formatCurrencyWithoutRp($pembayaran['total_pembayaran'])?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">
                                    Diskon
                                </td>
                                <td style="width: 5%;">
                                    :
                                </td>
                                <td style="width: 50%; text-align: right;">
                                    <?=formatCurrencyWithoutRp($pembayaran['diskon_nominal'])?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">
                                    Kembalian
                                </td>
                                <td style="width: 5%;">
                                    :
                                </td>
                                <td style="width: 50%; text-align: right;">
                                    <?=formatCurrencyWithoutRp($pembayaran['kembalian'])?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-size: .6rem;">
            <!-- <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;"> -->
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <span>Printed Date:</span><br>
                    <span><?=formatDate(date('Y-m-d H:i:s'))?></span>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right">
                    <span>Printed By:</span><br>
                    <span><?=$this->general_library->getNamaUser()?></span>
                </td>
            </tr>
        </table>
        <table style="margin-top: 15px; font-size: .6rem; width: 100%;">
            <?php if($pembayaran){ ?>
                <tr style="text-align: center;">
                    <td>
                        <span class="text-info">
                            Ini adalah bukti pembayaran yang sah
                        </span>
                    </td>
                </tr>
            <?php } else { ?>
                <tr style="text-align: center;">
                    <td>
                        <span class="text-info">
                            Ini bukan bukti pembayaran!
                        </span>
                    </td>
                </tr>
            <?php } ?>
            <?php if($pembayaran){ ?> 
                <tr>
                    <td>
                        <hr style="border-top: dotted 1px; padding-top: 0; padding-bottom: 0;">
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php if($pembayaran){ ?>
            <table style="width: 100%; font-size: .8rem; margin-top: -25px;">
                <tr>
                    <td style="text-align: center; vertical-align: middle; padding: 0px;">
                        <img style="width: 3rem; height: 3rem;" src="<?=base_url('assets/img/logo-biru-transparent.png')?>" /><br>
                        <!-- <span class="text-info-powered">
                            NiKita Developer
                        </span> -->
                    </td>
                </tr>
            </table>
        <?php } ?>
    </body>
</html>