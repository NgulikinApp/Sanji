<?php
    session_start();
    if(isset($_SESSION['user_admin'])){
        header("Location: https://www.admin.ngulikin.com/dashboard");
    }
    include 'web/system/minify.php';
?>
<html>
    <?php include 'web/library.php';?>
    <script src="js/module-login.js?jsr=<?php echo $jsversionstring; ?>"></script>
    <body>
        <?php
            include $_SERVER['DOCUMENT_ROOT'].'/web/loaderPopup.php';
    	?>
    	<div class="limiter w-full">
    	    <div class="container-login100 pos-relative w-full flex-c-m flex-w z-1 p-t-15 p-b-15 p-l-15 p-r-15">
    	        <div class="wrap-login100 p-t-60 p-b-170">
    	            <div class="login100-form-avatar wrap-pic-cir limiter icon-login100">
    	                <img class="icon-login100" src="img/icon.png" alt="avatar"/>
    	            </div>
    	            <span class="login100-form-title fs-24 p-b-15 text-center w-full dis-block lh-1-2">ngulikin</span>
    	            <div class="z-1 pos-relative m-b-10 w-full">
    	                <input class="input100 w-full dis-block p-t-0 p-r-30 p-b-0 p-l-53 bo-cur bo-hidden lh-1-2" type="text" id="login100-username" placeholder="Username or email"/>
    	                <span class="focus-input100 dis-block ab-b-l sizefull bo-cur trans-0-4"></span>
    	                <span class="symbol-input100 fs-15 sizefull ab-b-l p-l-30 flex-m bo-cur trans-0-4">
    	                    <i class="fa fa-user"></i>
    	                </span>
    	            </div>
    	            <div class="z-1 pos-relative m-b-10 w-full">
    	                <input class="input100 w-full dis-block p-t-0 p-r-30 p-b-0 p-l-53 bo-cur bo-hidden lh-1-2" type="password" id="login100-pass" placeholder="Password"/>
    	                <span class="focus-input100 dis-block ab-b-l sizefull bo-cur trans-0-4"></span>
    	                <span class="symbol-input100 fs-15 sizefull ab-b-l p-l-30 flex-m bo-cur trans-0-4">
    	                    <i class="fa fa-lock"></i>
    	                </span>
    	            </div>
    	            <span class="login100-form-alert text-center w-full dis-block w-full"></span>
    	            <div class="container-login100-form-btn flex-c-m flex-w p-t-10">
    	                <button class="login100-form-btn fs-15 w-full flex-c-m pos-relative z-1 bo-cur bo-hidden p-t-0 p-b-0 p-l-25 p-r-25 lh-1-5 trans-0-4">Login</button>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </body>
</html>