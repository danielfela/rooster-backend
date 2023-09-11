<?php

namespace Controllers;

class CraftersController extends \Phalcon\Mvc\Controller
{
    /**
     * @param $_userId string|int|null Discord user id
     * @param $_professionId string|int|null Profession id
     * @param $_professionLevel int|null Profession level 0-200
     * @param $_trophyLevel int|null Trophies level 0-9, 1 for each level of each trophy (3 trophies x 3 levels = max 9)
     * @param $_workwearLevel int|null Wear level 0-5, 1 for each piece
     * @return void
     */
    public function add(
        $_userId = null,
        $_professionId = null,
        ?int $_professionLevel = 200,
        ?int $_trophyLevel = 9,
        ?int $_workwearLevel = 5
    ) {

        var_dump(func_get_args());
        die();
    }

    public function showAddForm()
    {

        $this->view->professions = \Professions::

        $this->view->pick('crafters/add');

    }
}
