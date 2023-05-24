<div class="col-12">
    <h6 style="color: black">Isi Pesan: </h6>
    <h4 style="font-weight: bold; color: black"><?=$message_detail[0]['isi_pesan']?></h4>
    <table class="table table-hover">
        <thead>
            <th class="text-center">No</th>
            <th class="text-center">Nomor Tujuan</th>
            <th class="text-center">Status</th>
            <th class="text-left">Keterangan</th>
        </thead>
        <tbody>
            <?php $no=1; foreach($message_detail as $md){ ?>
                <tr>
                    <td class="text-center"><?=$no++;?></td>
                    <td class="text-center"><?=$md['nomor_tujuan']?></td>
                    <?php
                        $status = "<span style='color: blue;'><i class='fa fa-spin fa-spinner'></i> sending....</span>";
                        if($md['response_message'] == 'OK'){
                            $status = "<span style='color: green;'><i class='fa fa-check'></i> done</span>";
                        } else if($md['response_message'] && $md['response_message'] != 'OK'){
                            $status = "<span style='color: red;'><i class='fa fa-times'></i> failed</span>";
                        }
                    ?>
                    <td class="text-center"><?=$status?></td>
                    <td class="text-left"><?=$md['response_message']?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>