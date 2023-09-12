<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyCustomMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $data;
    public $history;

    public function __construct($history)
    {
        
        $this->history = $history;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email')->with('data', $this->history)->subject('PDF Attachment')
        ->attach(storage_path('/app/voip/pdf/'.$this->history->file_name), [
            'as' =>  $this->history->file_name,
            'mime' => 'application/pdf',
        ]);
       
    }
}
