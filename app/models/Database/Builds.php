<?php

namespace Model\Database;

use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @method static Builds findFirstByPlayerAndBuild(string $_player, string $_build)
 * @method static Simple findByPlayer(string $_player): self
 */
class Builds extends Model
{
    public string $build;
    public string $player;
    protected string $content;

   /* public function afterFetch()
    {
        $content = json_decode($this->content, true, 512, JSON_OBJECT_AS_ARRAY);
        if(!isset($content['player'])) {
            $content['player'] = $this->player;
        }

        $this->content = $content;
    }*/

    public function getContent() {
        $content = json_decode($this->content, true, 512, JSON_OBJECT_AS_ARRAY);
        if(!isset($content['player'])) {
            $content['player'] = $this->player;
        }

        return $content;
    }
}
