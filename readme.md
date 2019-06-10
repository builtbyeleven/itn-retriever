[![Build Status](https://travis-ci.org/builtbyeleven/itn-retriever.svg?branch=master)](https://travis-ci.org/builtbyeleven/itn-retriever) 
[![Style Status](https://github.styleci.io/repos/190818364/shield)](https://github.styleci.io/repos/190818364/shield)



## Usage

```
use BuiltByEleven\AceFilingInquiryFactory;

$ace = new AceFilingInquiryFactory($fid, $srn);
$ace->getItn();
```

## WebLink Status API URL

- Test/Training environment : https://ace.cbp.dhs.gov/aesd/ta/cert/aes-direct/secured/weblinkFilingInquiry

- Production environment : https://ace.cbp.dhs.gov/aesd/ta/aes-direct/secured/weblinkFilingInquiry

## WebLink Filing API URL:

- Test/Training environment : https://ace.cbp.dhs.gov/aesd/ta/cert/aes-direct/secured/createWeblinkFiling

- Production environment : https://ace.cbp.dhs.gov/aesd/ta/aes-direct/secured/createWeblinkFiling

| Variable | Description | Required By | Value(s) | Size |
| -- | -- | -- | -- | -- |
| FID |	Filer ID used for the commodity filing | WebLink | Alphanumeric | 11 |
| SRN |	Shipment Reference Number	| WebLink |	Alphanumeric |	17 |
URL	 | Return URL (optional, needed only for automated ITN redirect. Only https  URL will be supported.) | Optional | Valid URL | 150 |