<?php

namespace Model\Database;

/**
 * @method static findFirstById($eventId): self
 */
class Event extends Model
{
    public ?int $id = null;
    public int $eventTypeId;
    public ?string $title;
    public ?string $description;
    private int $dkp = 0;
    public string $date;
    public string $ownerId;
    public ?int $zoneId;

    public function initialize()
    {
        $this->hasOne("zoneId", Zones::class, "id", ['alias' => 'zone']);
        $this->hasOne("eventTypeId", EventType::class, "id", ['alias' => 'eventType']);
        $this->hasOne("ownerId", Users::class, "id", ['alias' => 'owner']);
        $this->hasOne("id", EventDetails::class, "evenId", ['alias' => 'eventDetails']);

    }

    public function getDkp(): bool
    {
        return (bool)$this->dkp;
    }

    public function setDkp(bool $dkp) {
        $this->dkp = (int)$dkp;
    }
}
