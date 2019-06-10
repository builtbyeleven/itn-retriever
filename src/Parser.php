<?php

namespace BuiltByEleven\ItnRetriever;

/**
 * 
 */
class Parser
{
	
	function __construct()
	{
		# code...
	}

	public function parse($string)
	{
		preg_match('/(?<=FID=")(.*?)(?=")/', $string, $fid);
		preg_match('/(?<=SRN=")(.*?)(?=")/', $string, $srn);
		preg_match('/(?<=STA=")(.*?)(?=")/', $string, $sta);
		preg_match('/(?<=ITN=")(.*?)(?=")/', $string, $itn);

		return [
			'fid' => $fid[0],
			'srn' => $srn[0],
			'sta' => $sta[0],
			'itn' => $itn[0],
		];
	}
}