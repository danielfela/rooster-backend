<?php

namespace Model\Database;

class EventDetails extends Model
{
    public int $eventId;
    private string $details;

    public function getDetails(): object
    {
        return $this->helper->decode($this->details);
    }

    public function setDetails($details) {
        $this->details = $this->helper->encode($details);
    }
}
