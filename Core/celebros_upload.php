<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Aug 27, 2013
 */

class celebros_upload {

    public static function uploadExportFiles() {
        $aExportFiles = celebros_upload::_getExportFileList();
        
        $sFtpServer = oxConfig::getInstance()->getShopConfVar('sCel_FtpServer');
        $sFtpUser = oxConfig::getInstance()->getShopConfVar('sCel_Ftp_User');
        $sFtpPass = oxConfig::getInstance()->getShopConfVar('sCel_Ftp_Password');
        
        $oFTPStream = @ftp_connect($sFtpServer);
        $sLoginResult = @ftp_login($oFTPStream, $sFtpUser, $sFtpPass );
        
        if ( (!$oFTPStream) || (!$sLoginResult) ) {
            return "FTP connection failed! Tried to connect to server $sFtpServer with user name $sFtpUser.\n";
        }
        
        
        foreach ( $aExportFiles as $sFile ) {
            $oHandle = fopen(oxConfig::getInstance()->getShopConfVar('sShopDir') . 'modules/eins_celebros/export/' . $sFile, 'r');
            $sFileRemote = 'uploading_' . $sFile;
            
            ftp_fput($oFTPStream, $sFileRemote, $oHandle, FTP_BINARY);
        }
        
        return true;
    }
    
    public static function getUploadProgress() {
        return oxConfig::getInstance()->getShopConfVar("celebros_upload_progress");
    }
    
    protected static function _getExportFileList() {
        $handle = opendir(oxConfig::getInstance()->getShopConfVar('sShopDir') . 'modules/eins_celebros/export/');
        
        while (false !== ($file = readdir($handle))) {
            if (substr($file, strlen($file) - 4) == ".zip") {
//                $sFullPath = oxConfig::getInstance()->getShopConfVar('sShopDir') . 'modules/eins_celebros/export/' . $file;
                $aExportFiles[] = $file;
            }
        }

        return $aExportFiles;
    }
}
?>
