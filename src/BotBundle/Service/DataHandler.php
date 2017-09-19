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
    private $firstB = false;
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
                    $div->filter('b')->each(function (Crawler $b, $i) use ($name, $div) {
                        $title = trim($b->text());
                        preg_match('/^(\d+.*гол)/', $title, $matches);

                        if (isset($matches[0])) {
                            $this->firstB = true;
                        }

                        if ($title == trim($name)) {
                            $num = $this->firstB ? $i : $i + 1;
                            $selector = 'a:nth-child('.$num.')';
                            $html = $div->html();
                            $selector = $div->filter('a')->getNode(0) != null ? 'a' : 'span';
                            $div->filter($selector)->each(function (Crawler $node, $a) use ($num) {
                                $tag = $node->nodeName();
                                $text = $node->text();
                                if ($tag == 'a') {
                                    if ($a + 1 == $num) {
                                        $this->betId = $node->attr('id');
                                    }
                                } elseif ($tag == 'span') {
                                    $this->betId = $node->attr('class');
                                }

                            });
//                            $this->betId = $div->filter($selector)->attr('id');
                        }
                    });
                }
            });
        }

        if ($this->betId == null) {
            throw new \Exception('на этот матч нет ставок на гол');
        }
        return $this->betId;
    }

    public function findInputId($html)
    {
        $crawler = new Crawler($html);

        return $this->checkNode('#basket-bets input', 'id', $crawler);
    }

    public function checkNode($selector, $attr, Crawler $node)
    {
        if ($node->filter($selector) != null) {
            if ($attr == 'text') {
                return trim($node->filter($selector)->text());
            } else {
                return trim($node->filter($selector)->attr($attr));
            }
        }

        return null;
    }

    public function openModalTag()
    {

    }
}