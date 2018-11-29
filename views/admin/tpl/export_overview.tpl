<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/css/eins_csv.css" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/js/jquery.form.min.js"></script>
    </head>
    <body>
        [{$oViewConf->getHiddenSid()}]
        <div class="container-semifluid">
            <div class="well bg_white">
                <div class="row-fluid">
                    <div class="pull-left">
                        <h1>CSV Export</h1>
                    </div>
                    <div class="pull-right text-right">
                        <a href="http://www.einscommerce.com" title="einscommerce.com" id="eins_eins" target="_blank">
                            <img src="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/pictures/celebros_logo.png" alt="Logo Celebros" />
                        </a>
                    </div>
                </div>
            </div>
            [{foreach from=$aPlugins key="sPluginId" item="oPlugin" name="plugin"}]
                <div class="well">
                    <form action="[{$oViewConf->getSelfLink()}]" method="post" id="csvForm_[{$smarty.foreach.plugin.index}]" enctype="multipart/form-data">
                        <div class="row-fluid">
                            <div class="span5">
                                <span>[{$oPlugin->getTitle()}]</span>
                            </div>
                            <input type="hidden" name="cl" value="celebros_conversionpro_admin_export" />
                            <input type="hidden" name="fnc" value="export" />
                            <input type="hidden" name="sOutputFileHash" value="[{$sOutputFileHash}]" />
                            <input type="hidden" name="pluginId" value="[{$oPlugin->getId()}]" />
                            <input type="hidden" name="iAmount" id="iAmount" value="[{$oPlugin->getChunkSize()}]">

                            [{foreach from=$oPlugin->getExportParamInfo() key="sParamName" item="sParam"}]
                                <div class="span2">
                                    <label>[{$sParam}]
                                        <input name="[{$sParamName}]" id="[{$sParamName}]" type="text" class="btn btn-primary" />
                                    </label>
                                </div>
                            [{/foreach}]

                            <div class="span2">
                                <input type="submit" class="btn btn-primary submitButton" value="Exportieren"/>
                            </div>

                            <div class="span5">
                                <div class="progress progress-striped active">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    [{assign var="aPluginExportedFiles" value=$aExportedFiles[$sPluginId]}]
                    <div id="wrap">
                        <div id="inner_wrap">
                            [{foreach from=$aPluginExportedFiles item="aFile"}]
                                <div class="well well-small">
                                    <div class="row-fluid">
                                        <div class="span6 pull-left">
                                            [{assign var=aLastModified value=" "|explode:$aFile.lastModified}]
                                            <i class="icon-calendar"></i> [{$aLastModified[0]}] &nbsp; <i class="icon-time"></i> [{$aLastModified[1]}]
                                        </div>
                                        <div class="span6 pull-right text-right">
                                            <a href="[{$oViewConf->getModuleUrl("celebros_conversionpro")}]export/[{$aFile.fileName}]" class="btn" title="[{$aFile.fileName}]"><i class="icon-download-alt"></i> Download</a>
                                        </div>
                                    </div>
                                </div>
                            [{/foreach}]
                        </div>
                    </div>
                </div>
            [{/foreach}]
            <script type="text/javascript">
            selflink = jQuery('.well form').attr('action');
                jQuery(function($) {
                    var i = 0;
                    function finalizeExport(form) {
                        $.ajax({
                            url: selflink,
                            type: "GET",
                            data: {
                                cl: "celebros_conversionpro_admin_export",
                                fnc: "finalizeExport",
                                pluginId: form.find('[name="pluginId"]').val(),
                                /*force_admin_sid: "[{$sSessionId}]",
                                stoken: "[{$sToken}]",*/
                                shp: "[{$sShopId}]"
                            },
                            success: function(data) {
                                form.find('.submitButton').prop('disabled',false);
                                form.find('.progress').addClass('progress-success no-transitions');
                                $('#wrap').load(selflink+'cl=celebros_conversionpro_admin_export&shp=[{$sShopId}] #inner_wrap');
                            }
                        });
                    }
                    function exportFile(form) {
                        $.ajax({
                            url: selflink,
                            type: "GET",
                            data: {
                                cl: "celebros_conversionpro_admin_export",
                                fnc: "export",
                                pluginId: form.find('[name="pluginId"]').val(),
                                sOutputFileHash: "[{$sOutputFileHash}]",
                                iOffset: window[form.attr('id')].i,
                                iAmount: $('#iAmount').val(),
                                /*force_admin_sid: "[{$sSessionId}]",
                                stoken: "[{$sToken}]",*/
                                shp: "[{$sShopId}]"
                            },
                            complete: function(data) {
                                console.log(data.responseText);
                                var today = new Date();
                                window[form.attr('id')].i += parseInt($('#iAmount').val());
                                var percentVal = ((window[form.attr('id')].i / window[form.attr('id')].fileLength) * 100) + '%';
                                $('.progress').children('.bar').css({
                                    width: percentVal
                                });
                                if (window[form.attr('id')].i < window[form.attr('id')].fileLength) {
                                    exportFile(form);
                                } else {
                                    finalizeExport(form);
                                }
                            }
                        });
                    }

                    function formSubmit(form) {
                        window[form.attr('id')].i = 0;
                        form.find('.submitButton').prop('disabled',true);
                        $('.progress').removeClass('progress-success').children('.bar').css({
                            width: 0
                        });
                        $('.progress').removeClass('no-transitions');
                        $.ajax({
                            url: selflink,
                            type: "GET",
                            data: {
                                cl: "celebros_conversionpro_admin_export",
                                fnc: "getRSSize",
                                pluginId: form.find('[name="pluginId"]').val(),
                                /*force_admin_sid: "[{$sSessionId}]",
                                stoken: "[{$sToken}]",*/
                                shp: "[{$sShopId}]"
                            },
                            complete: function(data) {
                                window[form.attr('id')].fileLength = data.responseText;
                            },
                            success: function() {
                                exportFile(form);
                            }
                        });
                    }

                    $(document).on('click', '.submitButton', function(e) {
                        e.preventDefault();
                        $(this).closest('form').submit();
                    });

                    $(document).on('submit', 'form', function(e) {
                        e.preventDefault();
                        var self = $(this);
                        formSubmit(self);
                    });
                });
            </script>
        </div>
        <style>
        .span5 span {
            font-size: 28px;
            line-height: normal;
        }
        </style>
    </body>
</html>