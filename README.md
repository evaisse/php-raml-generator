Quickstart
===

Simple exemple for manual api usage

    <?php

    $ramlMethod = new RamlGenerator_ApiMethod($def['verb'], $url);
    $ramlMethod->setVersion($def['version']);
    $ramlMethod->setDescription($description);

    foreach ($desc['param'] as $param) {

        $param = new RamlGenerator_ApiMethodParameter($pname, $ptype);
        $param->setRequired(strpos($pdesc, "OPTIONAL") !== false);

        $description = utf8_encode($pdesc);
        $description = iconv("utf-8","ASCII//TRANSLIT//IGNORE", $description);

        $param->setDescription($description);
        $param->setExample($pexample);


        if ($ramlMethod->getVerb() == 'get') {
            foreach ($params as $param) {
                $ramlMethod->addQueryParameter($param);
            }
        } else {

            // $body = array();
            $schema = array(
                'type'       => 'object',
                'required'   => true,
                'properties' => array(),
            );

            foreach ($params as $param) {
                $schema['properties'][$param->getName()] = array(
                    "type"        => $param->getType(),
                    "required"    => $param->isRequired(),
                    "description" => $param->getDescription(),
                );
            }

            $ramlMethod->addRequestBody('application/json', json_encode($schema), false);
        }

    }

    $response = new RamlGenerator_ApiMethodResponse(200);
    $ramlMethod->addResponse($response);

    $apis[] = $ramlMethod;


    /*
        Generate output
    */



    $g = new RamlGenerator();
    $g->setVersion($version);
    $g->setTitle('BRS API');
    $g->setBaseUri(
        $httpDomain . '/services/api/v' . $g->getVersion()
    );

    $g->setSecuritySchemes(array(
        array(
            'oauth_2_0' => $g->getOauth2Scheme($httpDomain . '/oauth/', $httpDomain . '/oauth/access_token/', array('credentials'))
        ),
    ));

    $g->setSecuredBy(array(
        'oauth_2_0'
    ));

    $g->setVersion($version);

    foreach ($apis as $key => $method) {

        if (floatval($method->getVersion()) > floatval($g->getVersion())) {
            continue;
        }

        $g->addApiMethod($method);

    }

    $g->output();


