<?php


namespace In;

use Zend_Controller_Action;



class InvoicesMailController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{

		$hostname = '{mail.iofractal.com:143}INBOX';
		$username = 'bill@iofractal.com@iofractal.com';
		$password = 'billisnotvil2013.';

		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());

		/* grab emails */
		$emails = imap_search($inbox,'ALL');

		/* if emails are returned, cycle through each... */
		if($emails) {
			echo "hay";
		}
	}
	 
	 
}




