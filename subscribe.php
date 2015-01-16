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

    $mail = new PHPMailer;
    $mail->SMTPDebug = 3;

    $mail->isSMTP();
    $mail->Host = 'imbagamedeals.com';
    $mail->Port = 25;

    $mail->From = 'daniel@imbagamedeals.com';
    $mail->FromName = 'Daniel';
    $mail->addAddress('lucadanielcostin@gmail.com');     // Add a recipient
    $mail->addReplyTo('daniel@imbagamedeals.com', 'Daniel Luca');

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send())
    {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    else
    {
        echo 'Message has been sent';
    }
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
