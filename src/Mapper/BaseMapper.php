<?php

namespace Sebastian\PhpEcommerce\Mapper;

abstract class BaseMapper
{
    protected function mapArrayToDTO(array $data, string $dtoClass)
    {
        // Get the DTO constructor
        $reflection = new \ReflectionClass($dtoClass);
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

    public function mapArrayToDTOArray(array $data, string $dtoClass): array
    {
        return array_map(fn($item) => $this->mapArrayToDTO($item, $dtoClass), $data);
    }
}
