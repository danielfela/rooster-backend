<?php

namespace Model\Database;

class NwtextsContent extends Model
{
    public int $propId;
    public int $langId;
    public string $text;
    public string $orgText;

    public function initialize()
    {
        $this->hasOne("propId", NwtextsProps::class, "id", ['alias' => 'prop']);
    }


}
