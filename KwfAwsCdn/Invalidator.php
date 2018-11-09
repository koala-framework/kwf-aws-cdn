<?php
use Aws\Common\Credentials\Credentials;
use Aws\CloudFront\CloudFrontClient;

class KwfAwsCdn_Invalidator
{
    private $client;
    private $distributionId;

    /**
     * Pass your CloudFront distribution id
     * This id must refer to the cdn which should be invalidated
     * Looks like this: E1NNRU851FPV4D
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // distributionId is required
        if (isset($config['distribution_id'])) {
            $this->distributionId = $config['distribution_id'];
        }

        $accessKey = Kwf_Config::getValue('awscdn.access_key_id');
        $accessSecretKey = Kwf_Config::getValue('awscdn.secret_access_key_id');

        if ($accessKey && $accessSecretKey) {
            $credentials = new Credentials($accessKey, $accessSecretKey);

            $clientConfig = array(
                'credentials' => $credentials,
                'request.options' => array()
            );

            if ( Kwf_Config::getValue('http.proxy.host')) {
                $clientConfig['request.options']['proxy'] = Kwf_Config::getValue('http.proxy.host') . ':' . Kwf_Config::getValue('http.proxy.port');
            }

            $s3Client = CloudFrontClient::factory($clientConfig);
            $this->client = $s3Client;
        }
    }

    /**
     *
     * @param string $path
     * @return Guzzle_Service_Resource_Model
     */
    public function invalidatePath($path)
    {
        if (!$this->client || !$this->distributionId) {
            return false;
        }

        $result = $this->client->createInvalidation(array(
            // DistributionId is required
            'DistributionId' => $this->distributionId,
            // Paths is required
            'Paths' => array(
                // Quantity is required
                'Quantity' => 1,
                'Items' => array($path),
            ),
            // CallerReference is required
            'CallerReference' => time(),
        ));

        return $result;
    }
}
