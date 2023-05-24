<style>
    .label_contoh_nomor{
        font-size: 13px;
        color: grey;
    }
</style>
<div class="card card-default">
    <div class="card-header"  style="display: block;">
        <h3 class="card-title">Kirim Pesan Individu</h3>
    </div>
    <div class="card-body" style="display: block;">
        <form id="form_kirim_pesan_individu">
            <div class="row">
                <div class="col-6">
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
                    <div class="col-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Nomor Tujuan</label><br>
                            <label class="label_contoh_nomor">(format nomor tujuan: '08xxxxxxxxxx' atau '628xxxxxxxxxx' atau '+628xxxxxxxxxx')</label>
                            <input class="form-control" autocomplete="off" name="nomor_tujuan" id="nomor_tujuan" required/>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Isi Pesan</label><br>
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
<script>
    $(function(){
        $('#id_m_jenis_pesan').select2()
    })

    $('#form_kirim_pesan_individu').on('submit', function(e){
        e.preventDefault();
        $('#btn_send').hide()
        $('#btn_loading').show()
        $.ajax({
            url: '<?=base_url("message/C_Message/sendIndividuMessage")?>',
            method: 'post',
            data: $(this).serialize(),
            success: function(data){
                let rs = JSON.parse(data)
                if(rs.code != '0'){
                    errortoast(rs.message)
                } else{
                    successtoast('Pesan terkirim')
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
</script>