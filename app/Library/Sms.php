<?php
/**
 * @author Md. Monzurul Haque <monzu860@yahoo.com>
 * @param string $pageName defining page name
 * @param array $data defining dynamic values passed to view
 * @return custom view
 */
namespace App\Library;


class Sms
{

public static function send_sms($sms_text, $recipients, $ta='pv', $mask='', $type='text')
{
  $destination = '';
  if ($ta == 'pv') { # private message (to numbers)
    if (!is_array($recipients)) { # one or more numbers specified in string, comma delimited
      $recipients = collect(explode(',', $recipients))->filter(function ($msisdn, $key) {
        $msisdn = trim(preg_replace("/[^0-9]+/", "", $msisdn));
        $msisdn = preg_replace("/^(00)?(88)?0/", "", $msisdn);
        if (strlen($msisdn) != 10 || strncmp($msisdn, "1", 1) != 0)
        return false;

        $msisdn = "880" . $msisdn;
        return $msisdn;
        })->toArray(); # make array of numbers
    }
    $destination = implode(',', $recipients); # filter out invalid numbers
  } else { # broadcast message (to group)
    $destination = strtoupper(trim($recipients));
  }


  //$destination=$recipients;

  if ($destination == '') return false;
  if ($type != 'flash') $type = 'text';

  $url = "http://sms.nixtecsys.com/index.php?app=webservices&ta=$ta&u=lithe"
    . "&h=91e9a911fe2e5e341153c671653ad6697a734d63&to=" . rawurlencode($destination)
    . "&msg=" . rawurlencode($sms_text) . "&mask=" . rawurlencode($mask)
    . "&type=$type";
  return file_get_contents($url);
}
}