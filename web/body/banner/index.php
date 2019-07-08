<?php
    session_start();
    if(!isset($_SESSION['user_admin'])){
        header("Location: .");
    }
?>
<html>
    <?php include 'web/library.php';?>
    <body>
        <div class="limiter w-full">
            <?php
                include $_SERVER['DOCUMENT_ROOT'].'/web/nav/headerMenu.php';
    		?>
    		<div class="container-content100 pos-relative">
    		    <section>
        		    <div class="container-left100 sizefull dis-inline-block text-top">
        		        <?php
            		        include $_SERVER['DOCUMENT_ROOT'].'/web/nav/mainMenu.php';
        		        ?>
        		    </div>
        		    <div class="container-content100-right container-right100 sizefull dis-inline-block">
        		        <?php
            		        include 'section_body.php';
        		        ?>
        		    </div>
        		</section>
    		</div>
		</div>
		<script src="js/module-homepagebanner.js?jsr=<?php echo $jsversionstring; ?>"></script>
    </body>
</html>