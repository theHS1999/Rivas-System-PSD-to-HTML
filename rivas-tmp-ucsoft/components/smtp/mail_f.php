<?php

require_once 'Mail.php';
require_once 'Mail/mime.php';

define( "MAIL_FROM", "passwdreset@ucsoft.ir" );
define( "SMTP_USERNAME", "passwdreset@ucsoft.ir" );
define( "SMTP_PASSWORD", "147963!@#" );
define( "SMTP_HOST", "localhost" );

function send_mail( $subject, $html, $to, $hdrs = array() )
{

    if ( !isset( $hdrs['To'] ) )
    {
        $hdrs['To'] = is_null( $to ) ? '' : $to;
    }
    if ( !isset( $hdrs['Subject'] ) )
    {
        $hdrs['Subject'] = is_null( $subject ) ? '' : $subject;
    }
    if ( !isset( $hdrs['From'] ) )
    {
        $hdrs['From'] = MAIL_FROM;
    }

    $mime = new Mail_mime( "\n" );
    $mime->setHTMLBody( $html );

    $mimeparams = array();
    $mimeparams['text_encoding'] = "7bit";
    $mimeparams['text_charset'] = "UTF-8";
    $mimeparams['html_charset'] = "UTF-8";
    $mimeparams['head_charset'] = "UTF-8";

    $body = $mime->get( $mimeparams );
    $hdrs = $mime->headers( $hdrs );

    $params['host'] = SMTP_HOST;
    $params['port'] = "25";
    $params['auth'] = true;
    $params['username'] = SMTP_USERNAME;
    $params['password'] = SMTP_PASSWORD;


    $mail = &Mail::factory( 'smtp', $params );
    $mail->send( $to, $hdrs, $body );

}

?>