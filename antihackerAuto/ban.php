<?php

include "dadosBan.php";

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

$type = 98;
$time1 = $timeBan;
$time2 = $mult;
$roleid = $idUser;
$reason = $motivo;
$time1= ($time1*$time2);
 
$Packet = pack("N*",-1).pack("C*", 1).pack("N*", -1).pack("N*", 0).pack("N*", $roleid).pack("N*", $time1).PackString($reason);
$Data = cuint(8004).cuint(strlen($Packet)).$Packet;		
		
SocketSender($Data, $host="127.0.0.1", $port = "29100");

?>
