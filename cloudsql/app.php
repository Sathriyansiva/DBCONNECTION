<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

# [START example]
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// create the Silex application
$app = new Application();

$app['pdo'] = function ($app) {
    $pdo = new PDO(
        $app['mysql.dsn'],
        $app['mysql.user'],
        $app['mysql.password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $pdo;
};

$app->get('/', function (Application $app, Request $request) {
    $ip = $request->GetClientIp();
    // Keep only the first two octets of the IP address.
    $octets = explode($separator = ':', $ip);
    if (count($octets) < 2) {  // Must be ip4 address
        $octets = explode($separator = '.', $ip);
    }
    if (count($octets) < 2) {
        $octets = ['bad', 'ip'];  // IP address will be recorded as bad.ip.
    }
    // Replace empty chunks with zeros.
    $octets = array_map(function ($x) {
        return $x == '' ? '0' : $x;
    }, $octets);
    $user_ip = $octets[0] . $separator . $octets[1];

    // Insert a visit into the database.
    /** @var PDO $pdo */
    $pdo = $app['pdo'];
   
$format = strtolower($_GET['format']) == 'json'; //xml is the default
    // Look up the last 10 visits
	$ibo ="1000";
	$receiveribo="1002";
	
    $select = $pdo->prepare(
        'SELECT * FROM chat WHERE (sender_ibo =:ibo1 and receiver_ibo =:receiveribo1) or  
	(sender_ibo=:receiveribo1 and receiver_ibo=:ibo1)');
    $select->execute(array(':ibo1'=>$ibo,':receiveribo1'=>$receiveribo));
    $visits = [""];
    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
     $image= $row['image'];
		$senderibo =$row['sender_ibo'];
	        $receiveribo=$row['receiver_ibo'];
		$message= $row['message'];
		echo $ibo;
		
	$posts[] = array('image'=>$image,'message' => $message,'senderibo'=>$senderibo,'receiveribo'=>$receiveribo);
		
    }
     
	
	if($format == 'json') {
    header('Content-type: application/json');
    echo json_encode(array('posts'=>$posts));
  }
  else {
    header('Content-type: text/xml');
    echo '';
    foreach($posts as $index => $post) {
      if(is_array($post)) {
        foreach($post as $key => $value) {
          echo '<',$key,'>';
          if(is_array($value)) {
            foreach($value as $tag => $val) {
              echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
            }
          }
          echo '</',$key,'>';
        }
      }
    }
    echo '';
  }
	return new Response(implode("\n", $visits), 200,
        ['Content-Type' => 'text/plain']);
});
# [END example]

return $app;
