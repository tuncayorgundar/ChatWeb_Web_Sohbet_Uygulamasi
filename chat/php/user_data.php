<?php
while($row = mysqli_fetch_assoc($query)){

    $sql2 = "SELECT * from messages where (incoming_msg_id = {$row['user_id']}
    or outgoing_msg_id = {$row['user_id']}) and (outgoing_msg_id = {$outgoing_id}
    or incoming_msg_id = {$outgoing_id}) order by msg_id desc limit 1";

    $query2 = mysqli_query($conn,$sql2);
    $row2 = mysqli_fetch_assoc($query2);

    (mysqli_num_rows($query2) > 0 ) ? $result = $row2['msg'] : $result = "No message available";
    //if user havent chat each other
    (strlen($result) > 28) ? $msg = substr($result,0,28) . '....' : $msg = $result;
    //if user chat is greater than 20

    if(mysqli_num_rows($query2) != 0){
        if($row2['msg'] == '' && $row2['msg_img'] != ''){ // if user chat is image
            $msg = 'image';
        }
    }

    if(isset($row2['outgoing_msg_id'])){
        ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = '';
    }else{
        $you = '';
    }
    ($row['status'] == "Offline Now") ? $offline = "offline" : $offline = "";
    ($outgoing_id == $row['user_id']) ? $shid_me = "hide" : $hid_me = "";
    
    $output .= '<a href="chat.php?user_id='.$row['user_id'].'">
    <div class="content">
        <img src="uploaded_img/'.$row['img'].'" alt="">
        <div class="details">
            <span>'.$row['name'].'</span>
            <p>'. $you . $msg .'</p>
        </div>
    </div>
    <div class="status-dot '.$offline.'"></div>
    </a>';
}

?>