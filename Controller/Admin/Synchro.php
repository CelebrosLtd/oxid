<?php
namespace Celebros\Conversionpro\Controller\Admin;

class Synchro extends oxAdminView {

    protected $_sThisTemplate = "celebros_synchro.tpl";

    public function render() {
        $this->_aViewData['sSessionId'] = oxConfig::getParameter('force_admin_sid');
        $this->_aViewData['sToken'] = oxConfig::getParameter('stoken');
        
        $this->_aViewData['aExportedFiles'] = $this->_getExportFileList();
        return $this->_sThisTemplate;
    }
    
    public function getUploadProgress() {
        die(celebros_upload::getUploadProgress());
    }

    protected function _getExportFileList() {
        $handle = opendir($this->getConfig()->getConfigParam('sShopDir') . 'modules/eins_celebros/export/');
        while (false !== ($file = readdir($handle))) {
            if (substr($file, strlen($file) - 4) == ".zip") {
                $sFullPath = $this->getConfig()->getConfigParam('sShopDir') . 'modules/eins_celebros/export/' . $file;
                $sLastModified = date('d.m.Y H:i', filemtime($sFullPath));

                $aExportFiles[] = array(
                    'fileName' => $file,
                    'lastModified' => $sLastModified
                );
            }
        }

        return $aExportFiles;
    }
    
    public function upload() {
        if(($sResult = celebros_upload::uploadExportFiles()) === true) {
            $this->_aViewData['upload_succeeded'] = true;
            $this->_aViewData['upload_message'] = "Upload complete successfully.";
        }
        else {
            $this->_aViewData['upload_succeeded'] = false;
            var_dump($sResult);
            $this->_aViewData['upload_message'] = $sResult;
        }
    }

}

?>
