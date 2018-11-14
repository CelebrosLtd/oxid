<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getBaseDir()}]modules/eins_celebros/views/admin/src/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getBaseDir()}]modules/eins_celebros/views/admin/src/css/eins_csv.css" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getBaseDir()}]modules/eins_celebros/views/admin/src/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getBaseDir()}]modules/eins_celebros/views/admin/src/js/jquery.form.min.js"></script>

    </head>

    <body>

        <div class="container-semifluid">

            <div class="well bg_white">
                <div class="row-fluid">
                    <div class="pull-left">
                        <h1>einscommerce CSV Export</h1>
                    </div>
                    <div class="pull-right text-right">
                        <a href="http://www.einscommerce.com" title="einscommerce.com" id="eins_eins" target="_blank">
                            <img src="[{$oViewConf->getBaseDir()}]modules/eins_celebros/views/admin/src/pictures/celebros_logo.png" alt="Logo einscommerce" />
                        </a>
                    </div>
                </div>
            </div>
                        
                        [{if $upload_message}]
        [{if $upload_succeeded}]
            <p class="upload_succeeded">[{$upload_message}]</p>
        [{else}]
            <p class="upload_failed">[{$upload_message}]</p>
        [{/if}]
    [{/if}]

            <div class="well">


                <form action="[{$oViewConf->getSelfLink()}]" method="post" id="csvForm_[{$smarty.foreach.plugin.index}]" enctype="multipart/form-data">
                    <div class="row-fluid">                  

                        <div class="span5">
                            <h2>Exportdateien hochladen</h2>
                        </div>
                        <input type="hidden" name="cl" value="celebros_synchro" />
                        <input type="hidden" name="fnc" value="upload" />
                        
                        <div class="span2">

                            <input type="submit" class="btn btn-primary submitButton" value="Hochladen"/>

                        </div>
                        </div>

                </form>
                <div id="wrap">
                    <div id="inner_wrap">
                        [{foreach from=$aExportedFiles item="aFile"}]
                        <div class="well well-small">
                            <div class="row-fluid">
                                <div class="span6 pull-left">
                                    [{assign var=aLastModified value=" "|explode:$aFile.lastModified}]
                                    <i class="icon-calendar"></i> [{$aLastModified[0]}] &nbsp; <i class="icon-time"></i> [{$aLastModified[1]}]
                                </div>
                                <div class="span6 pull-right text-right">
                                    <a href="[{$oViewConf->getModuleUrl("eins_celebros")}]export/[{$aFile.fileName}]" class="btn" title="[{$aFile.fileName}]"><i class="icon-download-alt"></i> Download</a>
                                </div>
                            </div>
                        </div>
                        [{/foreach}]
                    </div>        
                    </div>
                </div>  

        </div>
    </body>

</html>
