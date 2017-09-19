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
        $parser = new ParserWilliam($link);
        $parser->connect();

        $williamTitle = $parser->getTitle();

        $zenitService = new ZenitbetService($data['match_number']);

        if ($zenitService->authorization([$data['login'], $data['password']])) {
            $zenitTitle = $zenitService->goToMatch();
            $zenitService->setBet();
        } else {
            throw new \Exception('не могу авторизоваться, проверьте имя пользователя и пароль на соответствие: 
            Логин - '.$data['login'].', Пароль - '.$data['password']);
        }

        $williamArray = explode(' v ', $williamTitle);
        $zenitmArray = explode('-', $zenitTitle);


        return [
            'Матч на williamhill.com' => $williamTitle,
            'Матч на zenitbet.com' => $zenitTitle,
        ];
    }
}