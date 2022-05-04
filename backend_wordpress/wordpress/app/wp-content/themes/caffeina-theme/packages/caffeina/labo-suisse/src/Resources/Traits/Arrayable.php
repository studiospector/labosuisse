<?php

namespace Caffeina\LaboSuisse\Resources\Traits;

use ReflectionObject;
use ReflectionProperty;

trait Arrayable
{
    public function toArray()
    {
        $attributes = [];

        $properties = (new ReflectionObject($this))
            ->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $attributes[$property->name] = $this->{$property->name};
        }

        return $attributes;
    }
}
