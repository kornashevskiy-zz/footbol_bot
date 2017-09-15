<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.09.17
 * Time: 16:02
 */

namespace BotBundle\Service;


use Symfony\Component\Config\Definition\Exception\Exception;

class LinkHandler
{
    private $url = 'https://cachescoreboards.williamhill.com/football/3.0/index.html?eventId=';
    private $uri = '&sport=football&locale=ru-ru';
    public function changeUrl($url)
    {
        preg_match('/(OB_EV)(\d+)/', $url, $matches);

        if (isset($matches[2])) {
            return $this->url.$matches[2].$this->uri;
        }
        throw new Exception('Вы передали некорректную ссылку!');
    }
}