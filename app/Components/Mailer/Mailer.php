<?php



/**
 *
 * @author Milan Machacek <machacek76@gmail.com>
 */

namespace App\Components\Mailer;

use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;

class Mailer {

    private $params;

    
    public function __construct(\Nette\DI\Container $container) {
        $this->params = $container->getParameters()['mailer'];
    }
    		
    
    
	public function send($to, $sub, $message) {
		 
		$mail = new Message;
		$mail->setFrom($this->params['username'])
			->addTo($to)
			->setSubject($sub)
			->setHtmlBody($message);

		$mailer = new SmtpMailer(array(
		    'host'		=> $this->params['host'],
		    'username'	=> $this->params['username'],
		    'password'	=> $this->params['password'],
		    'port'		=> $this->params['port'],
			'secure'	=> $this->params['secure'],
		));
		$mailer->send($mail);
	}

}
