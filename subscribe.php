<?php

/*
CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `steam_id` varchar(100) NOT NULL,
  `steam_data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 
*/

require 'config.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$result_data = [
    'status' => null
];


// Insert the data for this subscriber
try
{
    $insert_result = Capsule::insert(
        "
        INSERT INTO
            `subscribers`
        (
            `email`
        )
        VALUES
        (
            ?
        )
        "
        , [
            $_POST['email']
        ]
    );

    $mandrill = new Mandrill('g2ofKjl3xMp6Nnq5QTudgQ');

    $message = array(
        'html' => nl2br(file_get_contents('templates/email_subscribed.html')),
        'text' => file_get_contents('templates/email_subscribed.html'),
        'subject' => 'You are on the waiting list for Imba Game Deals',
        'from_email' => 'daniel@imbagamedeals.com',
        'from_name' => 'Daniel Luca',
        'to' => array(
            array(
                'email' => $_POST['email'],
                'name' => '',
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'daniel@imbagamedeals.com'),
        'important' => false,
        'track_opens' => true,
        'track_clicks' => true,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'tracking_domain' => null,
        'signing_domain' => null,
        'google_analytics_domains' => array('imbagamedeals.com'),
        'google_analytics_campaign' => 'email_subscribed',
        'metadata' => array('website' => 'imbagamedeals.com'),
    );
    $async = false;
    $result = $mandrill->messages->send($message, $async);
    print_r($result);
}
catch (Exception $e)
{
    $insert_result = false;
}

if ($insert_result)
{
    $result_data['status'] = 'ok';
}
else
{
    $result_data['status'] = 'error';
}

echo json_encode($result_data);
