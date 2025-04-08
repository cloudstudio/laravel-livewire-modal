<?php

namespace Cloudstudio\Modal\Services;

use Exception;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Reflector;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use ReflectionClass;
use ReflectionProperty;

/**
 * Service for managing modal components.
 */
class ModalManagerService
{
    /**
     * Create a new modal component instance.
     *
     * @param  string  $component  The component name
     * @param  array  $arguments  The component arguments
     * @param  array  $modalAttributes  Additional modal attributes
     * @return array The modal component data
     *
     * @throws Exception If the component is an abstract class
     */
    public function createModalComponent(string $component, array $arguments = [], array $modalAttributes = []): array
    {
        $componentClass = app(ComponentRegistry::class)->getClass($component);
        $reflect = new ReflectionClass($componentClass);

        if ($reflect->isAbstract()) {
            throw new Exception("[{$componentClass}] is an abstract class.");
        }

        $id = md5($component.serialize($arguments));

        $arguments = collect($arguments)
            ->merge($this->resolveComponentProps($arguments, new $componentClass))
            ->all();

        return [
            'id' => $id,
            'data' => [
                'name' => $component,
                'arguments' => $arguments,
                'modalAttributes' => array_merge([
                    'closeOnClickAway' => $componentClass::closeModalOnClickAway(),
                    'closeOnEscape' => $componentClass::closeModalOnEscape(),
                    'closeOnEscapeIsForceful' => $componentClass::closeModalOnEscapeIsForceful(),
                    'dispatchCloseEvent' => $componentClass::dispatchCloseEvent(),
                    'destroyOnClose' => $componentClass::destroyOnClose(),
                    'maxWidth' => $componentClass::modalMaxWidth(),
                    'maxWidthClass' => $componentClass::modalMaxWidthClass(),
                    'modalFlyout' => $componentClass::modalFlyout(),
                    'modalFlyoutPosition' => $componentClass::modalFlyoutPosition(),
                ], $modalAttributes),
            ],
        ];
    }

    /**
     * Resolve component properties.
     *
     * @param  array  $attributes  The attributes to resolve
     * @param  Component  $component  The component instance
     * @return Collection The resolved properties
     */
    public function resolveComponentProps(array $attributes, Component $component): Collection
    {
        return $this->getPublicPropertyTypes($component)
            ->intersectByKeys($attributes)
            ->map(function ($className, $propName) use ($attributes) {
                return $this->resolveParameter($attributes, $propName, $className);
            });
    }

    /**
     * Resolve a parameter value.
     *
     * @param  array  $attributes  The attributes array
     * @param  string  $parameterName  The parameter name
     * @param  string  $parameterClassName  The parameter class name
     * @return mixed The resolved parameter
     *
     * @throws ModelNotFoundException If the model cannot be resolved
     */
    protected function resolveParameter(array $attributes, string $parameterName, string $parameterClassName)
    {
        $parameterValue = $attributes[$parameterName];

        if ($parameterValue instanceof UrlRoutable) {
            return $parameterValue;
        }

        if (enum_exists($parameterClassName)) {
            $enum = $parameterClassName::tryFrom($parameterValue);

            if ($enum !== null) {
                return $enum;
            }
        }

        $instance = app()->make($parameterClassName);

        if (! $model = $instance->resolveRouteBinding($parameterValue)) {
            throw (new ModelNotFoundException)->setModel(get_class($instance), [$parameterValue]);
        }

        return $model;
    }

    /**
     * Get public property types for a component.
     *
     * @param  Component  $component  The component instance
     * @return Collection The public property types
     */
    public function getPublicPropertyTypes(Component $component): Collection
    {
        return collect($component->all())
            ->map(function ($value, $name) use ($component) {
                return Reflector::getParameterClassName(new ReflectionProperty($component, $name));
            })
            ->filter();
    }
}
