<?php
class BaseEntity
{
    private array $properties = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->properties[$key] = $value;
        }
    }

    // Getter method
    public function get(string $property)
    {
        return $this->properties[$property] ?? null;
    }

    // Setter method
    public function set(string $property, $value): void
    {
        $this->properties[$property] = $value;
    }

    public function __call($method, $args)
    {
        if (strpos($method, 'get') === 0) {
            $property = lcfirst(substr($method, 3));
            return $this->properties[$property] ?? null;
        }

        if (strpos($method, 'set') === 0) {
            $property = lcfirst(substr($method, 3));
            $this->properties[$property] = $args[0] ?? null;
        }
    }

    public function toArray(): array
    {
        return $this->properties;
    }
}
