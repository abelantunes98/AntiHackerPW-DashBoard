<?php
// MateusTeex

Class SendPackets {
	
	public $data, $opCode, $buf;
	
	Function CUint($data) {
		if($data < 64)
			return StrRev(PACK("C", $data));
		else if($data < 16384)
			return StrRev(PACK("S", ($data | 0x8000)));
		else if($data < 536870912)
			return StrRev(PACK("I", ($data | 0xC0000000)));
		return StrRev(PACK("c", -32) . PACK("I", $data));
	}
	
	Function wCUint($data) {
		$this->data .= $this->CUint($data);
	}
	
	Function wInt32($data) {
		$this->data .= pack("N*", $data);
	}
	
	Function wByte($data) {
		$this->data .= pack("C", $data);
	}
	
	Function wOctet($data) {
		if (ctype_xdigit($data)) {
			$data = pack("H*", $data);
		}
		$this->data .= $this->CUint(strlen($data)).$data;
	}
	
	Function wString($data) {
		$data = iconv("UTF-8", "UTF-16LE", $data);
		$this->data .= $this->CUint(strlen($data)).$data;
	}
	
	Function wFloat($data) {
		$this->data .= pack("f", $data);
	}
	
	Function Send($ip, $port, $reciveData, $empty) {
		$data = $this->CUint($this->opCode).$this->CUint(strlen($this->data)).$this->data;
		$sock=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($sock, $ip, $port);
		socket_set_block($sock);
		if ($empty) {
			socket_recv($sock, $empty, 32768, 0);
		}
		socket_send($sock, $data, 32768, 0);
		if ($reciveData) {
			socket_recv($sock, $this->buf, 32768, 0);
		}
		socket_set_nonblock($sock);
		socket_close($sock);
	}
	
}

Class ReadPackets {
	
	public $data, $pos = 0;
	
	Function __construct($data) {
		$this->data = $data;
	}
	
	Function rByStruct($struct) {
		foreach($struct as $key => $value) {
			switch($struct[$key]) {
				case "int32":
					$struct[$key] = $this->rInt32();
					break;
				case "string":
					$struct[$key] = $this->rString();
					break;
				case "octet":
					$struct[$key] = $this->rOctet();
					break;
				case "bytes":
					$struct[$key] = $this->rBytes($struct[$a]["len"]);
					break;
				case "float":
					$struct[$key] = $this->rFloat();
					break;
				case "byte":
					$struct[$key] = $this->rByte();
					break;
				case "cuint":
					$struct[$key] = $this->rCUint();
					break;
			}
		}
		return $struct;
	}
	
	Function rCUint() {
		$int = unpack("C", substr($this->data, $this->pos, 1));
		$this->pos++;
		switch($int[1] & 224) {
			case 224:
				$a = unpack("N", substr($this->data, $this->pos, 4));// Int
				$this->pos += 4;
				return $a[1];
				break;
			case 192:
				$a = unpack("N", substr($this->data, $this->pos - 1, 4));// Int
				$this->pos += 4;
				return $a[1] & 1073741823;
				break;
			case 128:
			case 160:
				$a = unpack("n", substr($this->data, $this->pos - 1, 2));// Short
				$this->pos++;
				return $a[1] & 32767;
				break;
			default:
				return $int[1];
		}
	}
	
	Function rInt32() {
		$int = unpack("N", substr($this->data, $this->pos));
		$this->pos += 4;
		return $int[1];
	}
	
	Function rString() {
		$l = $this->rCUint();
		$str = iconv("UTF-16", "UTF-8", substr($this->data, $this->pos, $l));
		$this->pos += $l;
		return $str;
	}
	
	Function rOctet() {
		$l = $this->rCUint();
		$str = unpack("H*", substr($this->data, $this->pos, $l));
		$this->pos += $l;
		return $str[1];
	}
	
	Function rBytes($l) {
		$b = substr($this->data, $this->pos, $l);
		$this->pos += $l;
		return $b;
	}
	
	Function rByte() {
		$b = unpack("C", substr($this->data, $this->pos, 1));
		$this->pos++;
		return $b[1];
	}
	
	Function rFloat() {
		$f = unpack("f", substr($this->data, $this->pos, 4));
		$this->pos += 4;
		return $f[1];
	}

}

?>