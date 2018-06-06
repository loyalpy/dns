<?php
// IPv4 class
class cls_ipv4{
  var $address;
  var $netbits;
  var $endaddress;
   //--------------
  // Create new class
  function cls_ipv4($address,$endaddress,$netbits=1){
    $this->address = $address;
    $this->netbits = $netbits;
    $this->endaddress = $endaddress;
  }
   //--------------
  // Return the IP address
  function address() { return ($this->address); }
   //--------------
  // Return the netbits
  function netbits() { return ($this->netbits); }
   //--------------
  // Return the netmask
  function netmask(){
    return (long2ip(ip2long("255.255.255.255") 
           << (32-$this->netbits)));
  }
  // Return the network that the address sits in
  function network(){
    return (long2ip((ip2long($this->address))
           & (ip2long($this->netmask()))));
  }
   //--------------
  // Return the broadcast that the address sits in
  function broadcast(){
    return (long2ip(ip2long($this->network())
           | (~(ip2long($this->netmask())))));
  }
   //--------------
  // Return the inverse mask of the netmask
  function inverse(){
    return (long2ip(~(ip2long("255.255.255.255")
           << (32-$this->netbits))));
  }
  //Return the 
  function inverse2(){
  	$this->netbits = (32-strlen(decbin(ip2long($this->endaddress) - ip2long($this->address)).""));
  	return $this->address."/".$this->netbits; 
  }
}
?>