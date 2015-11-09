<?php

namespace Dmlogic\Traits;

trait Gettable {

    public function set($key,$value)
    {
        $this->enforceGettable();
        $mutator = $this->mutatorName($key,'set');
        if(method_exists($this,$mutator)) {
            $this->$mutator($value);
            return;
        }

        $this->data[$key] = $value;
    }

    public function get($key)
    {
        $this->enforceGettable();
        if(!array_key_exists($key, $this->data)) {
            return null;
        }

        $mutator = $this->mutatorName($key,'get');
        if(method_exists($this,$mutator)) {
            return $this->$mutator();
        }

        return $this->data[$key];
    }

    public function getAll()
    {
        return $this->data;
    }

    private function enforceGettable()
    {
        if(!property_exists($this,'data')) {
            throw new \Exception('$data not present');
        }
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key,$value)
    {
        $this->set($key,$value);
    }

    private function mutatorName($key,$type)
    {
        return $type.ucwords(str_replace(['-', '_'], ' ', $key)).'Attribute';
    }
}