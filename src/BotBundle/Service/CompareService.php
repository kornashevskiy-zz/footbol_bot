<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.17
 * Time: 15:46
 */

namespace BotBundle\Service;


class CompareService
{
    public static function compareWilliamsAndZenitbet($data)
    {
        $linkHandler = new LinkHandler();
        $link = $linkHandler->changeUrl($data['link']);
        $parser = ParserWilliam::getInstance();
        $parser->setUrl($link);
        $parser->connect();

        $williamTitle = $parser->getTitle();

        FeatureContext::getWebDriver()->executeScript('window.open()');
        $tabs = FeatureContext::getWebDriver()->getWindowHandles();
        FeatureContext::getWebDriver()->switchTo()->window($tabs[1]);

        $zenitService = ZenitbetService::getInstance();
        $parser->photo();

        $zenitService->setMatch($data['match_number']);

        if ($zenitService->authorization([$data['login'], $data['password']])) {
            $zenitTitle = $zenitService->goToMatch();
//            $newTitle = explode('-', $zenitTitle);
//            $zenitService->setBet($newTitle[0], $data['count_bet']);
        } else {
            throw new \Exception('не могу авторизоваться, проверьте имя пользователя и пароль на соответствие: 
            Логин - '.$data['login'].', Пароль - '.$data['password']);
        }

        return [
            'Матч на williamhill.com' => $williamTitle,
            'Матч на zenitbet.com' => $zenitTitle,
        ];
    }
}