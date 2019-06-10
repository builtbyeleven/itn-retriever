<?php

namespace BuiltByEleven\ItnRetriever\Tests;

use BuiltByEleven\ItnRetriever\Parser;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class ParseTest extends TestCase
{
	
	public function testParse()
	{
		$parser = new Parser();
		$result = $parser->parse('<!-- FID="123456789" -->
<!-- SRN="99-9-8765-20" -->
<!-- STA="A" -->
<!-- ITN="X20200101370929" -->
');
		$this->assertEquals('123456789', $result['fid']);
		$this->assertEquals('99-9-8765-20', $result['srn']);
		$this->assertEquals('A', $result['sta']);
		$this->assertEquals('X20200101370929', $result['itn']);
	}
}

