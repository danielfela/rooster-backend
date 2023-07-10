<?php

namespace Controllers;

use Model\Database\Languages;
use Model\Database\Translations;

class TranslationsController
{
    public function generateAction($_lang){
        $langId = Languages::findFirstByIso($_lang)->id;
        foreach(Translations::findByLanguageId($langId) as $t)
        {
            return;
        }
    }
}
