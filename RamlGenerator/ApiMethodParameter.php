<?php
/**
 * RAML Api method parameters description 
 *
 * @author Emmanuel VAISSE
 * @internal changelog:
 *     Emmanuel VAISSE - 2015-06-05 10:51:52
 *         new file
 */
class RamlGenerator_ApiMethodParameter
{

    /** 
     * Name of the parameters
     * @var string
     */
    protected $name;


    /**
     * Parameter type
     * @var string
     */
    protected $type;


    /**
     * Parameter description
     * @var string
     */
    protected $description;


    /**
     * Parameter default value
     * @var mixed
     */
    protected $defaultValue;


    /**
     * If required or not
     * @var boolean
     */
    protected $required = false;

    /**
     * An example
     * @var string
     */
    protected $example = '';


    /**
     * Get all available types
     * @return array<string> 
     */
    public static function getAvailableTypes()
    {
        return array(
            "string",
            "number",
            "integer", 
            "file",
            "boolean",
            "date",
        );
    }


    /**
     * Create a new param 
     * 
     * @param string $name name of the param
     * @param string $type type of the param
     */
    public function __construct($name, $type)
    {
        $this->setName($name);
        $this->setType($type);
    }

    /**
     * Gets the If required or not.
     *
     * @return boolean true if required, false otherwise
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Sets the If required or not.
     *
     * @param boolean $required the required
     *
     * @return self
     */
    public function setRequired($required)
    {
        $this->required = (bool)$required;

        return $this;
    }

    /**
     * Gets the Name of the parameters.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name of the parameters.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Parameter type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the Parameter type.
     *
     * @param string $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $types = self::getAvailableTypes();

        $type = strtolower($type);

        if (in_array($type, array('bool'), true)) {
            $type = "boolean";
        }

        if (in_array($type, array('float'), true)) {
            $type = "number";
        }

        if (in_array($type, $types, true)) {
            $this->type = $type;
        } else {
            $this->type = 'string';
        }

        return $this;
    }

    /**
     * Gets the Parameter description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the Parameter description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the Parameter default value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the Parameter default value.
     *
     * @param mixed $defaultValue the default value
     *
     * @return self
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }


    /**
     * Gets an example.
     *
     * @return string
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * Sets an example.
     *
     * @param string $example the example
     *
     * @return self
     */
    public function setExample($example)
    {
        $this->example = $example;

        return $this;
    }
}