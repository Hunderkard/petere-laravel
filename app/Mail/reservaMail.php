<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class reservaMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    public function __construct($req)
    {
        $this->data = $req;
    }

    public function build()
    {
        return $this->markdown('Email.reserva')
        ->with(['data' => $this->data]);
    }
}
