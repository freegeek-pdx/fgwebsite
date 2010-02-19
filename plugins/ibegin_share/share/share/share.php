<?php

/******************************************************\
         iBegin Share Configuration    - Start -
\******************************************************/

// Set this to the email address you wish to send email from.
// Leave it null to use the server default.
define(MAIL_SENDER, null);

// The subject of the email to send. (Eg. "You've got new message about iBegin Share")
function generateEmailSubject($title, $link, $from_name, $from_email, $to_name, $to_email)
{
    return "{$from_name} wants you to see this link";
}
// The plain text body of the email to send
function generateEmailBody($title, $link, $from_name, $from_email, $to_name, $to_email, $message=null)
{
    $output = array();
    $output[] = $from_name . ' thought you might find this link interesting:';
    $output[] = '';
    $output[] = 'Title: ' . $title;
    $output[] = 'Link: ' . $link;
    if ($message) $output[] = 'Message: ' . messageFilter($message);
    $output[] = '';
    $output[] = '-----------------------------';
    $output[] = $from_name . ' is using iBegin Share (http://labs.ibegin.com/share/)';
    $output[] = '-----------------------------';
    return implode("\r\n", $output);
}

/******************************************************\
         iBegin Share Configuration    - End -
\******************************************************/

// pdf was breaking
define(RELATIVE_PATH, '');

// get the content in a raw format
$link = strip_tags(urldecode($_GET['link']));
$title = urldecode($_GET['title']);
$raw_content = '(No content available)';
if (!empty($_GET['content']))
{
    $fp = @fopen(urldecode($_GET['content']),'r');
    if (is_resource($fp))
    {
        $raw_content = '';
        while(!feof($fp)) $raw_content .= fread($fp,4096); 
    }
}

// Set Header and cache expiration
$offset = 60 * 60 * 24 * 2; // 2 days to expiry date.
@ob_start("ob_gzhandler");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");                                                                 
header('Cache-Control: ');
header('Pragma: ');

//process the request. like do the mailing in 'Email' section.
switch($_GET['act'])
{
    // E-mail
    case 'email':
        header("Content-Type: text/plain");
        if (empty($_GET['shre_mail_frnme']) || empty($_GET['shre_mail_freml']) || empty($_GET['shre_mail_toeml']) || empty($_GET['shre_mail_tonme']))
        {
            header("HTTP/1.1 400 Bad Request");
            die('Please fill in all required fields.');
        }
        elseif (!isValidEmail(trim($_GET['shre_mail_freml'])))
        {
            header("HTTP/1.1 400 Bad Request");
            die('Your e-mail is invalid.');
        }
        elseif (!isValidEmail(trim($_GET['shre_mail_toeml'])))
        {
            header("HTTP/1.1 400 Bad Request");
            die('Your friend\'s email is invalid.');
        }
        else
        {
            $subject = generateEmailSubject($title, $link, $_GET['shre_mail_frnme'], $_GET['shre_mail_freml'], $_GET['shre_mail_tonme'], $_GET['shre_mail_toeml']);
            $body = generateEmailBody($title, $link, $_GET['shre_mail_frnme'], $_GET['shre_mail_freml'], $_GET['shre_mail_tonme'], $_GET['shre_mail_toeml'], $_GET['shre_mail_msg']);

            $from = $_GET['shre_mail_frnme']. '<'.$_GET['shre_mail_freml'].'>';
            $to = $_GET['shre_mail_tonme']. '<'.$_GET['shre_mail_toeml'].'>';

            $headers = array();
            $headers[] = 'From: ' . $from;
            $headers[] = 'Reply-To: ' . $from;
            $headers[] = 'X-Mailer: iBeginShare (PHP/' . phpversion() . ')';
            $headers = implode("\r\n", $headers);
                
            if (@mail($to, $subject, $body, $headers)) die('Your email was sent successfully.');
            else
            {
                header("HTTP/1.1 500 Internal Server Error");
                die('Unknown error sending the email.');
            }
        }
    break;
    // My PC
    case 'mypc':
        if($_GET['f'] == 'word')
        {
            require_once(dirname(__FILE__).'/includes/HTML2Doc.php');
            $doc = new HTML_TO_DOC();
            $doc->setTitle(quoteSmart($title));
            $doc->createDoc($raw_content,((strlen(quoteSmart($title)) > 0)?str_replace(' ','-',strip_tags($title)):'Document-'.date('Y-m-d')).'.doc');
        }
        else
        {
            require_once(dirname(__FILE__).'/includes/html2fpdf/html2fpdf.php');
            $pdf = new HTML2FPDF();
            $pdf->DisplayPreferences($title);
            $pdf->AddPage();
            $pdf->WriteHTML(html_entity_decode($raw_content));
            $pdf->Output(((strlen(quoteSmart($title)) > 0)?str_replace(' ','-',strip_tags($title)):'Document-'.date('Y-m-d')).'.pdf' ,'D');
        }
    break;
    // Print
    case 'print':
        header('Content-Type: text/html');
        echo $raw_content . '<script type="text/javascript">window.print();</script>';
    break;

    default:
        header("Location: ../");
    break;
}

function isValidEmail($email)
{
    $email = trim($email);
    return (bool)preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
}
function quoteSmart($value)
{
    if (is_array($value))
    {
        foreach ($value as $key => $value2):
            $value[$key] = htmlspecialchars((string) trim($value2), ENT_QUOTES, 'UTF-8');
        endforeach;
        return $value;
    }
    else
    {
        return htmlspecialchars((string) trim($value), ENT_QUOTES, 'UTF-8');
    }
}
function quoteDecode($value)
{
    if (is_array($value))
    {
        foreach ($value as $key => $value2)
        {
            $x = strtr($value2, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)));
            $value[$key] = str_replace('&#039;',"'",$x);
        }
        return $value;
    }
    else
    {
        $x = strtr($value, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)));
        return  str_replace('&#039;',"'",$x);
    }
}
function messageFilter($s){
    $s = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-])*", " ", $s);
    return ereg_replace("(^| |.)(www([.]?[a-zA-Z0-9_/-])*)", " ", $s);
}
