<?php
namespace Lambq\WhatsProto\Utils;

class Functions
{
    public static function generateRequestToken($country, $phone, $platform)
    {
        switch ($platform) {
          case 'Android':
            $signature = 'MIIDMjCCAvCgAwIBAgIETCU2pDALBgcqhkjOOAQDBQAwfDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExFDASBgNVBAcTC1NhbnRhIENsYXJhMRYwFAYDVQQKEw1XaGF0c0FwcCBJbmMuMRQwEgYDVQQLEwtFbmdpbmVlcmluZzEUMBIGA1UEAxMLQnJpYW4gQWN0b24wHhcNMTAwNjI1MjMwNzE2WhcNNDQwMjE1MjMwNzE2WjB8MQswCQYDVQQGEwJVUzETMBEGA1UECBMKQ2FsaWZvcm5pYTEUMBIGA1UEBxMLU2FudGEgQ2xhcmExFjAUBgNVBAoTDVdoYXRzQXBwIEluYy4xFDASBgNVBAsTC0VuZ2luZWVyaW5nMRQwEgYDVQQDEwtCcmlhbiBBY3RvbjCCAbgwggEsBgcqhkjOOAQBMIIBHwKBgQD9f1OBHXUSKVLfSpwu7OTn9hG3UjzvRADDHj+AtlEmaUVdQCJR+1k9jVj6v8X1ujD2y5tVbNeBO4AdNG/yZmC3a5lQpaSfn+gEexAiwk+7qdf+t8Yb+DtX58aophUPBPuD9tPFHsMCNVQTWhaRMvZ1864rYdcq7/IiAxmd0UgBxwIVAJdgUI8VIwvMspK5gqLrhAvwWBz1AoGBAPfhoIXWmz3ey7yrXDa4V7l5lK+7+jrqgvlXTAs9B4JnUVlXjrrUWU/mcQcQgYC0SRZxI+hMKBYTt88JMozIpuE8FnqLVHyNKOCjrh4rs6Z1kW6jfwv6ITVi8ftiegEkO8yk8b6oUZCJqIPf4VrlnwaSi2ZegHtVJWQBTDv+z0kqA4GFAAKBgQDRGYtLgWh7zyRtQainJfCpiaUbzjJuhMgo4fVWZIvXHaSHBU1t5w//S0lDK2hiqkj8KpMWGywVov9eZxZy37V26dEqr/c2m5qZ0E+ynSu7sqUD7kGx/zeIcGT0H+KAVgkGNQCo5Uc0koLRWYHNtYoIvt5R3X6YZylbPftF/8ayWTALBgcqhkjOOAQDBQADLwAwLAIUAKYCp0d6z4QQdyN74JDfQ2WCyi8CFDUM4CaNB+ceVXdKtOrNTQcc0e+t';
            $classesMd5 = 'o17G0Imoh/pOmI5FpLpKHQ=='; // 2.17.420
            $key2 = base64_decode('eQV5aq/Cg63Gsq1sshN9T3gh+UUp0wIw0xgHYT1bnCjEqOJQKCRrWxdAe2yvsDeCJL+Y4G3PRD2HUF7oUgiGo8vGlNJOaux26k+A2F3hj8A=');
            $data = base64_decode($signature).base64_decode($classesMd5).$phone;
            $opad = str_repeat(chr(0x5C), 64);
            $ipad = str_repeat(chr(0x36), 64);
            for ($i = 0; $i < 64; $i++) {
                $opad[$i] = $opad[$i] ^ $key2[$i];
                $ipad[$i] = $ipad[$i] ^ $key2[$i];
            }
            $output = hash('sha1', $opad.hash('sha1', $ipad.$data, true), true);
            return base64_encode($output);
          case 'Nokia':
            $token = md5('PdA2DJyKoUrwLw1Bg6EIhzh502dF9noR9uFCllGk1452554789539'.$phone);
            return $token;
  }
    }
    public static function ExtractNumber($from)
    {
        return str_replace(['@s.whatsapp.net', '@g.us'], '', $from);
    }
    public static function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }

    public static function wa_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos(), true)) {
            die('PBKDF2 ERROR: Invalid hash algorithm.');
        }
        if ($count <= 0 || $key_length <= 0) {
            die('PBKDF2 ERROR: Invalid parameters.');
        }

        $hash_length = strlen(hash($algorithm, '', true));
        $block_count = ceil($key_length / $hash_length);

        $output = '';
        for ($i = 1; $i <= $block_count; $i++) {
            $last = $salt.pack('N', $i);
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }

        if ($raw_output) {
            return substr($output, 0, $key_length);
        } else {
            return bin2hex(substr($output, 0, $key_length));
        }
    }

    public static function preprocessProfilePicture($path)
    {
        list($width, $height) = getimagesize($path);
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $size = 639;
        $image = imagecreatetruecolor($size, $size);
        $img = imagecreatefromstring(file_get_contents($path));

        imagecopyresampled($image, $img, 0, 0, $x, $y, $size, $size, $smallestSide, $smallestSide);
        ob_start();
        imagejpeg($image);
        $i = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);
        imagedestroy($img);

        return $i;
    }

    public static function createIcon($file)
    {
        if ((extension_loaded('gd')) && (file_exists($file))) {
            return createIconGD($file);
        } else {
            return base64_decode(giftThumbnail());
        }
    }

    public static function createIconGD($file, $size = 100, $raw = true)
    {
        list($width, $height) = getimagesize($file);
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $image_p = imagecreatetruecolor($size, $size);
        $image = imagecreatefromstring(file_get_contents($file));

        imagecopyresampled($image_p, $image, 0, 0, $x, $y, $size, $size, $smallestSide, $smallestSide);
        ob_start();
        imagejpeg($image_p);
        $i = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);
        imagedestroy($image_p);

        return $i;
    }

    public static function createVideoIcon($file)
    {
        /* Should install ffmpeg for the method to work successfully  */
        if (checkFFMPEG()) {
            //generate thumbnail
            $preview = sys_get_temp_dir().'/'.md5($file).'.jpg';
            @unlink($preview);

            //capture video preview
            $command = 'ffmpeg -i "'.$file.'" -f mjpeg -ss 00:00:01 -vframes 1 "'.$preview.'"';
            exec($command);

            return createIconGD($preview);
        } else {
            return base64_decode(videoThumbnail());
        }
    }

    public static function checkFFMPEG()
    {
        // Check if ffmpeg is installed.
        $output = [];
        $returnvalue = false;

        exec('ffmpeg -version', $output, $returnvalue);

        return $returnvalue === 0;
    }

    public static function giftThumbnail()
    {
        return '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';
    }

    public static function videoThumbnail()
    {
        return '/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/4QMpaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjAtYzA2MCA2MS4xMzQ3NzcsIDIwMTAvMDIvMTItMTc6MzI6MDAgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzUgV2luZG93cyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2MTQyRUVCOEI3MDgxMUUyQjNGQkY1OEU5M0U2MDE1MyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2MTQyRUVCOUI3MDgxMUUyQjNGQkY1OEU5M0U2MDE1MyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjYxNDJFRUI2QjcwODExRTJCM0ZCRjU4RTkzRTYwMTUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjYxNDJFRUI3QjcwODExRTJCM0ZCRjU4RTkzRTYwMTUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQAAgICAgICAgICAgMCAgIDBAMCAgME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';
    }
    public static function updateData($nameFile, $WAver, $WAToken = null)
    {
        $file = __DIR__.'/'.$nameFile;
        $open = fopen($file, 'r+');
        $content = fread($open, filesize($file));
        fclose($open);

        $content = explode("\n", $content);

        if ($file == __DIR__.'/token.php') {
            $content[27] = '    $releaseTime = \''.$WAToken.'\';';
        } else {
            if ($file == __DIR__.'/Constants.php') {
                $content[21] = '    const WHATSAPP_VER = \''.trim($WAver).'\';                                                          // The WhatsApp version.';
                $content[22] = '    const WHATSAPP_USER_AGENT = \'WhatsApp/'.trim($WAver).' S40Version/14.26 Device/Nokia302\';         // User agent used in request/registration code.';
            }
        }

        $content = implode("\n", $content);

        file_put_contents($file, $content);
    }
    public static function generatePaymentLink($number, $sku)
    {
        return sprintf(
        'https://www.whatsapp.com/payments/cksum_pay.php?phone=%s&cksum=%s&sku=%d',
        $number,
        md5($number.'abc'),
        $sku,
        ($sku != 1 && $sku != 3 && $sku != 5) ? 1 : $sku
    );
    }

    // Gets mime type of a file using various methods
    public static function get_mime($file)
    {
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file);
            finfo_close($finfo);

            return $mime;
        }

        if (function_exists('mime_content_type')) {
            return mime_content_type($file);
        }

        if (!strncasecmp(PHP_OS, 'WIN', 3) == 0 && !stristr(ini_get('disable_functions'), 'shell_exec')) {
            $file = escapeshellarg($file);
            $mime = shell_exec('file -b --mime-type '.$file);

            return trim($mime);
        }

        return false;
    }

    public static function getExtensionFromMime($mime)
    {
        $extensions = [
      'audio/3gpp'      => '3gp',
      'audio/x-caf'     => 'caf',
      'audio/wav'       => 'wav',
      'audio/mpeg'      => 'mp3',
      'audio/mpeg3'     => 'mp3',
      'audio/x-mpeg-3'  => 'mp3',
      'audio/x-ms-wma'  => 'wma',
      'audio/ogg'       => 'ogg',
      'audio/aiff'      => 'aif',
      'audio/x-aiff'    => 'aif',
      'audio/aac'       => 'aac',
      'audio/mp4'       => 'm4a',
      'image/jpeg'      => 'jpg',
      'image/gif'       => 'gif',
      'image/png'       => 'png',
      'video/3gpp'      => '3gp',
      'video/mp4'       => 'mp4',
      'video/quicktime' => 'mov',
      'video/avi'       => 'avi',
      'video/msvideo'   => 'avi',
      'video/x-msvideo' => 'avi',
  ];

        return $extensions[$mime];
    }

    public static function adjustId($id)
    {
        $data = strrev(pack('L', $id));
        $data = bin2hex(ltrim($data));
        while (strlen($data) < 6) {
            $data = '0'.$data;
        }

        return hex2bin($data);
    }

    public static function deAdjustId($data)
    {
        if (strlen($data) < 4) {
            $data = "\x00".$data;
        }
        $data = strrev($data);
        $data = unpack('L', $data);

        return $data[1];
    }

    public static function unpadV2Plaintext($v2plaintext)
    {
        if (strlen($v2plaintext) < 128) {
            return substr($v2plaintext, 2, -1);
        } else {
            return substr($v2plaintext, 3, -1);
        }
    }
    public static function parseText($txt)
    {
        for ($x = 0; $x < strlen($txt); $x++) {
            if (ord($txt[$x]) < 20 || ord($txt[$x]) > 230) {
                $txt = 'HEX:'.bin2hex($txt);

                return $txt;
            }
        }

        return $txt;
    }
    public static function niceVarDump($obj, $ident = 0)
    {
        $data = '';
        $data .= str_repeat(' ', $ident);
        $original_ident = $ident;
        $toClose = false;
        switch (gettype($obj)) {
        case 'object':
            $vars = (array) $obj;
            $data .= gettype($obj).' ('.get_class($obj).') ('.count($vars).") {\n";
            $ident += 2;
            foreach ($vars as $key => $var) {
                $type = '';
                $k = bin2hex($key);
                if (strpos($k, '002a00') === 0) {
                    $k = str_replace('002a00', '', $k);
                    $type = ':protected';
                } elseif (strpos($k, bin2hex("\x00".get_class($obj)."\x00")) === 0) {
                    $k = str_replace(bin2hex("\x00".get_class($obj)."\x00"), '', $k);
                    $type = ':private';
                }
                $k = hex2bin($k);
                if (is_subclass_of($obj, 'ProtobufMessage') && $k == 'values') {
                    $r = new ReflectionClass($obj);
                    $constants = $r->getConstants();
                    $newVar = [];
                    foreach ($constants as $ckey => $cval) {
                        if (substr($ckey, 0, 3) != 'PB_') {
                            $newVar[$ckey] = $var[$cval];
                        }
                    }
                    $var = $newVar;
                }
                $data .= str_repeat(' ', $ident)."[$k$type]=>\n".niceVarDump($var, $ident)."\n";
            }
            $toClose = true;
        break;
        case 'array':
            $data .= 'array ('.count($obj).") {\n";
            $ident += 2;
            foreach ($obj as $key => $val) {
                $data .= str_repeat(' ', $ident).'['.(is_int($key) ? $key : "\"$key\"")."]=>\n".niceVarDump($val, $ident)."\n";
            }
            $toClose = true;
        break;
        case 'string':
            $data .= 'string "'.parseText($obj)."\"\n";
        break;
        case 'NULL':
            $data .= gettype($obj);
        break;
        default:
            $data .= gettype($obj).'('.strval($obj).")\n";
        break;
    }
        if ($toClose) {
            $data .= str_repeat(' ', $original_ident)."}\n";
        }

        return $data;
    }
    public static function encodeInt7bit($value)
    {
        $v = $value;
        $out = '';
        while ($v >= 0x80) {
            $out .= chr(($v | 0x80) % 256);
            $v >>= 7;
        }
        $out .= chr($v % 256);

        return $out;
    }

    public static function padMessage($plaintext)
    {
        $padded = '';
        $padded .= chr(10);
        $padded .= encodeInt7bit(strlen($plaintext));
        $padded .= $plaintext;
        $padded .= chr(1);

        return $padded;
    }

    public static function randomStr($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function getRandomGCM()
    {
        return randomStr(5).'_'.randomStr(5).':'.'APA91b'.randomStr(64).
         '_'.randomStr(5).'-'.randomStr(12).'_'.randomStr(9).
         '_'.randomStr(11).'_'.randomStr(1).'_'.randomStr(26);
    }
}
