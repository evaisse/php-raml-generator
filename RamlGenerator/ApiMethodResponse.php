<?php
/**
 * RAML Api method response description 
 *
 * @author Emmanuel VAISSE
 * @internal changelog:
 *     Emmanuel VAISSE - 2015-06-05 10:51:52
 *         new file
 */
class RamlGenerator_ApiMethodResponse
{


    /**
     * HTTP response code
     * @var integer
     */
    protected $code;



    /**
     * Content type 
     * @var string
     */
    protected $contentType = "application/json";



    /**
     * Response Body example
     * @var string
     */
    protected $example = '';



    /**
     * Reference description, can be include or schemas
     * @var string
     */
    protected $reference;


    /**
     * Description of the given response
     * @var string
     */
    protected $description = '';


    /**
     * HTTP Headers return by responses
     * @var array
     */
    protected $headers = array();


    /**
     * Construct http code
     * 
     * @param integer $code HTTP response code
     */
    public function __construct($code)
    {
        $this->setCode((int)$code);
    }


    /**
     * Gets the HTTP response code.
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets the HTTP response code.
     *
     * @param integer $code the code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets the Content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the Content type.
     *
     * @param string $contentType the content type
     *
     * @return self
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Gets the Response Body example.
     *
     * @return string
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * Sets the Response Body example.
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

    /**
     * Gets the Reference description, can be include or schemas.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Sets the Reference description, can be include or schemas.
     *
     * @param string $reference the reference
     *
     * @return self
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Gets the HTTP Headers return by responses.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets the HTTP Headers return by responses.
     *
     * @param array $headers the headers
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Gets the Description of the given response.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the Description of the given response.
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
}