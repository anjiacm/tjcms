<?php

/**
 * Curl wrapper PHP v2
 * @author hackerone
 */
class Curl
{
    private function curl_get_content($url, $method = 'GET', $data = '', $isFake = false, $isXmlRequest=false,$referer='')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, "Accept-Charset: utf-8");
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(strpos($referer,'http')!==false){
            curl_setopt ($ch,CURLOPT_REFERER,$referer);
        }
        if ($isFake) {
            $header = $this->getHeader();
            if(!$isXmlRequest){
                $header[]='X-Requested-With:XMLHttpRequest';
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'error' . curl_error($ch);
        }
        curl_close($ch);
        return $result;

    }

    private function getHeader()
    {
        $cip = "123.125." . mt_rand(0, 254) . "." . mt_rand(0, 254);
        $xip = "125.90." . mt_rand(0, 254) . "." . mt_rand(0, 254);
        $header = array(
            'CLIENT-IP:' . $cip,
            'X-FORWARDED-FOR:' . $xip,
        );
        return $header;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function get($url, $isFake = false, $method = 'GET', $data = '')
    {
        return $this->curl_get_content($url, $method, $data, $isFake);
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     */
    public function post($url, $data, $isFake = false, $isXmlRequest=false,$referer='')
    {
        return $this->curl_get_content($url, 'POST', $data, $isFake, $isXmlRequest, $referer);
    }


    public function init()
    {
        return;
    }
}
