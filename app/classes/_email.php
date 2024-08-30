<?php

/* 
    Clase propia del framework para enviar mails predefinidos 2.0
*/

class _email

{
    private $headers        = "From:%s\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\n";
    private $email_soporte  = email_soporte;
    private $site_title     = '';
    private $site_logo      = '';

    public static function send_validate($to, $subject, $validate_code = null)
    {
        $self = new self();

        $template = "send_code";
        $self->headers = sprintf($self->headers, $self->email_soporte);

        $content = file_get_contents(FOLDER_EMAIL . $template . '.php');
        $content = sprintf($content, $self->site_title, $self->site_logo, $validate_code);

        return (mail($to, $subject, $content, $self->headers)) ? true : false;
    }

    public static function send_pass($to, $subject, $password = null)
    {
        $self = new self();

        $template = "send_pass";
        $self->headers = sprintf($self->headers, $self->email_soporte);

        $content = file_get_contents(FOLDER_EMAIL . $template . '.php');
        $content = sprintf($content, $self->site_title, $self->site_logo, $password);

        return (mail($to, $subject, $content, $self->headers)) ? true : false;
    }
}
