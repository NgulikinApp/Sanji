<div class="w-full p-t-15 p-b-15 p-l-25 p-r-25">
    <div class="p-b-15 border-bottom">
        <span class="text-up">dashboard admin</span>
        <span class="default-color p-l-15 p-r-15">></span>
        <span class="default-color text-up">Pengaturan</span>
        <span class="default-color p-l-15 p-r-15">></span>
        <span class="default-color text-capital">notifikasi</span>
    </div>
</div>
<div class="p-l-25">
    <div class="bgwhite container-notif100 p-t-15 p-b-15 p-l-10 p-r-10">
        <div class="text-up fn-lightblue">
            <div class="dis-inline-block bck-grey brdr-solid-grey notif-menu hov-pointer text-center p-t-5 p-b-5">marketing</div>
            <div class="dis-inline-block brdr-solid-grey notif-menu hov-pointer text-center p-t-5 p-b-5">pengaturan</div>
            <div class="dis-inline-block brdr-solid-grey notif-menu hov-pointer text-center p-t-5 p-b-5">pengumuman</div>
            <div class="dis-inline-block brdr-solid-grey notif-menu hov-pointer text-center p-t-5 p-b-5">promo</div>
        </div>
        <div class="p-t-20 p-b-20">
            <div class="dis-inline-block default-color container-notif100-left">Deskripsi Toko</div>
            <div class="dis-inline-block container-notif100-right">
                <textarea class="w-full brdr-solid-grey inpt-notif-desc p-t-5 p-b-5 p-l-5 p-r-5"></textarea>
            </div>
        </div>
        <div>
            <div class="dis-inline-block container-notif100-left default-color">Logo Toko</div>
            <div class="dis-inline-block container-notif100-right">
                <label for="file-notif" class="hov-pointer">
                    <img id="img-notif" src="/img/no-image.jpg" width="150" height="150" />
                </label>
                <input type="file" id="file-notif" name="file[]"/>
            </div>
        </div>
        <div class="p-t-15 p-b-20 brdr-grey">
            <div class="dis-inline-block container-notif100-left default-color">User</div>
            <div class="dis-inline-block container-notif100-right">
                <select class="select-notif-user brdr-solid-grey">
                    <option value="0">All user</option>
                    <option value="1">Users seller</option>
                    <option value="2">Users premium</option>
                </select>
            </div>
        </div>
        <div class="p-t-10 p-b-0">
            <div class="dis-inline-block container-notif100-left"></div>
            <div class="dis-inline-block container-notif100-right">
                <button class="btn-notif text-white p-t-3 p-b-3 p-l-50 p-r-50 float-r">Kirim</button>
            </div>
        </div>
    </div>
</div>