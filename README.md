# CDN Integration with Amzon Cloudfront


Adds support for caching asset and media urls thru a cdn instance on amazon cloudfront.

## Configuration

### For single domain
	
	;bind kwf events
	eventSubscribers.awscdn = KwfAwsCdn_Events
	;bind clear cache
	clearCacheTypes.assetsAwsCdn = KwfAwsCdn_ClearCacheTypeAssets
	; individual cdn config
	awscdn.access_key_id = [YOUR-AWS-KEY]
	awscdn.secret_access_key_id = [YOUR-AWS-SECRET_KEY]
	awscdn.distribution_id = [YOUR-CDN-DISTRIBUTION-ID]
	awscdn.domain = [YOUR-CDN-WEB-DOMAIN]


### For multi domain

	;bind kwf events
	eventSubscribers.awscdn = KwfAwsCdn_Events
	;bind clear cache
	clearCacheTypes.assetsAwsCdn = KwfAwsCdn_ClearCacheTypeAssets
	; individual cdn config
	awscdn.access_key_id = [YOUR-AWS-KEY]
	awscdn.secret_access_key_id = [YOUR-AWS-SECRET_KEY]
	kwc.domains.my.awscdn.distribution_id = [YOUR-CDN-DISTRIBUTION-ID]
	kwc.domains.my.awscdn.domain = [YOUR-CDN-WEB-DOMAIN]



[more info on credentials](https://docs.aws.amazon.com/aws-sdk-php/v2/guide/credentials.html#passing-credentials-into-a-client-factory-method)

## Compatibility

Does not work in combination with [https://github.com/koala-framework/kwf-varnish
](). Kwf-Varnish has the same purpose as this package but uses a different strategy.
Be sure to turn varnish off:

### For single domain

	varnish.domain = false

### For multi domain

	kwc.domains.hu.varnish.domain = false


## Test enviromnent

Kwf Prelogin must be disabled. Otherwise Amazon Cloudfront cannot properly cache the site.

## References

### Relevant Docu for aws-php sdk version 2

[Invalidate cache](https://docs.aws.amazon.com/aws-sdk-php/v2/api/class-Aws.CloudFront.CloudFrontClient.html#_createInvalidation)

[Path Syntax for invalidation API](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html#invalidation-specifying-objects-paths)

[Configure sdk](https://docs.aws.amazon.com/aws-sdk-php/v2/guide/configuration.html)

[Doku](https://docs.aws.amazon.com/aws-sdk-php/v2/guide/index.html)
