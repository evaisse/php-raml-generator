<?php
/**
 * A Yaml writer Helper  
 *
 * @author Emmanuel VAISSE
 * @internal changelog:
 *     Emmanuel VAISSE - 2015-03-31 18:16:23
 *         new file
 */
class RamlGenerator_YamlBuilder
{


    /**
     * Depth indentation level
     * @var integer current indent depth
     */
    protected $depth = 0;


    /**
     * Filepath to append strings to
     * @var string filepath to append data
     */
    protected $file;


    /**
     * @constructor
     */
    public function __construct()
    {
        $this->file = tempnam(sys_get_temp_dir(), 'raml-generator.' . md5(uniqid()));
    }


    /**
     * Gets the Depth indentation level.
     *
     * @return integer current indent depth
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Sets the Depth indentation level.
     *
     * @param integer current indent depth $depth the depth
     *
     * @return self
     */
    protected function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }


    /**
     * Indent one more level
     */
    public function openSection($prefix = null, $extra = false)
    {
        if ($prefix) {
            $this->write($prefix . $extra);
        }
        $this->depth++;
    }


    /**
     * Indent one level less
     */
    public function closeSection()
    {
        $this->depth--;
    }



    /**
     * Decrease indent to root level
     */
    public function root()
    {
        $this->depth = 0;
    }


    /**
     * Write php data to yaml structs
     * @param mixed $data data to write
     */
    public function write($data)
    {
        if (is_string($data)) {
            $data = utf8_encode($data);
            file_put_contents($this->file, (str_repeat('  ', $this->depth)) . $data . "\n", FILE_APPEND);
        }
    }

    /**
     * Check if array is vector or Hash
     * 
     * @param  array   $map An array
     * @return boolean true if array vector, false otherwise
     */
    public function isArrayVector(array $map)
    {
        $keys = array_keys($map);
        $isVector = true;
        foreach ($keys as $value) {
            $isVector = (is_int($value) && $isVector) ? true : false;
        }
        return $isVector;
    }

    /**
     * Write Yaml MAP struct
     * 
     * @param  array|object  $map A map of key value to write
     */
    public function writeMap($map)
    {

        $isVector = is_array($map) ? $this->isArrayVector($map) : false;

        foreach ($map as $key => $value) {

            if (is_array($value) or is_object($value)) {
                $this->openSection($isVector ? "- " : $key . ":");
                $this->writeMap($value);
                $this->closeSection();
            } else {
                if (is_bool($value)) {
                    $value = ($value ? "true" : "false");
                    if ($isVector) {
                        $this->write('- ' . $value);
                    } else {
                        $this->write("$key: " . $value);
                    }
                } else if (is_string($value) && strpos($value, "\n") !== false) {
                    // write multiline string
                    $this->openSection($isVector ? "- " : $key . ":", "|");

                    foreach (explode("\n", $value) as $string) {

                        if (empty($string)) {
                            continue;
                        }

                        $string = $isVector ? '- ' . $this->escape($value) : "$key: " . $this->escape($value);
                        $this->write($value);
                    }

                    $this->closeSection();
                } else {
                    $value = $isVector ? '- ' . $this->escape($value) : "$key: " . $this->escape($value);
                    $this->write($value);
                }
            }
        }
    }

    /**
     * Escape string for YAML output
     * 
     * @param  string $string A string to escape
     * 
     * @return string escape string
     */
    public function escape($string)
    {
        if (preg_match("/[^\w\s\.]/i", $string)) {
            return '"' . str_replace("\'", "'", addslashes(str_replace("\n", " ", $string))) . '"';    
        } else {
            return $string;
        }
    }

    /**
     * FLush yaml file to output buffer
     */
    public function output()
    {
        header('Content-type: text/plain; charset=utf-8');
        readfile($this->file);
    }

}