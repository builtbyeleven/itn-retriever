<?php

namespace BuiltByEleven\ItnRetriever;

use BuiltByEleven\ItnRetriever\Parser;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 *
 */

class AceFilingInquiryFactory
{
    private $loginUrl = 'https://ace.cbp.dhs.gov/pkmslogin.form';
    private $itnTestUrl = 'https://ace.cbp.dhs.gov/aesd/ta/cert/aes-direct/secured/weblinkFilingInquiry?';
    private $itnProdUrl = 'https://ace.cbp.dhs.gov/aesd/ta/aes-direct/secured/weblinkFilingInquiry?';
    private $itnUrl = '';

    private $itn;
    private $sta;
    
    public function __construct($fid, $srn, $aceUsername = null, $acePassword = null)
    {
        $this->client = new Client(['cookies' => true]);
        getenv('ACE_ENV') != 'prod' ? $this->itnUrl = $this->itnTestUrl : $this->itnUrl = $this->itnProdUrl;

        ($aceUsername) ? $this->aceUsername = $aceUsername : $this->aceUsername = getenv('ACE_USERNAME');
        ($acePassword) ? $this->acePassword = $acePassword : $this->acePassword = getenv('ACE_PASSWORD');

        $this->get($fid, $srn);
    }

    /**
     * Retrieve filing inquiry from ACE.
     */
    private function get($fid, $srn)
    {
        $data = [
            'FID' => $fid,
            'SRN' => $srn,
        ];

        $params = http_build_query($data);

        try {
            $res = $this->client->post($this->loginUrl, [
                'form_params' => [
                    'username' => $this->aceUsername,
                    'password' => $this->acePassword,
                    'login-form-type' => 'pwd',
                    'Login' => 'Login',
                ]
            ]);
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }

        try {
            $res = $this->client->get($this->itnUrl.$params);
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }

        $parser = new Parser();
        $result = $parser->parse($res->getBody()->getContents());

        $this->itn = $result['itn'];
        $this->sta = $result['sta'];

        return $result['sta'];
    }

    /**
     * Get Itn number
     */
    public function getItn()
    {
        return $this->itn;
    }

    /**
     * Get Status
     */
    public function getStatus()
    {
        return $this->sta;
    }

    public function getResponse()
    {
        return [];
    }
}
