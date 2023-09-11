<?php

namespace Model\Database;

use Library\Support\HelperFactory;

class EventDetails extends Model
{
    public int $eventId;
    public object|string $details;

    /*    public function setDetails($details): void
        {
            $this->details = $this->getDi()->get('helper')->encode($details);
        }

        public function getDetails(): object|string
        {
            return $this->details;
        }*/

    public function beforeSave(): void
    {
        $this->details = $this->getDi()->get('helper')->encode($this->details);
    }

    public function afterFetch(): void
    {
        $this->details = $this->getDi()->get('helper')->decode($this->details);
    }

    public function afterSave(): void
    {
        $this->details = $this->getDi()->get('helper')->decode($this->details);
    }
}
