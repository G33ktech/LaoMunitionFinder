
<!DOCTYPE html>
<html>
<head> 
	<?php
	$reply = 'Words';
	//print_r($_POST);
	// the address
/*$to = "deakin.databack@gmail.com";

//subject line
$subject = "Test email, no filter";

// the message
//$msg = "First line of text\nSecond line of text";
$msg = "test message test message test message test message test message";

// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);$visitor_email

//headers
//$headers = "From: databack@r100.websiteservername.com	";
$headers = "From: mail@databackformserver.com.au";
$headers .= "Reply-To: pbarne@deakin.edu.au";
// send email
mail($to,$subject,$msg,$headers);
echo " Mail sent V2";*/
	if (isset($_POST['submit']))
	{
		$from = $_POST['email'];
		$headers = 'From: databack@r100.websiteservername.com';//.'\r\n'.'X-Mailer: PHP/'.phpversion();
		$body ='   Reply-To: '.$from;
		$body .= $_POST['message'];
		$subject = 'Databack Message from '.$_POST['name'];
		$to = 'deakin.databack@gmail.com';
		mail($to, $subject, $body, $headers);
		$reply = "Your email has been sent.";
	}
	elseif (($name=="")||($email=="")||($message==""))
        {
        $reply = "We would like to hear from you please fill in the contact form. All fields are required.";
        }
	?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="deakin.css"> 
    <title>Contact Us</title>

</head> 
    <body>
       <div id="wrapper">
            <header >
                    <h1>Laos War Time Munitions Finder</h1>
                    
			<nav id="container">
               <img id="lao_title" src="lao.png" />
				<ul class="menu">
				<li class="first_tab"><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="support.html">Support</a></li>
                <li><a href="the_team.html">The Team</a></li>
                <li class="last_tab"><a href="contact_us.html">Contact Us</a></li>
                </ul>

            </nav>
			</header>
           <br />
            <div class="main" style="padding-bottom:80px"">
         <h2>Contact Us</h2>
		 <p id="confirmation">
		 
		 <?php echo $reply ?>
		 
		 </p>
	<form class="body" method="post" action="contact_us.php">
        
		<label>Name</label>
		<input name="name" placeholder="Type Here">
				
		<label>Email</label>
		<input name="email" type="email" required placeholder="Type Here">
				
		<label>Message</label>
		<textarea name="message" placeholder="Type Here"></textarea><br/>
		<input class="reset" name="reset" type="reset" value="Reset" />       
		<input class="submit" id="submit" name="submit" type="submit" value="Submit" />
		
        
	</form>
            
            </div>




      <footer id="footer">
            <div class="footer_img">
                        <a href="https://www.deakin.edu.au" ><img src="deakinv2.jpg" alt="DEAKIN" /></a>
                
                <p class="footer1">Designed by - SIT782 Group 14 T1 2015</p>
                <p class="footer2">This Web Site is for educational purposes only.<br /> 
                Opinions expressed within this Web Site are not necessarily the views of Deakin University Australia.</p>
            </div>
        </footer>
       
     </div>

    </body>
    </html>

