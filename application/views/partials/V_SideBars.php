<div id="nikita-side-bar">
    <div class="text-center">
        <strong><a id="real-time"></a></strong>
    </div>
    <div style="height: 3px; background-color: #000d1aef;"></div>
    <div class="text-center" id="profile-picture-user">
        <a href="#"><img id="id-user-profile" class="user-profile mt-2" style="width: 100px; height: 100px;" src="<?=base_url('assets/img/default-profile-user.png')?>"/></a>
        <div style="display:none" id="user-profile-menu" class="mt-1">
            <ul class="nav flex-column text-left">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <strong>
                            <span class="fa fa-key"></span> Change Password
                        </strong>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <strong>
                            <span class="fa fa-sign-out-alt"></span> Log Out
                        </strong>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="mt-2 mb-2" style="height: 3px; background-color: #000d1aef;"></div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#" id="daftar-baru">
                <strong>
                    <span class="fa fa-user-plus"></span> Daftar Baru
                </strong>
            </a>
        </li>
        <div id="div-nav-item-child" class="daftar-div-nav-item-child mt-1">
            <ul class="nav flex-column">
                <li class="nav-item-child mb-1">
                    <?php if($active == 'user-baru'){ ?>
                        <a class="nav-link active" href="#">
                    <?php } else {?>
                        <a class="nav-link" href="<?=base_url('user/daftar')?>">
                    <?php }?>
                        <strong>
                            User
                        </strong>
                    </a>
                </li>
                <li class="nav-item-child">
                    <?php if($active == 'anggota-baru'){ ?>
                        <a class="nav-link active" href="#">
                    <?php } else {?>
                        <a class="nav-link" href="<?=base_url('anggota/daftar')?>">
                    <?php }?>
                        <strong>
                            Anggota
                        </strong>
                    </a>
                </li>
            </ul>
        </div>
    </ul>
</div>
<script>
    $(document).ready(function(){
        startTime();
        let page = '<?=$active?>';
        if(page == 'anggota-baru' || page == 'user-baru'){
            $('#div-nav-item-child').show();
        }
    });

    function startTime() {
        var today = new Date();
        var D = getDayWord(today.getDay());
        var month = getMonthWord(today.getMonth());
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        $('#real-time').html(h + ":" + m + ":" + s);
        // document.getElementById('real-time').innerHTML = 
        var t = setTimeout(startTime, 500);
    }

    function getDayWord(i){
        var j = new Array( "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        return j[i];
    }

    function getMonthWord(i){
        var j = new Array('Janunari', 'Februari',
        'Maret', 'April', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember');
        return j[i];
    }
    
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }


    $(document).on('click', '#daftar-baru', function(){
        $('#div-nav-item-child').slideToggle('fast');
    });

    $(document).on('click', '.user-profile', function(){
        $('#user-profile-menu').animate({
            height: 'show'
        });
        $('#id-user-profile').removeClass('user-profile').addClass('user-profile-opened');
    });

    $(document).on('click', '.user-profile-opened', function(){
        $('#user-profile-menu').animate({
            height: 'hide'
        });
        $('#id-user-profile').removeClass('user-profile-opened').addClass('user-profile');
    });
</script>