<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Oct 1, 2013
 */

class celebros_details extends celebros_details_parent {
    public function render() {
        $this->_aViewData['sCelAnalyticsType'] = 'details';
        
        return parent::render();
    }
}
?>
