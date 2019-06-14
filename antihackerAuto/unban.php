<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>UnBanned Accounts by WSOK</title>
</head>
<body style='text-align: center;'>
<?php
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


echo "<form method='post'><input type='number' style='width:100px' min='1' name='uid' required placeholder='ID ACCOUNT'><input type='submit' value='unban'></form>";
if (isset($_POST['uid']))
{
$Socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	
		if(socket_connect($Socket,"127.0.0.1","29400"))
	{
	    socket_set_block($Socket);

	   	$Packet = pack("N*",-1).pack("C*", 2).pack("N*", -1).pack("N*", 0).pack("N*", $_POST['uid']).pack("N*", 1).PackString(0);
        $Data = cuint(8004).cuint(strlen($Packet)).$Packet;	


		$send = socket_send($Socket,$Data,8192,0);
	    $recv = socket_recv($Socket,$buf,8192,0);  
	    socket_set_nonblock($Socket);
	    socket_close($Socket);
	}

	echo"Аккаунт ".$_POST['uid']." успешно разбанен!<br>";
}else{
echo"Пожалуйста укажите ID игрового аккаунта.";
}
echo"<hr>разработка от wsok.net (webserverok.com).";