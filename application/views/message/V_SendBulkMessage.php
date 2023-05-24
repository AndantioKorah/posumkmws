<style>
    .label_contoh_nomor{
        font-size: 12px;
        color: grey;
    }
</style>
<div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">Kirim Pesan Massal</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_kirim_pesan_massal">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Jenis Pesan</label>
                        <select class="form-control form-control-sm select2-navy" style="width: 100%"
                        id="id_m_jenis_pesan" data-dropdown-css-class="select2-navy" name="id_m_jenis_pesan">
                            <?php if($list_jenis_pesan){
                                foreach($list_jenis_pesan as $ljp){
                                ?>
                                <option value="<?=$ljp['id'].';'.$ljp['jenis_pesan']?>">
                                    <?=$ljp['jenis_pesan']?>
                                </option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nomor Tujuan</label> <label class="label_contoh_nomor">(format nomor tujuan: '08xxxxxxxxxx' atau '628xxxxxxxxxx' atau '+628xxxxxxxxxx')</label>
                        <textarea placeholder="08xxxxxxxxxx&#10;+628xxxxxxxxxx&#10;081xxxxxxxxx" rows=6 class="form-control" autocomplete="off" name="nomor_tujuan" id="nomor_tujuan" required></textarea>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Isi Pesan</label>
                        <textarea rows=6 class="form-control" autocomplete="off" name="isi_pesan" id="isi_pesan" required></textarea>
                    </div>
                </div>          
                <div class="col-6">
                </div>
                <div class="col-6">
                    <button id="btn_send" class="btn btn-block btn-navy" type="submit"><i class="fa fa-paper-plane"></i> Kirim</button>
                    <button id="btn_loading" style="display: none;" class="btn btn-block btn-navy" disabled><i class="fa fa-spin fa-spinner"></i> Mengirim Pesan...</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-default" id="progress_card" style="display: none;">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">Progress Kirim Pesan Massal</h3>
    </div>
    <div class="card-body" id="progress_body_card" style="display: block;">
        
    </div>
</div>
<script>
    $(function(){
        showProgress(11)
        $('#id_m_jenis_pesan').select2()
    })

    $('#form_kirim_pesan_massal').on('submit', function(e){
        e.preventDefault();
        $('#btn_send').hide()
        $('#btn_loading').show()
        $.ajax({
            url: '<?=base_url("message/C_Message/sendBulkMessage")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                if(rs.code != '0'){
                    errortoast(rs.message)
                } else{
                    successtoast('Pesan sedang dikirim')
                    showProgress(rs.last_id)
                    $('#nomor_tujuan').val('')
                    $('#isi_pesan').val('')
                }
            }, error: function(e){
                errortoast('Terjadi Kesalahan')
            }
        })
        $('#btn_send').show()
        $('#btn_loading').hide()
    })

    function showProgress(id){
        $('#progress_card').show()
        $('#progress_body_card').html('')
        $('#progress_body_card').append(divLoaderNavy)
        var intervalProgress = setInterval(function(){
            $.ajax({
                url: '<?=base_url("message/C_Message/checkIfAllDoneBulkMessage")?>'+'/'+id,
                method: 'post',
                // data: $(this).serialize(),
                success: function(data){
                    let rs = JSON.parse(data)
                    console.log(rs)
                    if(rs.flag_all_done == '1'){
                        clearInterval(intervalProgress);
                        successtoast('Proses pengiriman pesan massal selesai')
                    }
                }, error: function(e){
                    errortoast('Terjadi Kesalahan')
                }
            })
            $('#progress_body_card').load("<?=base_url('message/C_Message/progressSendBulkMessage')?>"+'/'+id, function(){

            })
        }, 5000);
    }
</script>