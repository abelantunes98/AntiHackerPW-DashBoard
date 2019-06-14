<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Веб-панель банов Perfect World (C) webserverok.com</title>
</head>
<body style='text-align: left;'>
<?php

function SocketSender($Data, $host, $port){
	$Socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
		if(socket_connect($Socket,$host,$port))
	{
	    socket_set_block($Socket);
		$send = socket_send($Socket,$Data,8192,0);
	    $recv = socket_recv($Socket,$buf,8192,0);  
	    socket_set_nonblock($Socket);
	    socket_close($Socket);
	} else { getErrorMessage(7,'die','feedback'); }
	
}	
function cuint($data)
{
    if($data < 64)
        return strrev(pack("C", $data));
    else if($data < 16384)
        return strrev(pack("S", ($data | 0x8000)));
    else if($data < 536870912)
        return strrev(pack("I", ($data | 0xC0000000)));
    return strrev(pack("c", -32) . pack("i", $data));
}
    function PackString($data)
    {
        $data = iconv("UTF-8", "UTF-16LE", $data);
        return cuint(strlen($data)).$data;
    }

echo "<fieldset><legend>PW-BANNED WEBPANEL v1.2 beta by wsok</legend><form method='post'>
    <select name='type' style='width: 65%; height: 35px; font-size:20px;'><option disabled selected value=''>Выберите тип бана</option><option value='106'>БАН ИГРОВОГО ЧАТА [УКАЗАТЬ ID ПЕРСОНАЖА]</option><option value='104'>БАН ИГРОВОГО ПЕРСОНАЖА [УКАЗАТЬ ID ПЕРСОНАЖА]</option><option value='98'>БАН ИГРОВОГО АККАУНТА [УКАЗАТЬ ID АККАУНТА]</option></select><br><br>
<input type='number' style='width: 20%; height: 35px; font-size:20px;' min='1' name='roleid' required placeholder='ID'><br>

    <br><input type='number' style='width: 20%; height: 35px; font-size:20px;' min='1' name='time1' required placeholder='Tempo�'><br><br>
    <select name='time2' style='width: 20%; height: 35px; font-size:20px;'><option value='1'>СЕКУНДЫ</option><option value='60'>МИНУТЫ</option><option value='3600'>ЧАСЫ</option><option value='86400'>ДНИ</option>
        <option value='604800'>НЕДЕЛИ</option><option value='2592000'>МЕСЯЦЫ</option><option value='31536000'>ГОДЫ</option></select><br><br>
    <input type='text' style='width: 65%; height: 35px; font-size:20px;' required placeholder='Причина [ не более 50 символов ]' name='reason' MAXLENGTH='50'><br><br>
    <input type='submit' value='ВЫДАТЬ БАН' style='width: 65%; height: 35px; font-size:20px;'></form></fieldset>";

if (isset($_POST['roleid']))
{
    if (in_array($_POST['type'],array(104,106,98))) // 104 = БП, 98 = БА, 106 = БЧ.
    {
$time1=$_POST['time1']*$_POST['time2'];
 
	 if($_POST['type'] != 98){
		$Data = pack("N*", -1).pack("N*", -1).pack("N*",$_POST['roleid']).pack("N*", $time1).PackString($_POST['reason']);
		$Data = pack("C",129).pack("C",$_POST['type']).cuint(strlen($Data)).$Data;
		}else{
		$Packet = pack("N*",-1).pack("C*", 1).pack("N*", -1).pack("N*", 0).pack("N*", $_POST['roleid']).pack("N*", $time1).PackString($_POST['reason']);
        $Data = cuint(8004).cuint(strlen($Packet)).$Packet;		
		}
	         SocketSender($Data, $host="127.0.0.1", $port = "29100");
        if ($_POST['type'] == 104) { 
		echo "Персонажу ID <b>{$_POST['roleid']}</b> выдан бан на <b>{$time1}</b> секунд";
		} 
		elseif($_POST['type'] == 106) {
			echo "Персонажу ID <b>{$_POST['roleid']}</b> выдан бан чата на <b>{$time1}</b> секунд";
		}
		elseif($_POST['type'] == 98){
		echo "Аккаунту ID <b>{$_POST['roleid']}</b> выдан бан на <b>{$time1}</b> секунд";
		}
		      
    }
    else echo "Укажите тип бана!";
}
echo "<br>
<hr>Веб-панель банов Perfect World (C) webserverok.com<br>";


echo "</body></html>";
