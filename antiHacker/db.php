<?php
include "001.php";
include("002.php");
include("dados.php");

$h = $host;
$p = $port;
$sp = new SendPackets();
$sp->opCode = 352;
$sp->wInt32(1);
$sp->wInt32(1);
$sp->wInt32(1);
$sp->wOctet(1);
$sp->Send($h, $p, true, true);
$rp = new ReadPackets($sp->buf);
$GMListOnlineUser_Re = array(
	"opcode" => "cuint",
	"len" => "cuint",
	"retcode" => "int32",
	"gmroleid" => "int32",
	"localsid" => "int32",
	"handler" => "int32",
	"count" => "cuint"
);
$GMPlayerInfo = array(
	"userid" => "int32",
	"roleid" => "int32",
	"linkid" => "int32",
	"localsid" => "int32",
	"gsid" => "int32",
	"status" => "byte",
	"name" => "string",
	
);
$info = $rp->rByStruct($GMListOnlineUser_Re);
$ponline = array();
for ($a = 0; $a < $info["count"]; $a++) {
	$ponline[] = $rp->rByStruct($GMPlayerInfo);
}
?>

<?php

foreach($ponline as $value) {
	//echo $value["userid"];
	
	$DBGetConsumeInfos = new WritePacket();
	$DBGetConsumeInfos -> WriteUInt32(-1); // always
	$DBGetConsumeInfos -> WriteCUInt32(1); // count of array
	$DBGetConsumeInfos -> WriteUInt32($value["roleid"]); // roleid
	$DBGetConsumeInfos -> Pack(0x180);
 
	if (!$DBGetConsumeInfos -> Send("localhost", 29400))
    		return -1;
 
	$DBGetConsumeInfos_Re = new ReadPacket($DBGetConsumeInfos);
	$DBGetConsumeInfos_Re -> ReadPacketInfo();
	$DBGetConsumeInfos_Re -> ReadUInt32(); // always
	$DBGetConsumeInfos_Re -> ReadUInt32(); // retcode
	$DBGetConsumeInfos_Re -> ReadCUInt32(); // count of array
	$DBGetConsumeInfos_Re -> ReadUInt32(); // roleid
	$DBGetConsumeInfos_Re -> ReadUInt32(); // level	
	$loginip = $DBGetConsumeInfos_Re -> ReadUInt32();
	$ip[0] = $loginip & 0xFF;
	$ip[1] = ($loginip >> 8) & 0xFF;
	$ip[2] = ($loginip >> 16) & 0xFF;
	$ip[3] = ($loginip >> 24) & 0xFF;
	
	$ipComp = "{$ip[0]}.{$ip[1]}.{$ip[2]}.{$ip[3]}";

	 
$sn = $servername;
$un = $username;
$pas = $password;
$dbn = $dbname;

// Create connection
$conn = mysqli_connect($sn, $un, $pas, $dbn);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$usuado_id = $value["userid"];
$role_id =$value["roleid"];
$link = $value["linkid"];
$local_sid =$value["localsid"];
$gs =$value["gsid"];
$statu = $value["status"];
$nome = $value["name"];
$sql = " REPLACE INTO players(userid, roleid, linkid, localsid, gsid, status, name, ipUser) VALUES ('$usuado_id', '$role_id', '$link', '$local_sid', '$gs','$statu','$nome','$ipComp')";
	

if (!mysqli_query($conn, $sql)) {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
	 
	
}
?>

