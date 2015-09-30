<?php
  $feed_info = array(
    'ip' => array(
      'page_title' => 'Blocked IP Addresses',
      'menu_title' => 'Blocked IPs',
      'columns' => array(
        'ip' => array(
          'title' => "IP Address"
        ),
        'blocked_at' => array(
          'title' => "Last Blocked",
          'format' => array("format_date" => "Y/d/m H:i:s")
        ),
        'ticket_id' => array(
          'title' => "IST Tracking"
        ),
      )
    ),
    'mac' => array(
      'page_title' => 'Blocked DHCP MAC addresses',
      'menu_title' => 'Blocked MACs',
      'columns' => array(
        'mac' => array(
          'title' => "MAC Address"
        ),
        'blocked_at' => array(
          'title' => "Last Blocked",
          'format' => array("format_date" => "Y/d/m H:i:s")
        ),
        'ticket_id' => array(
          'title' => "IST Tracking"
        ),
      )

    ),
    'uid' => array(
      'page_title' => 'Blocked CalNetUIDs for Airbears/VPN service',
      'menu_title' => 'Blocked UIDs',
      'columns' => array(
        'calnet_uid' => array(
          'title' => "Service"
        ),
        'service' => array(
          'title' => "CalNet UID"
        ),
        'is_dmca' => array(
          'title' => "DMCA?",
          'format' => array('true_false' => "")
        ),
        'blocked_at' => array(
          'title' => "Last Blocked",
          'format' => array("format_date" => "Y/d/m H:i:s")
        ),
        'ticket_id' => array(
          'title' => "IST Tracking"
        ),
      )

    )
  );
  variable_set('ucberkeley_security_feeds_info', $feed_info);
