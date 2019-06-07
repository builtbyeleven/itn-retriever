<?php

namespace BuiltByEleven\ItnRetriever;

use GuzzleHttp\Client;

/**
 * 
 */

class ItnFactory
{
	private $loginUrl = 'https://ace.cbp.dhs.gov/pkmslogin.form';
	private $itnPage = 'https://ace.cbp.dhs.gov/aesd/ta/cert/aes-direct/secured/weblinkFilingInquiry?';

	private $itn;
	private $sta;
	
	function __construct()
	{
		$this->client = new Client(['cookies' => true]);
	}

	public function get($username, $password, $fid, $srn, $url)
	{	
		$data = [
			'FID' => $fid,
			'SRN' => $srn,
			'URL' => $url,
		];

		$params = http_build_query($data);

		$res = $this->client->post($this->loginUrl, [
			'form_params' => [
				'username' => $username,
				'password' => $password,
				'login-form-type' => 'pwd',
				'Login' => 'Login',
			]
		]);

		$res = $this->client->get($this->itnPage.$params);

		$this->parse($res->getBody()->getContents());

		return $this->sta;
	}

	private function parse($string)
	{
		# code...
		preg_match('/(?<=FID=")(.*?)(?=")/', $string, $fid);
		preg_match('/(?<=SRN=")(.*?)(?=")/', $string, $srn);
		preg_match('/(?<=STA=")(.*?)(?=")/', $string, $sta);
		preg_match('/(?<=ITN=")(.*?)(?=")/', $string, $itn);

		// preg_match(pattern, subject)
		$this->sta = $sta[0];
		$this->itn = $itn[0];

		return $this->sta;
	}

	public function getItn()
	{
		return $this->itn;
	}

	public function getStatus()
	{
		return $this->sta;
	}
}