<?php
namespace Celebros\Conversionpro\Controller;

class DetailsController extends DetailsController_parent
{
    public function render()
    {
        $this->_aViewData['sCelAnalyticsType'] = 'details';
        
        return parent::render();
    }
}
