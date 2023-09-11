<?php

namespace Model\Database;

class EventType extends Model
{
    public string $id;
    public string $mainType = 'PVX';
    protected string $settings;

    public function initialize()
    {
        parent::initialize();
        $this->setSource('event_type');
    }
}
