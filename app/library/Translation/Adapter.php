<?php

namespace Library\Translation;

use Model\Database\Languages;
use Model\Database\Translations;
use Phalcon\Di;

class Adapter
{
    public int $ttl = 60*60*24;

    public function getText(string $_key, $_value = 0, $_lang = 'en')
    {
        /** @var \Library\Cache\Adapter $cache */
        $cache = Di\Di::getDefault()->getShared('cache');
        $cache->toggle('translations_'.$_lang, $this->ttl, function() use ($_lang) {
            var_dump('no cache');

            $ret = [];
           /* foreach(Translations::findByLanguageId(Languages::findFirstByIso($_lang)->id) as $t){
                $ret[$t->tk->key] = (object)[
                    'text' => $t->text,
                    'plurals' => $t->plurals,
                ];
            }*/

        });
        $ret = [];

        foreach(Translations::findByLanguageId(Languages::findFirstByIso($_lang)->id) as $t){
           // var_dump($t->toArray());
           // var_dump($t->getTk()->toArray());
//var_dump($t->getL()->toArray());
            $ret[$t->tk->key] = (object)[
                'text' => $t->text,
                'plurals' => $t->plurals,
            ];
        }
        var_dump($ret);
die('oo');
        switch ($_lang) {
            case 'pl':
                return $_options[(($_value == 1) ? 0 : ((($_value % 10 >= 2) && ($_value % 10 <= 4) && (($_value % 100 < 12) || ($_value % 100 > 14))) ? 1 : 2)) + 1];
            case 'ro':
                return $_options[(($_value == 1) ? 0 : ((($_value == 0) || (($_value % 100 > 0) && ($_value % 100 < 20))) ? 1 : 2))+1];
            case 'cs':
                return $_options[(($_value == 1) ? 0 : ($_value >= 2 && $_value <= 4 ? 1 : 2)) + 1];
            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'hy':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'xbr':
            case 'ti':
            case 'wa':
                return $_options[($_value == 1 || $_value == 0) ? 1 : 2];
            case 'af':
            case 'az':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                return $_options[($_value == 1) ? 1 : 2];
            case 'lt':
                return $_options[((($_value % 10 == 1) && ($_value % 100 != 11)) ? 0 : ((($_value % 10 >= 2) && (($_value % 100 < 10) || ($_value % 100 >= 20))) ? 1 : 2))+1];
            default :
                return 0;
        }
    }
}
