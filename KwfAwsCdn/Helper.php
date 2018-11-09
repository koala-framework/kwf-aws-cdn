<?php
class KwfAwsCdn_Helper
{
    /**
     * Helper function to retrieve all aws distribution ids of a web
     *
     * @return array
     */
    public static function getAllDistributionIds()
    {
        $awscdnDistributionIds = array();
        if (Kwf_Config::getValue('awscdn.distribution_id')) {
            $awscdnDistributionIds[] = Kwf_Config::getValue('awscdn.distribution_id');
        }
        foreach (Kwf_Config::getValueArray('kwc.domains') as $i) {
            if (isset($i['awscdn']['distribution_id']) && $i['awscdn']['distribution_id'] && !in_array($i['awscdn']['distribution_id'], $awscdnDistributionIds)) {
                $awscdnDistributionIds[] = $i['awscdn']['distribution_id'];
            }
        }
        return $awscdnDistributionIds;
    }
}
