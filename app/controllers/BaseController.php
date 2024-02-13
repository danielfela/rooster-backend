<?php

namespace Controllers;

use Library\MVC\Controller;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Validation;

/**
 *
 */
class BaseController extends Controller
{
    protected static function checkPost($rules, $post): Validation
    {
        $validation = new Validation();

        foreach($rules as $name => $data) {
            foreach($data as $class => $classProps) {
                if(!class_exists($class)) {
                    $validation->appendMessage('Validator not found: '.$class);
                    continue;
                }

                if($class === 'PresenceOf') {
                    if($classProps !== true) {
                        $validation->appendMessage("Validator {$class} malformed");
                        continue;
                    }

                    $validation->add($name, new PresenceOf([
                        'message' => "The {$name} is required",
                    ]));
                }

                if($class === 'Digit') {
                    if($classProps !== true) {
                        $validation->appendMessage("Validator {$class} malformed");
                        continue;
                    }

                    $validation->add($name, new Validation\Validator\Digit([
                        'message' => "The {$name} must be numeric",
                    ]));
                }

                if($class === 'Numericality') {
                    if($classProps !== true) {
                        $validation->appendMessage("Validator {$class} malformed");
                        continue;
                    }

                    $validation->add($name, new Validation\Validator\Numericality([
                        'message' => "The {$name} must be numeric",
                    ]));
                }

                if($class === 'StringLength') {
                    if(!is_array($classProps) || count($classProps) !== 2) {
                        $validation->appendMessage("Validator {$class} malformed");
                        continue;
                    }

                    $validation->add($name, new Validation\Validator\Numericality([
                        'message' => "The {$name} must be numeric",
                    ]));
                }

                $validation->add($name, new $class(['message' => 'The name is required',]));
            }

        }


        $validation->add('name', new PresenceOf(['message' => 'The name is required',]));
        $validation->add('name', new StringLength([
            [
                "max"             => 50,
                "min"             => 2,
                "messageMaximum"  => "Name too long",
                "messageMinimum"  => "Only initials please",
                "includedMaximum" => true,
                "includedMinimum" => false,
            ]
        ]));

        $validation->add('email', new PresenceOf(['message' => 'The e-mail is required',]));

        $validation->add(
            'email',
            new Email(
                [
                    'message' => 'The e-mail is not valid',
                ]
            )
        );

        $messages = $validation->validate($_POST);

        if (count($messages)) {
            foreach ($messages as $message) {
                echo $message, '<br>';
            }
        }
        return true;
    }
}
