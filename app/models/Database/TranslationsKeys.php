<?php

namespace Model\Database;

class TranslationsKeys extends Model
{
    public int $id;
    public string $key;

    public function initialize()
    {
        $this->belongsTo("id", Translations::class, "translationsId", ['alias' => 'translations']);
    }
}
