<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.09.17
 * Time: 10:32
 */

namespace BotBundle\Service;


class Connect
{
    private $url = 'https://zenitbet.com';
    private $cookiefile = '/tmp/cookie.txt';
    private $postData;

    public function authorization()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($this->postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
        }

        $html = curl_exec($ch);
        curl_close($ch);

        file_put_contents($this->cookiefile, '');

        return $html;
    }
}