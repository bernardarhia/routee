<?php

namespace Routee\Validate;

use Glee\Validate\Checker;

class Validator extends Checker
{
    private $message = [];
    private $error = [];
    /**
     * @param array|object $requestBody
     *  This represents the incoming request body from a form or an input
     * 
     * @param array $ruleData
     * This is a key value pair array containing the request key and the rules for that key
     * 
     * Example if an incoming request has a key of "name" then the rule value can be ["name"=>string|required]
     * 
     * @param array $message
     * This is variable is use to override the default messages passed from the validator for the keys in relation to the ruleNames
     */
    public function validate($requestBody, $ruleData, $message = null)
    {
        // turn object into an array
        if (is_object($ruleData)) {
            $ruleData = (array) $ruleData;
        }
        $requestBody = (object) $requestBody;
        foreach ($requestBody as $inputKey => $value) {
            // Value provided by the user
            $inputValue = $value;
            $rules = $ruleData[$inputKey] ?? null;
            // if (!isset($ruleData[$inputKey])) return $this->writeError($ruleData[$inputKey] . "required");

            $splittedRules = explode("|", $rules);
            foreach ($splittedRules as $key => $valueRules) {
                $valueRules = explode(":", $valueRules);
                $rule = $valueRules[0];
                $ruleValue = $valueRules[1] ?? null;


                $renamed = $this->renameKey($splittedRules, $inputKey);

                switch ($rule) {
                    case "string":

                        if (!$this->string($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a string. " . gettype($inputValue) . " provided");
                        break;
                    case "email":
                        if (!$this->email($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "invalid $renamed provided. ");
                        break;
                    case "integer":
                        if (!$this->integer($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a number. " . gettype($inputValue) . " provided");
                        break;
                    case "float":
                        if (!$this->float($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a decimal number.");
                        break;
                    case "array":
                        if (!$this->array($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be an array. " . gettype($inputValue) . " provided");
                        break;
                    case "object":
                        if (!$this->object($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be an object. " . gettype($inputValue) . " provided");
                        break;
                    case "date":
                        if (!$this->date($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "Invalid date provided for $inputKey");
                        break;
                    case "null":
                        if (!$this->null($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be null. " . gettype($inputValue) . " provided");
                        break;
                    case "uuid":
                        if (!$this->uuid($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed is not a valid uuid.");
                        break;
                    case "url":
                        if (!$this->url($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed is not a valid url.");
                        break;
                    case "boolean":
                        if (!$this->boolean($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a boolean. " . gettype($inputValue) . " provided");
                        break;
                    case "time":
                        if (!$this->time($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid time.");
                        break;
                    case "datetime":
                        if (!$this->datetime($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid datetime.");
                        break;
                    case "required":
                        if (empty(trim($inputValue))) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed required");
                        break;
                    case "min":
                        if (!$this->min($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be at least $ruleValue chars long");
                        break;
                    case "max":
                        if (!$this->max($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be at most $ruleValue chars long");
                        break;
                    case "least":
                        if (!$this->least($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be at least $ruleValue");
                        break;
                    case "most":
                        if (!$this->most($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be at most $ruleValue");
                        break;
                    case 'in':
                        if (!$this->in($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be " . implode(" or ", explode(",", $ruleValue)));
                        break;
                    case 'between':
                        if (!$this->between($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed should be between $ruleValue");
                        break;

                    case "alphanumeric":
                        if (!$this->alphanumeric($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be alphanumeric.");
                        break;
                    case "start_with":
                        if (!$this->start_with($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must start with $ruleValue");
                        break;
                    case 'same':
                        if (!$this->same($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be same as $ruleValue");
                        break;
                    case "ip":
                        if (!$this->ip($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid ip.");
                        break;
                    case "ipv4":
                        if (!$this->ipv4($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid ipv4.");
                        break;
                    case "ipv6":
                        if (!$this->ipv6($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid ipv6.");
                        break;
                    case "mac":
                        if (!$this->mac($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid mac address.");
                        break;
                    case "gte":
                        if (!$this->gte($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be greater than or equal to $ruleValue");
                        break;
                    case "lte":
                        if (!$this->lte($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be less than or equal to $ruleValue");
                        break;
                    case "gt":
                        if (!$this->gt($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be greater than $ruleValue");
                        break;
                    case "lt":
                        if (!$this->lt($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be less than $ruleValue");
                        break;
                    case "regex":
                    case "rgx":
                        if (!$this->regex($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must match $ruleValue");
                        break;
                    case "eq":
                    case "equals":
                        if (!$this->eq($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be equal to $ruleValue");
                        break;
                    case "neq":
                    case "not_equals":
                        if (!$this->neq($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must not be equal to $ruleValue");
                        break;
                    case "phone":
                        if (!$this->phone($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be a valid phone number.");
                        break;
                    case "after":
                        if (!$this->after($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be after $ruleValue");
                        break;
                    case "after_or_equal":
                        if (!$this->after_or_equal($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be after or equal to $ruleValue");
                        break;
                    case "before":
                        if (!$this->before($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be before $ruleValue");
                        break;
                    case "before_or_equal":
                        if (!$this->before_or_equal($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be before or equal to $ruleValue");
                        break;

                    case "between_dates":
                        if (!$this->between_dates($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be between $ruleValue");
                        break;
                    case "between_times":
                        if (!$this->between_times($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be between $ruleValue");
                        break;
                    case "between_datetimes":
                        if (!$this->between_datetimes($inputValue, $requestBody->$ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$renamed must be between $ruleValue");
                        break;
                }
            }
        }
        $renamed = null;
        return $this->error;
    }
    private function writeError($error)
    {
        $this->error[] = $error;
    }
    protected function getError()
    {
        return $this->error;
    }
    protected function renameKey($array, $oldKey)
    {
        $newKey = null;
        foreach ($array as $key => $value) {
            $splitted = explode(':', $value);
            if (in_array("renameKey", $splitted)) {
                $newKey = $splitted[1];
                continue;
                // break;
            }
            // continue;
        }
        return $newKey ?? $oldKey;
    }
}