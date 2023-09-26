<?php
    include('Mobile_Detect.php');
    include('BrowserDetection.php');
    
    $browser=new Wolfcast\BrowserDetection;
    $ipaddress = getenv("REMOTE_ADDR") ;
    $browser_name=$browser->getName();
    $browser_version=$browser->getVersion();
    
    $detect=new Mobile_Detect();
    
    if($detect->isMobile()){
    	$type='Mobile';
    }elseif($detect->isTablet()){
    	$type='Tablet';
    }else{
    	$type='PC';
    }
    
    if($detect->isiOS()){
    	$os='IOS';
    }elseif($detect->isAndroidOS()){
    	$os='Android';
    }else{
    	$os='Window';
    }
    
    $url=(isset($_SERVER['HTTPS'])) ? "https":"http";
    $url.="//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $prev_page='';

    if(isset($_SERVER['HTTP_REFERER'])){
    	$prev_page=$_SERVER['HTTP_REFERER'];
    }
    
    if(!isset($_COOKIE['visit'])){
    	setCookie('visit','yes',time()+(60*60*24*30*12*10));
        $check_web_analytics=mysqli_query($con,"select * from web_user_analytics where ip_address='$ipaddress' and browser='$browser_name' and device='$type' and device_os='$os'");
        if(!mysqli_num_rows($check_web_analytics)>0){
            mysqli_query($con,"INSERT INTO web_user_analytics(ip_address,browser,device,device_os) VALUES('$ipaddress','$browser_name','$type','$os')");
        }
    }

    if(!isset($_COOKIE['cookies_permission'])){
        // echo "no cookie permission";
        if(isset($_POST['cookies_permission'])){
            setCookie('cookies_permission','yes',time()+(60*60*24*30*12));
            header('location:'.$prev_page.'');
        }
        ?>
        <div class="cookies_permission fixed-bottom bg-primary text-light pt-5 px-5 pb-3">
            <div class="container">
                <form method="post" class="row">
                    <div class="col-md m-0 d-flex flex-column">
                        <h4>Accept all cookies</h4>
                        <p>accepting all cookies means your accepting our terms and conditions and agreeing to share your information</p>
                    </div>
                    <div class="col-md d-flex align-items-center justify-content-center">
                        <button class=" btn btn-sm btn-light text-primary" name="cookies_permission" type="submit">Accept all cookies</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
?>