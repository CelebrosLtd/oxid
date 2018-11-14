<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Nov 8, 2013
 */

class eins_celebros_uninstall {
    public static function onDeactivate() {
        $oDb = oxDb::getDb();
        $oDb->execute("DELETE FROM oxtplblocks WHERE oxmodule='eins_celebros'");
    }
}
?>
