<?php

namespace App\Traits;

trait DataManipulatorTrait
{
    public array $properties;
    public function make(): self
    {
        $this->properties = get_object_vars($this);
        return $this;
    }

    public function removeNulls(): self
    {

        foreach ($this->properties as $key => $value) {
            if ($value == null) {
                unset($this->properties[$key]);
            }
        }
        return $this;
    }

    public function removeKeys(array $exclude): self
    {

        foreach ($this->properties as $key => $value) {
            if (in_array($key, $exclude)) {
                unset($this->properties[$key]);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return $this->properties;
    }
}
