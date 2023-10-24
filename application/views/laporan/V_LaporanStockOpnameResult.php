<style>
    .tr_menu_merchant:hover{
        cursor: pointer;
    }
</style>
<div class="card card-default p-3">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" onclick="openDataMenu()" aria-current="page" href="#menu_tab" data-toggle="tab">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="openDataBahanBaku()" href="#bahan_baku_tab" data-toggle="tab">Bahan Baku</a>
                </li>
            </ul>
        </div>
        <div class="tab-content col-12 mt-2" id="myTabContent">
            <div class="tab-pane show active" id="menu_tab">
            </div>
            <div class="tab-pane" id="bahan_baku_tab">
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        openDataMenu()
    })

    function openDataMenu(){
        $('#menu_tab').html('')
        $('#menu_tab').append(divLoaderNavy)
        $('#menu_tab').load('<?=base_url("laporan/C_Laporan/openStockOpnameMenuResult")?>', function(){
            $('#loader').hide()
        })
    }

    function openDataBahanBaku(){
        $('#bahan_baku_tab').html('')
        $('#bahan_baku_tab').append(divLoaderNavy)
        $('#bahan_baku_tab').load('<?=base_url("laporan/C_Laporan/openStockOpnameBahanBakuResult")?>', function(){
            $('#loader').hide()
        })
    }
</script>