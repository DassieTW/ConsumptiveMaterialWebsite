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
use Session;
use Crypt;

class MailController extends Controller
{
    public function basic_email()
    {
        $test = Crypt::encrypt('Tony_Tseng@pegatroncorp.com');
        $data = array('email' => $test , 'username' => 'mmm');

        Mail::send('consumecheck', $data, function ($message) {
            // $email = 'Tony_Tseng@pegatroncorp.com';
            $email = 't22923200@gmail.com';
            $message->to($email, 'Tutorials Point')->subject('Check Consume data');
            $message->from('ConsumablesManagement@pegatroncorp.com', 'Consumables Management');
        });
        echo "HTML Email Sent. Check your inbox.";
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
