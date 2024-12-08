<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Modeltblusers;
class RegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Modeltblusers $userinfo)
    {
      $this->user=$userinfo;
  }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email/user_registered_email') ->with([
            'user_name' => $this->user->email,
            'expiry_date' => date('d-m-Y', strtotime("+30 days"))
        ]);
    }
}