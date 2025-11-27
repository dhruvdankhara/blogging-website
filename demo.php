<?php



try {
    // Server settings
    // TCP port to connect to

    // Sender information
    $mail->setFrom('utsavparmar72@gmail.com');

    // Recipient information
    $mail->addAddress('kreract@gmail.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Subject of your email';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the plain text message body for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
