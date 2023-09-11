<?php

namespace Controllers;

use Library\Http\Response;
use Model\Database\Event;
use Model\Database\EventDetails;
use Model\Database\EventType;
use Model\Database\Zones;

class CalendarController extends BaseController
{
    public function eventToObject($eventRec, $withDetails = false): object
    {
        $ret            = (object)$eventRec->toArray();
        $ret->zone      = $eventRec->zone ? (object)$eventRec->zone->toArray() : null;
        $ret->eventType = $eventRec->eventType ? (object)$eventRec->eventType->toArray() : null;
        $ret->owner     = $eventRec->owner ? (object)$eventRec->owner->toArray() : null;
        if ($withDetails) {
            $ret->details = $eventRec->eventDetails ? (object)$eventRec->eventDetails->details : null;
        }
        unset($ret->ownerId, $ret->eventTypeId, $ret->zoneId);

        return $ret;
    }

    public function get($start, $end)
    {
        $rec = Event::find([
            'date between ?0 AND ?1',
            'bind' => [
                $start, // ex. format '2023-07-01',
                $end,
            ]
        ]);
        $ret = [];
        foreach ($rec as $r) {
            $ret[] = $this->eventToObject($r);
        }

        $response          = Response::getBaseResponse();
        $response->content = $ret;
        return $this->response->setJsonContent($response);
    }

    public function set(): \Phalcon\Http\ResponseInterface
    {
        $title = $this->request->getFromJson('title');
        if (strlen($title) === 0) {
            $title = null;
        }
        $description = $this->request->getFromJson('description');
        if (strlen($description) === 0) {
            $description = null;
        }
        $date = $this->request->getFromJson('date');

        $event              = new Event();
        $event->title       = $title;
        $event->description = $description;
        $event->date        = $date->year . '-' . $date->month . '-' . $date->day . ' ' . $date->hour . ':' . $date->minute . ':00';
        $event->eventTypeId = (integer)$this->request->getFromJson('type');
        $event->zoneId      = (integer)$this->request->getFromJson('zone');
        $event->dkp         = $this->request->getFromJson('dkp');
        $event->ownerId     = $this->instance->getUserId();

        if ($event->save()) {
            return $this->response
                ->setContent('Created')
                ->setStatusCode(201, 'Created');
        } else {
            return $this->response
                ->setContent(implode('<br />', $event->getMessages()))
                ->setStatusCode(400, 'Bad Request');
        }
    }

    public function getEventsData(): \Phalcon\Http\ResponseInterface
    {
        $response          = Response::getBaseResponse();
        $response->content = (object)[
            'zones' => Zones::find()->toArray(),
            'types' => EventType::find()->toArray(),
        ];
        return $this->response->setJsonContent($response);
    }

    public function getEventDetails($eventId): \Phalcon\Http\ResponseInterface
    {
        $event             = Event::findFirstById($eventId);
        $response          = Response::getBaseResponse();
        $response->content = $this->eventToObject($event, true);
        return $this->response->setJsonContent($response);
    }

    public function setRooster(): \Phalcon\Http\ResponseInterface
    {
        $eventId = $this->request->getFromJson('eventId');
        $details = $this->request->getFromJson('');

        $eventDetails = EventDetails::findFirstByEventId($eventId);

        if (!$eventDetails) {
            $eventDetails          = new EventDetails();
            $eventDetails->eventId = $eventId;

        }
        $eventDetails->details = $details;

        if ($eventDetails->save()) {
            return $this->response
                ->setContent('Created')
                ->setStatusCode(201, 'Created');
        } else {
            return $this->response
                ->setContent(implode('<br />', $eventDetails->getMessages()))
                ->setStatusCode(400, 'Bad Request');
        }
    }

}
