<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.09.17
 * Time: 14:17
 */

namespace BotBundle\Service;


use Symfony\Component\DomCrawler\Crawler;

class DataHandler
{
    private $title = null;
    private $id = null;

    private $betId = null;

    public function findMatch($html, $match)
    {
        $crawler = new Crawler($html);

        $crawler->filter('table')->each(function (Crawler $table) use ($match) {

            $table->filter('tr')->each(function (Crawler $tr) use ($match) {

                if ($tr->filter('td > span')->getNode(0) != null) {

                    if ($tr->filter('td > span')->text() == $match) {
                        $arr = ["\r", "\n", "\t"];
                        $this->title = trim(str_replace($arr, '', $tr->filter('td > b')->text()));
                        $this->id = $tr->filter('td > b')->attr('id');
                    }
                }
            });
        });

        return [
            'title' => $this->title,
            'id' => $this->id
        ];
    }

    public function findBet($html, $name)
    {
        $crawler = new Crawler($html);

        if ($crawler->filter('.g-r-l')->getNode(0) != null) {
            $crawler->filter('.g-r-l')->each(function (Crawler $div) use ($name) {
                $text = trim($div->text());
                preg_match('/^(\d+.*гол)/', $text, $matches);

                if (isset($matches[0])) {
                    if (trim($div->filter('b:nth-child(1)')->text()) == trim($name)) {
                        $this->betId =  $div->filter('a:nth-child(1)')->attr('id');
                    } elseif (trim($div->filter('b:nth-child(2)')->text()) == trim($name)) {
                        $this->betId = $div->filter('a:nth-child(2)')->attr('id');
                    }
                }
            });
        }

        if ($this->betId == null) {
            throw new \Exception('на этот матч нет ставок на гол');
        }
        return $this->betId;
    }
}