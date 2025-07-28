<?php

namespace App\Mail;



use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;



class NotificationMail extends Mailable

{

    use Queueable, SerializesModels;



    public $title;

    public $message;

    public $org;

    public $user;

    public $activeLocations;



    public function __construct($title, $message, $org, $user)

    {

        $this->title = $title;

        $this->message = $message;

        $this->org = $org;

        $this->user = $user;

        $this->activeLocations = \App\Models\Location::where('org_id', $org->id)->get();

    }



    public function build()

    {

        return $this->subject($this->title)

            ->view('emails.notification')

            ->with([

                'title' => $this->title,

                'message' => $this->message,

                'org' => $this->org,

                'user' => $this->user,
                 'activeLocations' => $this->activeLocations,

            ]);



    }

}

