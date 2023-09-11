<?php

namespace Model\Database;

use Phalcon\Support\HelperFactory;

/**
 * @method static Server findFirstById(string $id)
 */
class Server extends Model
{
    public string $id;
    public ?string $owner_id = null;
    protected ?string $settings = null;
    private ?\Library\Api\Server $encodedSettings = null;


    public function getSettings(): \Library\Api\Server|null
    {
        if (!$this->encodedSettings) {
            if (isset($this->settings) && strlen($this->settings) > 0) {
                //Phalcon helper decode is not working ??
                $this->encodedSettings = new \Library\Api\Server(json_decode($this->settings));
            }
        }

        return $this->encodedSettings;
    }

    public function setSettings($settings): void
    {
        if (null === $settings) {
            return;
        }

        $this->settings = is_string($settings) ? $settings : $this->getDi()->get('helper')->encode($settings);
    }

    public function initialize()
    {
        parent::initialize();
        $this->setSource('servers');
    }
}
