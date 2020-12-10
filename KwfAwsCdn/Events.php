<?php
class KwfAwsCdn_Events extends Kwf_Events_Subscriber
{
    public function getListeners()
    {
        $ret = parent::getListeners();
        $ret[] = array(
            'class' => null,
            'event' => 'Kwf_Component_Event_CreateMediaUrl',
            'callback' => 'onCreateMediaUrl'
        );
        $ret[] = array(
            'class' => null,
            'event' => 'Kwf_Events_Event_CreateAssetsPackageUrls',
            'callback' => 'onCreateAssetsPackageUrls'
        );
        $ret[] = array(
            'class' => null,
            'event' => 'Kwf_Events_Event_CreateAssetUrl',
            'callback' => 'onCreateAssetUrl'
        );
        $ret[] = array(
            'class' => null,
            'event' => 'Kwf_Events_Event_Media_Changed',
            'callback' => 'onMediaChanged'
        );
        $ret[] = array(
            'class' => null,
            'event' => 'Kwf_Events_Event_Media_ClearAll',
            'callback' => 'onMediaClearAll'
        );
        return $ret;
    }

    public function onCreateMediaUrl(Kwf_Component_Event_CreateMediaUrl $ev)
    {
        $awscdnDomain = $ev->component->getBaseProperty('awscdn.domain');
        if ($awscdnDomain && $ev->component->isVisible()) {
            $ev->url = '//'.$awscdnDomain.$ev->url;
        }
    }

    public function onCreateAssetUrl(Kwf_Events_Event_CreateAssetUrl $ev)
    {
        if ($ev->subroot) {
            $awscdnDomain = $ev->subroot->getBaseProperty('awscdn.domain');
            if ($awscdnDomain) {
                $ev->url = '//'.$awscdnDomain.$ev->url;
            }
        }
    }

    public function onCreateAssetsPackageUrls(Kwf_Events_Event_CreateAssetsPackageUrls $ev)
    {
        $awscdnDomain = null;
        if ($ev->subroot) {
            $awscdnDomain = $ev->subroot->getBaseProperty('awscdn.domain');
        } else {
            $awscdnDomain = Kwf_Config::getValue('awscdn.domain');
        }

        if ($awscdnDomain) {
            $ev->prefix = '//'.$awscdnDomain;
        }
    }

    public function onMediaChanged(Kwf_Events_Event_Media_Changed $ev)
    {
        $awscdnDistributionId = $ev->component->getBaseProperty('awscdn.distribution_id');

        if ($awscdnDistributionId) {
            $invalidator = new KwfAwsCdn_Invalidator(array('distributionId' => $awscdnDistributionId));
            $path = '/media/'.rawurlencode($ev->class).'/'.$ev->component->componentId.'/*';
            $invalidator->invalidatePath($path);
        }
    }

    public function onMediaClearAll(Kwf_Events_Event_Media_ClearAll $ev)
    {
        foreach (KwfAwsCdn_Helper::getAllDistributionIds() as $awscdnDistributionId) {
            $invalidator = new KwfAwsCdn_Invalidator(array('distributionId' => $awscdnDistributionId));
            $invalidator->invalidatePath('/media/*');
        }
    }
}
