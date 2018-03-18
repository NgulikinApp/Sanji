<?php
    /*
        Function referred on : user.php
        Used for returning the detail data user
        Return data:
                - fullname
                - dob
                - username
                - username
                - gender
                - phone
                - email
                - user_photo
    */
    function user($stmt){
        $data = array();
        
        $stmt->execute();
    
        $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
        
        $stmt->fetch();
        
        if($col7 != "no-photo.jpg"){
            $user_photo = 'http://'.IMAGES_URL.'/'.$col3.'/'.$col7;
        }else{
            $user_photo = "http://".INIT_URL."/img/".$col7;
        }
        
        $data['fullname'] = $col1;
        $data['dob'] = $col2;
        $data['username'] = $col3;
        $data['gender'] = $col4;
        $data['phone'] = $col5;
        $data['email'] = $col6;
        $data['user_photo'] = $user_photo;
        
        $stmt->close();
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
    
    /*
        Function referred on : updateUser.php
        Used for returning the detail data user
        Return data:
                - user_id
                - fullname
                - dob
                - gender
                - phone
                - user_photo
    */
    function updateUser($user_id,$fullname,$dob,$gender,$phone,$user_photo){
        $data = array();
        
        $data['user_id'] = $user_id;
        $data['fullname'] = $fullname;
        $data['dob'] = $dob;
        $data['gender'] = $gender;
        $data['phone'] = $phone;
        $data['user_photo'] = $user_photo;
        
        /*
            Function location in : /model/general/functions.php
        */
        credentialVerified((object)$data);
    }
?>