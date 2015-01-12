<?php

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
