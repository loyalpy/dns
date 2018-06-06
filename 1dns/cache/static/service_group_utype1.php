<?php
return array (
  'free' => 
  array (
    'service_group' => 'free',
    'name' => '免费版',
    'cost1' => '0.00',
    'ns_group' => 'free',
    'utype' => '1',
    'sort' => '0',
    'data' => 
    array (
      'loadBalanc' => '5',
      'QPS' => '10000',
      'SLA' => '99',
      'DDOS' => '0',
      'monitorTask' => '1',
    ),
  ),
  'vip1' => 
  array (
    'service_group' => 'vip1',
    'name' => '标准版',
    'cost1' => '38.00',
    'ns_group' => 'vip11',
    'utype' => '1',
    'sort' => '1',
    'data' => 
    array (
      'loadBalanc' => '20',
      'QPS' => '50000',
      'SLA' => '99',
      'DDOS' => '30',
      'monitorTask' => '10',
    ),
  ),
  'vip2' => 
  array (
    'service_group' => 'vip2',
    'name' => '豪华版',
    'cost1' => '188.00',
    'ns_group' => 'vip21',
    'utype' => '1',
    'sort' => '2',
    'data' => 
    array (
      'loadBalanc' => '30',
      'QPS' => '100000',
      'SLA' => '99',
      'DDOS' => '80',
      'monitorTask' => '3',
    ),
  ),
  'vip3' => 
  array (
    'service_group' => 'vip3',
    'name' => '旗舰版',
    'cost1' => '1680.00',
    'ns_group' => 'vip31',
    'utype' => '1',
    'sort' => '3',
    'data' => 
    array (
      'loadBalanc' => '50',
      'QPS' => '200000',
      'SLA' => '99.99',
      'DDOS' => '200',
      'monitorTask' => '20',
    ),
  ),
  'vip9' => 
  array (
    'service_group' => 'vip9',
    'name' => '高防版',
    'cost1' => '2980.00',
    'ns_group' => 'vip91',
    'utype' => '1',
    'sort' => '9',
    'data' => 
    array (
      'loadBalanc' => '100',
      'QPS' => '300000',
      'SLA' => '100',
      'DDOS' => '300',
      'monitorTask' => '30',
    ),
  ),
);
?>