<?php

namespace Deimos;

class Libs
{

    const FORMAT_BYTE_B = 'B';
    const FORMAT_BYTE_KB = 'KB';
    const FORMAT_BYTE_MB = 'MB';
    const FORMAT_BYTE_GB = 'GB';
    const FORMAT_BYTE_TB = 'TB';
    const FORMAT_BYTE_PB = 'PB';
    const FORMAT_BYTE_EB = 'EB';
    const FORMAT_BYTE_ZB = 'ZB';
    const FORMAT_BYTE_YB = 'YB';
    const FORMAT_BYTE_AUTO = '';

    /**
     * @param $bytes
     * @param int $decimals
     * @param $unit
     * @return string
     */
    public static function byte($bytes, $decimals = 2, $unit = Libs::FORMAT_BYTE_AUTO)
    {

        $units = array(
            'B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3,
            'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7,
            'YB' => 8
        );

        $value = 0;

        if ($bytes) {

            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        return sprintf('%.' . $decimals . 'f %s', $value, $unit);
    }

    /**
     * @param string $file_path
     * @param string $file_download_name
     * @param int $length
     * @return bool
     */
    public static function force_download(
        $file_realpath = './file.txt',
        $file_path = './file.txt',
        $file_download_name = 'File-Name',
        $length = 1024,
        $sleep = false
    ) {

        if (file_exists($file_realpath)) {

            if (ob_get_level())
                ob_end_clean();

            $file_download_name = str_replace(array('"', "'", ' ', ','), '_', $file_download_name);

            header('Content-Type: ' . mime_content_type($file_realpath), true);
            header('Content-Disposition: inline; filename=' . $file_download_name);
            //header('Content-Description: File Transfer');
            //header('Content-Length: ' . filesize($file_realpath));
            //header('Content-Transfer-Encoding: binary');
            //header('Cache-Control: must-revalidate');
            //header('Accept-Ranges: bytes');
            //header('Pragma: public');
            header("Expires: " . gmdate("D, d M Y H:i:s", time() + ( 60 * 60 * 24 * 7 )) . " GMT");
            
            header("X-Accel-Redirect: " . $file_path);

            /*if ($fd = fopen($file_path, 'rb')) {

                while (!feof($fd)) {
                    print fread($fd, $length);
                    if ( $sleep ) sleep(1);
                }
                return fclose($fd);
            }*/
            
            return true;
        }

        return false;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function random_characters($length = 5)
    {

        $alf = array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            range('0', '9')
        );

        for ($url = '', $i = 0; $i < $length; $i++) {

            while (($l = mb_strlen($url)) &&
                (($ch = $alf[array_rand($alf)]) &&
                    ($ch == mb_substr($url, $l - 1, 1)))) ;

            $url .= $alf[array_rand($alf)];

        }

        return $url;

    }

    /**
     * @return bool
     */
    public static function is_https()
    {
        if (isset($_SERVER['HTTPS'])) {
            $https = $_SERVER['HTTPS'];
            return (bool)filter_var($https, FILTER_VALIDATE_BOOLEAN);
        }
        return false;
    }

    /**
     * @return string
     */
    public static function get_protocol()
    {
        return static::is_https() ? 'https://' : 'http://';
    }

}
