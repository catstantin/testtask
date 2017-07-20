<?php

class Sender
{
	private $name = NULL;
	private $email = NULL;
	private $text = NULL;
	private $files = [];


	public function __construct()
	{
		if (!empty($_POST['name'])) {
			$this->name = $_POST['name'];
		}

		if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$this->email = $_POST['email'];
		}

		if (!empty($_POST['text'])) {
			$this->text = $_POST['text'];
		}

		//TODO:: if you need security (check file MIME, size, extension e.t.c.) on load - write it here

		if (is_array($_FILES)) {
			$this->files = $_FILES['files'];
		}

	}

    public function run() 
    {
    	$message = '';

    	if ($this->name && $this->email && $this->text) {

		    require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php';
		    
		    $mail = new PHPMailer;
		    $mail->setFrom('from@example.com', 'First Last');
		    $mail->addAddress($this->email, $this->name);
		    $mail->Subject = 'File sender';
		    $mail->Body = $this->text;
		    $mail->isHTML(true); // Set email format to HTML

		    //Attach multiple files one by one
		    for ($counter = 0; $counter < count($this->files['tmp_name']); $counter++) {
		        $uploadFile = tempnam(sys_get_temp_dir(), sha1($this->files['name'][$counter]));
		        $filename = $this->files['name'][$counter];

		        if (move_uploaded_file($this->files['tmp_name'][$counter], $uploadFile)) {
		            $mail->addAttachment($uploadFile, $filename);
		        } 
		        else {
		            $message .= ' Failed to move file to ' . $uploadFile;
		        }
		    }

		    if (!$mail->send()) {
		        return $message . ' ' . $mail->ErrorInfo;
		    } 
    	}

	    return true;
    }

}
?>