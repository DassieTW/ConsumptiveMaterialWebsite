<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Mail;
class MailController extends Controller {
   public function basic_email() {
    $data = array('name'=>"Virat Gandhi");

    Mail::send(['text'=>'mail'], $data, function($message) {
       $message->to('t105590005@ntut.org.tw', 'Tutorials Point')->subject
          ('Laravel Basic Testing Mail');
       $message->from('xyz@gmail.com','Virat Gandhi');
    });
    echo "Basic Email Sent. Check your inbox.";
   }
//    public function html_email() {
//       $data = array('name'=>"Virat Gandhi");
//       Mail::send('mail', $data, function($message) {
//          $message->to('abc@gmail.com', 'Tutorials Point')->subject
//             ('Laravel HTML Testing Mail');
//          $message->from('xyz@gmail.com','Virat Gandhi');
//       });
//       echo "HTML Email Sent. Check your inbox.";
//    }
//    public function attachment_email() {
//       $data = array('name'=>"Virat Gandhi");
//       Mail::send('mail', $data, function($message) {
//          $message->to('abc@gmail.com', 'Tutorials Point')->subject
//             ('Laravel Testing Mail with Attachment');
//          $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
//          $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
//          $message->from('xyz@gmail.com','Virat Gandhi');
//       });
//       echo "Email Sent with attachment. Check your inbox.";

}
