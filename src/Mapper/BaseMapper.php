<?php

namespace Sebastian\PhpEcommerce\Mapper;

abstract class BaseMapper
{
    /**
     * Dynamically instantiating a DTO class by examining its constructor and
     * mapping associative array to constructor arguements
     * @param array $data
     * @param string $dtoClass
     * @throws \InvalidArgumentException
     * @return object|null
     */
    protected function mapArrayToDTO(array $data, string $dtoClass)
    {
        // reports information about the $dtoClass
        $reflection = new \ReflectionClass($dtoClass);
        // Get the DTO constructor
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            throw new \InvalidArgumentException("DTO class $dtoClass must have a constructor.");
        }

        // Get constructor parameters
        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType();
            $value = $data[$paramName] ?? null; // Fallback to null if missing

            if ($paramType && !$paramType->isBuiltin()) {
                // Check if it's another DTO
                $dtoType = $paramType->getName();
                if (class_exists($dtoType) && is_array($value)) {
                    // Recursively map nested DTOs
                    $value = $this->mapArrayToDTO($value, $dtoType);
                }
            }

            // Handle null defaults to prevent TypeErrors
            if ($value === null && $paramType?->allowsNull()) {
                $args[] = null;
            } else {
                $args[] = $value;
            }
        }

        return $reflection->newInstanceArgs($args);
    }

    protected function mapArrayToDTOArray(array $data, string $dtoClass): array
    {
        return array_map(fn($item) => $this->mapArrayToDTO($item, $dtoClass), $data);
    }
}
