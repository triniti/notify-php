<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

abstract class Base implements \JsonSerializable
{
    public function jsonSerialize()
    {
        $properties = $this->getSetProperties();

        return $properties;
    }

    /**
     * Check to see if required properties are set
     *
     * @param array $properties
     * @throws \Exception
     */
    protected function checkRequiredProperties(array $properties)
    {
        foreach ($this->required() as $requiredProperty) {
            if (!isset($properties[$requiredProperty])) {
                throw new \Exception(sprintf('Required property [%s] is not set on [%s]', $requiredProperty,
                    get_class($this)));
            }
        }
    }

    /**
     * @return array
     */
    protected function getSetProperties()
    {
        $properties = array_filter(get_object_vars($this), function ($v) {
            return ($v !== null && $v !== []);
        });

        return $properties;
    }

    /**
     * Validates required properties are set and are of the correct type
     *
     * @throws \Exception
     */
    public function validateProperties()
    {
        $properties = $this->getSetProperties();
        $constraints = $this->constraints();

        foreach ($properties as $k => $v) {
            if (!isset($constraints[$k])) {
                throw new \Exception(
                    sprintf(
                        '[%s] is not a valid property or matching constraint is missing in [%s]',
                        $k,
                        get_class($this)
                    )
                );
            }

            $types = explode('|', $constraints[$k]);

            // can be multiple types
            if (count($types) > 1) {
                $maxNumOfExceptions = count($types);
                $numOfExceptions = 0;
                foreach ($types as $type) {
                    try {
                        $this->checkTypes($type, $v, $k);
                    } catch (\Exception $e) {
                        $numOfExceptions++;
                    }
                }

                if ($numOfExceptions === $maxNumOfExceptions) {
                    throw new \Exception(sprintf('[%s] is not of type [%s]', $k, implode('|', $types)));
                }
            } else {
                // check to see if type should be array
                if (strrpos($types[0], '[]') > 0) {
                    if (!is_array($v)) {
                        throw new \Exception(sprintf('[%s] is not an Array', $k));
                    }
                    $types[0] = str_replace('[]', '', $types[0]);

                    foreach ($v as $k2 => $v2) {
                        $this->checkTypes($types[0], $v2, $k . '[' . $k2 . ']');
                    }
                } else {
                    $this->checkTypes($types[0], $v, $k);
                }
            }
        }

        // Check to see if required properties are set
        $this->checkRequiredProperties($properties);

        // check to see that each property that is set is valid
        foreach ($properties as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($v2 instanceof Base) {
                        $v2->validateProperties();
                    }
                }
            } else {
                if ($v instanceof Base) {
                    $v->validateProperties();
                }
            }
        }
    }

    /**
     * @param $type
     * @param $value
     * @param $property
     * @throws \Exception
     */
    protected function checkTypes($type, $value, $property)
    {
        switch ($type) {
            case 'string':
                if (!is_string($value)) {
                    throw new \Exception(sprintf('[%s] is not of type [%s]::[%s]', $property, $type, get_class($this)));
                }
                break;
            case 'integer':
                if (!is_integer($value)) {
                    throw new \Exception(sprintf('[%s] is not of type [%s]::[%s]', $property, $type, get_class($this)));
                }
                break;
            case 'float':
                if (!is_float($value)) {
                    throw new \Exception(sprintf('[%s] is not of type [%s]::[%s]', $property, $type, get_class($this)));
                }
                break;
            case 'boolean':
                if (!is_bool($value)) {
                    throw new \Exception(sprintf('[%s] is not of type [%s]::[%s]', $property, $type, get_class($this)));
                }
                break;
            default:
                if (!$value instanceof $type) {
                    throw new \Exception(sprintf('[%s] is not instance of [%s]::[%s]', $property, $type, get_class($this)));
                }
        }
    }

    /**
     * Define required properties.
     */
    protected function required()
    {
        return [];
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return [];
    }

    /**
     * Implements __toString().
     */
    public function __toString()
    {
        return json_encode($this, JSON_UNESCAPED_SLASHES);
    }
}

