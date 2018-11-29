<?php
class KwfAwsCdn_ClearCacheTypeAssets extends Kwf_Util_ClearCache_Types_Abstract
{
    protected function _clearCache($options)
    {
        foreach (KwfAwsCdn_Helper::getAllDistributionIds() as $awscdnDistributionId) {
            $invalidator = new KwfAwsCdn_Invalidator(array('distributionId' => $awscdnDistributionId));
            $invalidator->invalidatePath('/assets/*');
        }
    }

    public function getTypeName()
    {
        return 'assetsAwsCdn';
    }

    public function doesRefresh()
    {
        return false;
    }

    public function doesClear()
    {
        return true;
    }
}

