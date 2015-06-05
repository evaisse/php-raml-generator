<?php
/**
 * RAML generator helper 
 *
 * @author Emmanuel VAISSE
 * @internal changelog:
 *     Emmanuel VAISSE - 2015-03-31 18:10:27
 *         new file
 */
class RamlGenerator
{


    /**
     * yaml writer helper
     * @var RamlGenerator_YamlBuilder yaml writer helper
     */
    protected $yaml;

    /**
     * current RAML version
     * @var string current raml version
     */
    protected $ramlVersion = "0.8";

    /**
     * APIs list 
     * @var array API list
     */
    protected $apis;

    /**
     * base uri for api
     * @var string base uri for api
     */
    protected $baseUri;

    /**
     * API purpose
     * @var string API purpose
     */
    protected $title;

    /**
     * current API version
     * @var string current API version
     */
    protected $version;

    /**
     * A list of available protocol
     * @var array
     */
    protected $protocols = array();

    /**
     * A list of secured by protocols
     * 
     * @var array
     */
    public $securedBy = array();

    /**
     * A list of security schemes
     * 
     * @var array
     */
    public $securitySchemes = array();


    protected $urlTree = array();

    /**
     * construct
     */
    public function __construct()
    {
        $this->setBuilder(new sfYaml());
    }

    /**
     * Build yaml tree
     * 
     * @param  [type] &$tree  [description]
     * @param  [type] $slices [description]
     * @param  [type] $method [description]
     * @return [type]         [description]
     */
    public function tree(&$tree, $slices, $method)
    {
        $segment = array_shift($slices);

        $segment = '/' . $segment;

        if (count($slices) == 1) {
            if (empty($tree[$segment])) {
                $tree[$segment] = array();
            }
            $tree[$segment]['/' . $slices[0]] = isset($tree[$segment]['/' . $slices[0]]) ? $tree[$segment]['/' . $slices[0]] : array(); 
            $tree[$segment]['/' . $slices[0]][$method->getVerb()] = $this->drawMethod($method);
            return true;
        }

        if (!isset($tree[$segment])) {
            $tree[$segment] = array();
        }

        $this->tree($tree[$segment], $slices, $method);
    }


    /**
     * Draw array struct of method definition
     * 
     * @param  RamlGenerator_ApiMethod $method [description]
     * @return [type]                          [description]
     */
    public function drawMethod(RamlGenerator_ApiMethod $method)
    {
        $map = array();

        $parameters = array();

        $map['description'] = 'v' . $method->getVersion() . ' - ' . $method->getDescription();

        foreach ($method->getQueryParameters() as $name => $param) {
            $parameters[$name] = array(
                'displayName' => $param->getName(),
                'type'        => $param->getType(),
                'description' => $param->getDescription(),
                'example'     => $param->getExample(),
                'required'    => $param->isRequired()
            );
        }

        if (count($method->getResponses())) {
            foreach ($method->getResponses() as $response) {
                $map['responses'][$response->getCode()] = $this->drawResponse($response);

                $body = array();
                $body[$response->getContentType()] = array();
                $body[$response->getContentType()]['example'] = $response->getExample();
                
            }
        }

        if ($parameters) {
            $map['queryParameters'] = $parameters;
        }

        if (count($method->getRequestBodies())) {

            $map['body'] = array();

            foreach ($method->getRequestBodies() as $key => $value) {

                $map['body'][$key] = array();
                $map['body'][$key]['schema'] = $value["schema"];

                if ($value['example']) {
                    $map['body'][$key]['example'] = $value['example'];
                }

            }
        }

        return $map;

    }

    /**
     * [drawResponse description]
     * @param  RamlGenerator_ApiMethodResponse $response [description]
     * @return [type]                                    [description]
     */
    public function drawResponse(RamlGenerator_ApiMethodResponse $response)
    {

        $bodies = array();

        $bodies[$response->getContentType()] = array(
            'example' => $response->getExample(),
        );

        $map = array(
            'description' => $response->getDescription(),
            'body'        => $bodies,
        );
        return $map;
    }

    /**
     * [generate description]
     * @return [type] [description]
     */
    public function generate()
    {


        print "#%RAML " . $this->getRamlVersion() . "\n";
        print "---\n";

        foreach (array('title', 'baseUri', 'version') as $key) {
            print $this->yaml->dump(array(
                $key => $this->{"get$key"}(),
            ));
        }

        if (count($this->getSecuritySchemes())) {
            print $this->yaml->dump(array(
                'securitySchemes' => $this->getSecuritySchemes(),
                'securedBy'      => $this->getSecuredBy(),
            ), 10);
        }


        $currentDomain = false;

        ksort($this->apis);

        $tree = array();

        foreach ($this->apis as $key => $method) {
            $u = $method->getUrl();
            $u = trim($u, '/');
            $slices = explode('/', $u);
            $this->tree($tree, $slices, $method);
        }

        $this->out = array();


        // $this->drawTree($tree);

        print $this->yaml->dump($tree, 10);

    }


    /**
     * [output description]
     * @return [type] [description]
     */
    public function output()
    {
        header('Content-type: text/plain; charset=utf-8');
        $this->generate();
    }


    /*
                                                                                                 
                                                                                                 
                                                                                                 
         ####   #####  ####     #####  ###### ###### #### ### ### #### ###### ####  ###  ### ### 
          ###    ## ##  ##       ## ##  ##  #  ##  #  ##   ##  #   ##  # ## #  ##  ## ##  ##  #  
          # #    ## ##  ##       ## ##  ####   ####   ##   ### #   ##    ##    ##  ## ##  ### #  
         #####   ####   ##       ## ##  ##     ##     ##   #####   ##    ##    ##  ## ##  #####  
         ## ##   ##     ##       ## ##  ## ##  ##     ##   ## ##   ##    ##    ##  ## ##  ## ##  
        ### ### ####   ####     #####  ###### ####   #### ### ##  ####  ####  ####  ###  ### ##  
                                                                                                 
                                                                                                 
     */


    /**
     * Sets the yaml writer helper.
     *
     * @param RamlGenerator_YamlBuilder yaml writer helper $yaml the builder
     *
     * @return self
     */
    protected function setBuilder($yaml)
    {
        $this->yaml = $yaml;

        return $this;
    }


    /**
     * Gets the base uri for api.
     *
     * @return string base uri for api
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Sets the base uri for api.
     *
     * @param string base uri for api $baseUri the base uri
     *
     * @return self
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Gets the API purpose.
     *
     * @return string API purpose
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the API purpose.
     *
     * @param string API purpose $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the current RAML version.
     *
     * @return string current raml version
     */
    public function getRamlVersion()
    {
        return $this->ramlVersion;
    }

    /**
     * Sets the current RAML version.
     *
     * @param string current raml version $ramlVersion the raml version
     *
     * @return self
     */
    protected function setRamlVersion($ramlVersion)
    {
        $this->ramlVersion = $ramlVersion;

        return $this;
    }

    /**
     * Gets the current API version.
     *
     * @return string current API version
     */
    public function getVersion()
    {
        return ltrim($this->version, 'v');
    }

    /**
     * Sets the current API version.
     *
     * @param string current API version $version the version
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }



    /**
     * Add an API to RAML definition
     *     
     * @param string $urlPattern [description]
     * @param string $verb       [description]
     * @param string $version    A api version, like 
     * @param array $def        [description]
     */
    public function addApiMethod(RamlGenerator_ApiMethod $method)
    {
        // always keep the more up to date version of an api
        if (isset($this->apis[$method->getKey()])) {
            if ($this->apis[$method->getKey()]->getVersion() > $method->getVersion()) {
                return false;
            }
        }

        $this->apis[$method->getKey()] = $method;
        return true;
    }


    /**
     * Gets the APIs list.
     *
     * @return array API list
     */
    public function getApis()
    {
        return $this->apis;
    }

    /**
     * Sets the APIs list.
     *
     * @param array API list $apis the apis
     *
     * @return self
     */
    protected function setApis($apis)
    {
        $this->apis = $apis;

        return $this;
    }

    /**
     * Gets the A list of available protocol.
     *
     * @return array
     */
    public function getProtocols()
    {
        return $this->protocols;
    }

    /**
     * Sets the A list of available protocol.
     *
     * @param array $protocols the protocols
     *
     * @return self
     */
    public function setProtocols(array $protocols)
    {
        $this->protocols = $protocols;

        return $this;
    }

    /**
     * Gets the A list of secured by protocols.
     *
     * @return array
     */
    public function getSecuredBy()
    {
        return $this->securedBy;
    }

    /**
     * Sets the A list of secured by protocols.
     *
     * @param array $securedBy the secured by
     *
     * @return self
     */
    public function setSecuredBy(array $securedBy)
    {
        $this->securedBy = $securedBy;

        return $this;
    }

    /**
     * Gets the A list of security schemes.
     *
     * @return array
     */
    public function getSecuritySchemes()
    {
        return $this->securitySchemes;
    }

    /**
     * Sets the A list of security schemes.
     *
     * @param array $securitySchemes the security schemes
     *
     * @return self
     */
    public function setSecuritySchemes(array $securitySchemes)
    {
        $this->securitySchemes = $securitySchemes;

        return $this;
    }



    /**
     * Get RAML description of OAuth 2.0 scheme
     * 
     * @param  string $authorizationUri Authorization URI
     * @param  string $accessTokenUri   Access token URI
     * @param  string $grants           A list of array grants to provide ( 'credentials', "code", "token" )
     * @return array raml description of OAuth 2.0 scheme
     */
    public function getOauth2Scheme($authorizationUri, $accessTokenUri, $grants)
    {
        $map = array();

        $scheme = array();

        $settings = array(
            "authorizationUri"      => $authorizationUri,
            "accessTokenUri"        => $accessTokenUri,
            "authorizationGrants"   => $grants
        );

        $scheme = array (
            'headers' => array(
                'Authorization' =>  array(
                    'description' => 'Used to send a valid OAuth 2 access token. Do not use with the "access_token" query string parameter.',
                    'type' => 'string',
                ),
            ),
            'queryParameters' =>  array(
                'access_token' => array(
                    'description' => 'Used to send a valid OAuth 2 access token. Do not use together with the "Authorization" header',
                    'type' => 'string',
                ),
            ),
            'responses' => array(
                401 => array(
                    'description' => 'Bad or expired token. This can happen if the user or server revoked or expired an access token. To fix, you should re-authenticate the user.',
                ),
                403 => array(
                    'description' => 'Bad OAuth request (wrong consumer key, bad nonce, expired timestamp...). Unfortunately, re-authenticating the user won\'t help here.',
                ),
            ),
        );

        $map['description'] = "BRS Api supports OAuth 2.0 for authenticating all API requests.";

        $map['type'] = "OAuth 2.0";

        $map['describedBy'] = $scheme;
        $map['settings'] = $settings;

        return $map;

        /*
        description: |
            Dropbox supports OAuth 2.0 for authenticating all API requests.
        type: OAuth 2.0
        describedBy:
            headers:
                Authorization:
                    description: |
                       Used to send a valid OAuth 2 access token. Do not use
                       with the "access_token" query string parameter.
                    type: string
            queryParameters:
                access_token:
                    description: |
                       Used to send a valid OAuth 2 access token. Do not use together with
                       the "Authorization" header
                    type: string
            responses:
                401:
                    description: |
                        Bad or expired token. This can happen if the user or Dropbox
                        revoked or expired an access token. To fix, you should re-
                        authenticate the user.
                403:
                    description: |
                        Bad OAuth request (wrong consumer key, bad nonce, expired
                        timestamp...). Unfortunately, re-authenticating the user won't help here.
        settings:
          authorizationUri: https://www.dropbox.com/1/oauth2/authorize
          accessTokenUri: https://api.dropbox.com/1/oauth2/token
          authorizationGrants: [ code, token ]
        */
    }
}