<?php

namespace BuiltByEleven\ItnRetriever;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Exception;

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
	
	function __construct($fid, $srn, $aceUsername = null, $acePassword = null)
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
			'URL' => $url,
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
		} catch(RequestException $e) {
			throw new Exception($e->getMessage());
		}

		try {
			$res = $this->client->get($this->itnUrl.$params);
		} catch(RequestException $e) {
			throw new Exception($e->getMessage());
		}

		$this->parse($res->getBody()->getContents());

		return $this->sta;
	}

	/**
	 * Parse result into array.
	 */
	private function parse($string)
	{
		preg_match('/(?<=FID=")(.*?)(?=")/', $string, $fid);
		preg_match('/(?<=SRN=")(.*?)(?=")/', $string, $srn);
		preg_match('/(?<=STA=")(.*?)(?=")/', $string, $sta);
		preg_match('/(?<=ITN=")(.*?)(?=")/', $string, $itn);

		// preg_match(pattern, subject)
		$this->sta = $sta[0];
		$this->itn = $itn[0];

		return $this->sta;
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