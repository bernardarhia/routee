<?php

namespace Routee\Validate;

use Routee\Validate\Checker;

class Validator extends Checker
{
    private $message = [];
    private $error = [];
    public function validate($inputs, $data, $message = null)
    {
        // turn object into an array
        if (is_object($data)) {
            $data = (array) $data;
        }
        foreach ($inputs as $inputKey => $value) {
            // Value provided by the user
            $inputValue = $value;
            $rules = $data[$inputKey] ?? null;
            $splittedRules = explode("|", $rules);
            foreach ($splittedRules as $key => $value) {
                $value = explode(":", $value);
                $rule = $value[0];
                $ruleValue = $value[1] ?? null;
                switch ($rule) {
                    case "string":
                        if (!$this->string($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a string. " . gettype($inputValue) . " provided");
                        break;
                    case "email":
                        if (!$this->email($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "invalid email provided. ");
                        break;
                    case "integer":
                        if (!$this->integer($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a number. " . gettype($inputValue) . " provided");
                        break;
                    case "float":
                        if (!$this->float($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a decimal number.");
                        break;
                    case "array":
                        if (!$this->array($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be an array. " . gettype($inputValue) . " provided");
                        break;
                    case "object":
                        if (!$this->object($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be an object. " . gettype($inputValue) . " provided");
                        break;
                    case "date":
                        if (!$this->date($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "Invalid date provided for $inputKey");
                        break;
                    case "null":
                        if (!$this->null($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be null. " . gettype($inputValue) . " provided");
                        break;
                    case "uuid":
                        if (!$this->uuid($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey is not a valid uuid.");
                        break;
                    case "url":
                        if (!$this->url($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey is not a valid url.");
                        break;
                    case "boolean":
                        if (!$this->boolean($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a boolean. " . gettype($inputValue) . " provided");
                        break;
                    case "time":
                        if (!$this->time($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid time.");
                        break;
                    case "datetime":
                        if (!$this->datetime($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid datetime.");
                        break;
                    case "required":
                        if (empty($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey required");
                        break;
                    case "min":
                        if (!$this->min($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey should at at least $ruleValue chars long");
                        break;
                    case "max":
                        if (!$this->max($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey should at at most $ruleValue chars long");
                        break;
                    case 'in':
                        if (!$this->in($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey should be in $ruleValue");
                        break;
                    case 'between':
                        if (!$this->between($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey should be between $ruleValue");
                        break;

                    case "alphanumeric":
                        if (!$this->alphanumeric($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be alphanumeric.");
                        break;
                    case "start_with":
                        if (!$this->start_with($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must start with $ruleValue");
                        break;
                    case 'same':
                        if (!$this->same($inputValue, $inputs[$ruleValue])) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be same as $ruleValue");
                        break;
                    case "ip":
                        if (!$this->ip($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid ip.");
                        break;
                    case "ipv4":
                        if (!$this->ipv4($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid ipv4.");
                        break;
                    case "ipv6":
                        if (!$this->ipv6($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid ipv6.");
                        break;
                    case "mac":
                        if (!$this->mac($inputValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be a valid mac address.");
                        break;
                    case "gte":
                        if (!$this->gte($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be greater than or equal to $ruleValue");
                        break;
                    case "lte":
                        if (!$this->lte($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be less than or equal to $ruleValue");
                        break;
                    case "gt":
                        if (!$this->gt($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be greater than $ruleValue");
                        break;
                    case "lt":
                        if (!$this->lt($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be less than $ruleValue");
                        break;
                    case "regex":
                    case "rgx":
                        if (!$this->regex($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must match $ruleValue");
                        break;
                    case "eq":
                    case "equals":
                        if (!$this->eq($inputValue, $ruleValue)) $this->writeError($message[$inputKey . ".$rule"] ?? "$inputKey must be equal to $ruleValue");
                        break;
                }
            }
        }
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
}