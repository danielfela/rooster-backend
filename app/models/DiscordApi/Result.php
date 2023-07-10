<?php

namespace Model\DiscordApi;

use Library\Api\Snowflake;
use Library\Traits\magicMethodsHelper;
use Phalcon\Di\Di;

class Result extends \Library\MVC\Injectable implements \JsonSerializable, \Stringable
{
    use magicMethodsHelper;

    public Snowflake $identity;
    private object $raw;
    /**
     * @throws \ReflectionException
     */
    public function __construct($response)
    {
        $this->raw = $response;

        foreach($response as $prop => $val) {
            if(property_exists($this, $prop)) {
                $rp = new \ReflectionProperty($this::class, $prop);
                $propClass = $this->getObjectType($rp->getType());
                if($propClass) {
                    if($propClass === 'Snowflake') {
                        $this->$prop = new Snowflake($val);
                    }
                    if (class_exists($propClass)) {
                        $this->$prop = new $propClass($val);
                    }
                    else {
                        throw new \UnexpectedValueException('Class '.$propClass.' not exists');
                    }
                }
                else{
                    $this->$prop = $val;
                }
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function getObjectType(\ReflectionType $rp): string|bool
    {
        if($rp instanceof \ReflectionNamedType) {
            return $rp->isBuiltin() ? false : $rp->getName();
        }
        else if($rp instanceof \ReflectionUnionType) {
            $types = $rp->getTypes();
            foreach($types as $type) {
                if($type->isBuiltin() === false) {
                    return $type->getName();
                }
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): object
    {
        return $this->raw;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->raw;
    }

    public function __get(string $propertyName)
    {

        $uncamelized = Di::getDefault()->get('helper')->uncamelize($propertyName);
        if(isset($this->{$uncamelized})) {
            return $this->{$uncamelized};
        }

        return parent::__get($propertyName);
    }
}
