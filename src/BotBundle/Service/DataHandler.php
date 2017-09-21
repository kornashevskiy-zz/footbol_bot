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

                    if ($tr->filter('td > span')->getNode(0) != null) {
                        if ($tr->filter('td > span')->text() == $match) {
                            $arr = ["\r", "\n", "\t"];
                            $this->title = trim(str_replace($arr, '', $tr->filter('td > b')->text()));
                            $this->id = $tr->filter('td > b')->attr('id');
                        }
                    }

                }
            });
        });

        return [
            'title' => $this->title,
            'id' => $this->id
        ];
    }

    public function findBet($html, $name, $secondCheck = false)
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
                            $selector = $div->filter('a')->getNode(0) != null ? 'a' : 'span';
                            $div->filter($selector)->each(function (Crawler $node, $a) use ($num) {
                                $tag = $node->nodeName();
                                $text = $node->text();
                                if ($tag == 'a') {
                                    if ($a + 1 == $num) {

                                        if ($node->attr('id') != null) {
                                            $this->betId['id'] = $node->attr('id');
                                        } elseif ($node->attr('data-id') != null) {
                                            $this->betId['data-id'] = $node->attr('data-id');
                                        }
                                    }
                                } elseif ($tag == 'span') {
                                    $this->betId = $node->attr('class');
                                }

                            });
                        }
                    });
                }
            });
        }

        if ($crawler->filter('table tr > td > div > div > b')->getNode(0) != null) {
            $crawler->filter('table tr > td > div > div')->each(function (Crawler $div) use ($name) {
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
                            $selector = $div->filter('a')->getNode(0) != null ? 'a' : 'span';
                            $div->filter($selector)->each(function (Crawler $node, $a) use ($num) {
                                $tag = $node->nodeName();
                                if ($tag == 'a') {
                                    if ($a + 1 == $num) {

                                        if ($node->attr('id') != null) {
                                            $this->betId['id'] = $node->attr('id');
                                        } elseif ($node->attr('data-id') != null) {
                                            $this->betId['data-id'] = $node->attr('data-id');
                                        }
                                    }
                                } elseif ($tag == 'span') {
                                    $this->betId['span'] = $node->attr('class');
                                }

                            });
                        }
                    });
                }
            });
        }

        if ($this->betId == null) {

            if ($secondCheck) {
                return false;
            }
            throw new \Exception('на этот матч нет ставок на гол');
        }
        return $this->betId;
    }

    public function findInputId($html)
    {
        $crawler = new Crawler($html);
        $id = $this->checkNode('input', 'id', $crawler);
        return $id;
    }

    public function checkNode($selector, $attr, Crawler $node)
    {
        if ($node->filter($selector)->getNode(0) != null) {
            if ($attr == 'text') {
                $res = trim($node->filter($selector)->text());
                return $res;
            } else {
                $res = trim($node->filter($selector)->attr($attr));
                return $res;
            }
        }

        return null;
    }

    public static function addPoint($str)
    {
        $len = strlen($str);
        $cycle = 1024 - $len;
        for ($i = 0; $i < $cycle; $i++) {
            $str .='.';
        }

        return $str;
    }

    public function checkHtml($html, $element)
    {
        $crawler = new Crawler($html);

        if ($crawler->filter($element)->getNode(0) != null) {
            return true;
        }

        return false;
    }
}