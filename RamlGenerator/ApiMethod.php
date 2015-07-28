<?php


class RamlGenerator_ApiMethod
{

    /**
     * URI pattern to api method
     * @var string url pattern
     */
    protected $url;


    /**
     * API method version, i.e "3.1"
     * @var string API method version, i.e "3.1"
     */
    protected $version;


    /**
     * HTTP Verb to trigger method
     * @var string http verb that trigger method
     */
    protected $verb;

    /**
     * Required Arguments
     * @var array arguments
     */
    protected $arguments;


    /**
     * Description of the feature/method
     * @var string Description of the feature/method
     */
    protected $description;


    /**
     * Query parameters Definition
     * @var array<RamlGenerator_ApiMethodParameter> Query parameters Definition
     */
    protected $queryParameters = array();


    /**
     * ACL for this method
     * @var RamlGenerator_Acl ACL for this method
     */
    protected $authorization;

    /**
     * A list of method responses description, indexed by http response code
     * @var array<RamlGenerator_ApiMethodResponse> A list of method responses description, indexed by http response code
     */
    protected $responses;



    /**
     * Api Request bodies
     * @var array a list of request bodies
     */
    protected $requestBodies = array();


    /**
     * Define an API method
     * 
     * @param string http verb that trigger method
     * @param string url pattern
     */
    public function __construct($verb, $url)
    {
        $this->setVerb($verb);
        $this->setUrl($url);
    }


    /**
     * Gets the URI pattern to api method.
     *
     * @return string url pattern
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the URI pattern to api method.
     *
     * @param string url pattern $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the API method version, i.e "3.1".
     *
     * @return string API method version, i.e "3.1"
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the API method version, i.e "3.1".
     *
     * @param string API method version, i.e "3.1"
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Gets the HTTP Verb to trigger method.
     *
     * @return string http verb that trigger method
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Sets the HTTP Verb to trigger method.
     *
     * @param string http verb that trigger method
     *
     * @return self
     */
    public function setVerb($verb)
    {
        $this->verb = $verb;

        return $this;
    }

    /**
     * Gets the Required Arguments.
     *
     * @return array arguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Sets the Required Arguments.
     *
     * @param array arguments $arguments the arguments
     *
     * @return self
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Gets the Description of the feature/method.
     *
     * @return string Description of the feature/method
     */
    public function getDescription()
    {
        return trim($this->description);
    }

    /**
     * Sets the Description of the feature/method.
     *
     * @param string Description of the feature/method
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the Query parameters Definition.
     *
     * @return RamlGenerator_ApiMethodParameters Query parameters Definition
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * Sets the Query parameters Definition.
     *
     * @param RamlGenerator_ApiMethodParameters Query parameters Definition
     *
     * @return self
     */
    public function setQueryParameters(array $queryParameters)
    {
        $this->queryParameters = $queryParameters;

        return $this;
    }

    /**
     * Add a query parameter to this method
     * 
     * @param RamlGenerator_ApiMethodParameter $parameter query parameter of the method
     *
     * @return self
     */
    public function addQueryParameter(RamlGenerator_ApiMethodParameter $parameter)
    {
        $this->queryParameters[$parameter->getName()] = $parameter;
        return $this;
    }

    /**
     * Gets the ACL for this method.
     *
     * @return RamlGenerator_Acl ACL for this method
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * Sets the ACL for this method.
     *
     * @param RamlGenerator_Acl ACL for this method
     *
     * @return self
     */
    public function setAuthorization(RamlGenerator_Acl $authorization)
    {
        $this->authorization = $authorization;

        return $this;
    }


    /**
     * Get a unique for this service definition key 
     * 
     * @return string
     */
    public function getKey()
    {
        return join(' - ', array($this->getUrl(), $this->getVerb()));
    }



    /**
     * Gets the A list of method responses description, indexed by http response code.
     *
     * @return array<RamlGenerator_ApiMethodResponse> A list of method responses description, indexed by http response code
     */
    public function getResponses()
    {
        return $this->responses;
    }


    /**
     * Sets the A list of method responses description, indexed by http response code.
     *
     * @param RamlGenerator_ApiMethodResponse $response A list of method responses description, indexed by http response code
     *
     * @return self
     */
    public function addResponse(RamlGenerator_ApiMethodResponse $response)
    {
        $this->responses[$response->getCode()] = $response;

        return $this;
    }


    /**
     * Sets the A list of method responses description, indexed by http response code.
     *
     * @param array<RamlGenerator_ApiMethodResponse> A list of method responses description, indexed by http response code $responses the responses
     *
     * @return self
     */
    protected function setResponses($responses)
    {
        $this->responses = $responses;

        return $this;
    }


    /**
     * Gets the Api Request bodies.
     *
     * @return array a list of request bodies
     */
    public function getRequestBodies()
    {
        return $this->requestBodies;
    }

    /**
     * Sets the Api Request bodies.
     *
     * @param array a list of request bodies $requestBodies the request bodies
     *
     * @return self
     */
    public function setRequestBodies($requestBodies)
    {
        $this->requestBodies = $requestBodies;

        return $this;
    }


    /**
     * Request body 
     * 
     * @param string $contentType a mime content type encoding for body request
     * @param array $map a map for request body
     */
    public function addRequestBody($contentType, $map)
    {
        $this->requestBodies[$contentType] = $map;
    }

}
