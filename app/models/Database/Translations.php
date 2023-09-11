<?php

namespace Model\Database;

/**
 * @method static Translations findByLanguageId(string $_languageId)
 */
class Translations extends Model
{
    public int $translationId;
    //public int $translationsId;
    public int $languageId;
    public string $plurals;
    public string $text;

    public function initialize()
    {
        $this->hasOne(
            "translationId",
            TranslationsKeys::class,
            "id",
            ['alias' => 'tk']);
        $this->hasOne(
            "languageId",
            Languages::class,
            "id",
            ['alias' => 'l']);
    }
}
