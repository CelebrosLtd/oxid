<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('eins_csv')}]views/admin/src/css/bootstrap.min.css" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('eins_csv')}]views/admin/src/css/eins_csv.css?v=0.0.4" />

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl('eins_csv')}]views/admin/src/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl('eins_csv')}]views/admin/src/js/jquery.form.min.js"></script>

    </head>

    <body>

        <div class="container-semifluid">

            <div class="row-fluid">
                <div class="well">
                    <h1>einscommerce CSV Import</h1>
                </div>
            </div>
            

            [{foreach from=$aPlugins item="oPlugin" name="plugin"}]
            <h3>[{$oPlugin->getTitle()}]</h3>
            <form action="[{$oViewConf->getSelfLink()}]" method="post" id="csvForm_[{$smarty.foreach.plugin.index}]" enctype="multipart/form-data">

                <div class="row-fluid">

                    <div class="span5">

                        <input type="hidden" name="cl" value="eins_import_overview" />
                        <input type="hidden" name="fnc" value="upload" />
                        <input type="hidden" name="sProgressHash" value="[{$sProgressHash}]" />
                        <input type="hidden" name="pluginId" value="[{$oPlugin->getId()}]" />
                        <input type="hidden" name="force_admin_sid" value="[{$sSessionId}]" />
                        <input type="hidden" name="stoken" value="[{$sToken}]" />
                        <input type="hidden" name="iAmount" id="iAmount" value="[{$oPlugin->getChunkSize()}]">
                        
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Datei auswählen</span><span class="fileupload-exists">Ändern</span><input type="file" name="file" size="50" maxlength="10000000" accept=".csv" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Entfernen</a>
                            </div>
                        </div>

                    </div>

                    <div class="span2">

                        <button type="submit" class="btn btn-primary">Hochladen</button>

                    </div>

                    <div class="span5">
                        <div class="progress-bars">
                            <div class="progress-bar-upload">
                                Upload:
                                <div class="progress progress-striped progress-upload progress-warning active">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                            </div>

                            <div class="progress-bar-import">
                                Import:
                                <div class="progress progress-striped progress-import active">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </form>

            [{/foreach}]

            <script type="text/javascript">
                jQuery(function($) {

                    $(document).on('submit', 'form', function(e) {
                        var self = $(this);
                        window[self.attr('id')].i = 0;
                        e.preventDefault();

                        if (self.find('input[type="file"]').val() == '') {
                            return false;
                        }

                        self.find('.progress-bar-upload').fadeIn(250, function() {
                            self.find('.progress-bar-import').find('.bar').addClass('no-transitions').css('width', 0).removeClass('no-transitions');
                        });
                        self.find('button').attr('disabled', true);
                        self.ajaxSubmit({
                            forceSync: true,
                            uploadProgress: function(event, position, total, percentComplete) {
                                self.find('.progress-upload .bar').css({
                                    width: percentComplete + '%'
                                });
                            },
                            success: function() {
                                self.find('.progress-upload').removeClass('progress-warning').addClass('progress-success');
                                getFileLength(self);
                            }
                        });
                        return false;
                    });

                    function importFile(form) {
                        $.ajax({
                            url: "[{$oViewConf->getSelfLink()}]",
                            data: {
                                cl: "eins_import_overview",
                                fnc: "import",
                                iOffset: window[form.attr('id')].i,
                                iAmount: $('#iAmount').val(),
                                pluginId: form.find('[name="pluginId"]').val(),
                                force_admin_sid: form.find('[name="force_admin_sid"]').val(),
                                stoken: form.find('[name="stoken"]').val()
                            },
                            type: "GET",
                            success: function(data) {

                                window[form.attr('id')].i += $('#iAmount').val();

                                var percentVal = ((window[form.attr('id')].i / window[form.attr('id')].fileLength) * 100) + '%';

                                form.find('.progress-import .bar').css({
                                    width: percentVal
                                });

                                console.log('importFile / success 1: ' + window[form.attr('id')].i);
                                console.log('importFile / success 2: ' + form.attr('id'));
                                console.log('importFile / success 3: ' + data);

                                if (window[form.attr('id')].i < window[form.attr('id')].fileLength) {

                                    importFile(form);
                                }
                                else {
                                    finalizeImport(form);
                                    form.find('.progress-import').addClass('progress-success');
                                    form.find('button').attr('disabled', false);
                                }
                            },
                            error: function(data) {
                                setTimeout(function() {
                                    importFile(form);
                                }, 1000);
                            }
                        });
                    }

                    function finalizeImport(form) {
                        $.ajax({
                            url: "[{$oViewConf->getSelfLink()}]",
                            data: {
                                cl: "eins_import_overview",
                                fnc: "finalizeImport",
                                pluginId: form.find('[name="pluginId"]').val(),
                                force_admin_sid: form.find('[name="force_admin_sid"]').val(),
                                stoken: form.find('[name="stoken"]').val()
                            },
                            type: "GET",
                            success: function(data) {
                                console.log('done! data: ' + data);
                            }
                        });
                    }

                    function getFileLength(form) {
                        $.ajax({
                            url: "[{$oViewConf->getSelfLink()}]",
                            data: {
                                cl: "eins_import_overview",
                                fnc: "getFileLength",
                                // pluginId: form.find('[name="pluginId"]').val(),
                                force_admin_sid: form.find('[name="force_admin_sid"]').val(),
                                stoken: form.find('[name="stoken"]').val()
                            },
                            type: "GET",
                            complete: function(data) {
                                window[form.attr('id')].fileLength = parseInt(data.responseText);
                                form.find('.progress-bar-upload').fadeOut(250).find('.bar').addClass('no-transitions').css('width', 0).removeClass('no-transitions');
                                window[form.attr('id')].i = 0;
                                importFile(form);
                            }
                        });
                    }
                });
            </script>

        </div>

    </body>

</html>
