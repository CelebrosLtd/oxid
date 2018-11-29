[{*debug*}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]
<script type="text/javascript">
    if (top)
  {
          top.sMenuItem = "[{oxmultilang ident="cel_salesperson_menuitem"}]";
          top.sMenuSubItem = "[{oxmultilang ident="cel_salesperson_menusubitem"}]";
          top.sWorkArea = "[{$_act}]";
          top.setTitle();
      }
</script>
<script type="text/javascript">
<!--
    function _groupExp(el) {
        var _cur = el.parentNode;

        if (_cur.className == "exp")
            _cur.className = "";
        else
            _cur.className = "exp";
    }
    function showBasketReserved()
    {
            if (document.getElementById('basketreserved').value == 1)
  {
              document.getElementById('basketreservedtime').className = 'rowexp';
          }
          else
  {
              document.getElementById('basketreservedtime').className = 'rowhide';
          }
      }
      function showInvitations()
      {
              if (document.getElementById('invitations').value == 1)
  {
              document.getElementById('pointsforinvitation').className = 'rowexp';
              document.getElementById('pointsforregistration').className = 'rowexp';
          }
          else
  {
              document.getElementById('pointsforinvitation').className = 'rowhide';
              document.getElementById('pointsforregistration').className = 'rowhide';
          }
      }
//-->
</script>

[{if $readonly}]
[{assign var="readonly" value="readonly disabled"}]
[{else}]
[{assign var="readonly" value=""}]
[{/if}]

[{cycle assign="_clear_" values=",2"}]
<img src="[{$oViewConf->getBaseDir()}]modules/celebros/conversionpro/views/admin/src/pictures/celebros_logo.png" alt="Logo Celebros" />
<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="celebros_conversionpro_admin_settings">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="celebros_conversionpro_admin_settings">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_CONNECTION_SETTINGS"}]</b></a>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_ServiceHost] value="[{$confstrs.sCel_ServiceHost}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SERVICE_HOST"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SERVICE_HOST"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_ServicePort] value="[{if $confstrs.sCel_ServicePort}][{$confstrs.sCel_ServicePort}][{else}]6035[{/if}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SERVICE_PORT"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SERVICE_PORT"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_SiteKey] value="[{$confstrs.sCel_SiteKey}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SITE_KEY"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SITE_KEY"}]
                </dd>
                <div class="spacer"></div>
            </dl>        
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_DefaultSearchProfile] value="[{$confstrs.sCel_DefaultSearchProfile}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <!--<div class="groupExp">
        <div id="liste">
            <a href="#" onclick="_groupExp(this); return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_MAPPINGS"}]</b></a>
            <span style="color:red;margin-left:200px">* Mandatory data fields cannot be edited or excluded from the export</span>
            <dl>
                <dt style="width:100%">
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SORTING_FIELDS_MAPPINGS"}]
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <colgroup>
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>                
                    <tr>
                        <td class="listheader" height="15">[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_OXID_FIELD_NAME"}]</td>
                        <td class="listheader" height="15">[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SALESPERSON_FIELD_NAME"}]</td>
                        <td class="listheader" height="15">[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_INCLUDE"}]</td>
                        <td class="listheader" height="15">[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_IS_NUMERIC_SORT"}]</td>
                    </tr>
                    [{assign var="blWhite" value=""}]
                    [{assign var="_cnt" value=0}]
                    [{foreach from=$fieldsList item=listitem}]
                    [{assign var="_cnt" value=$_cnt+1}]
                    <tr id="row.[{$_cnt}]">
                        [{assign var="listclass" value=listitem$blWhite}]
                        <td valign="top" class="[{$listclass}]" height="15">
                            [{$listitem.oxid_field_name}]   	
                        </td>                  	
                        <td valign="top" class="[{$listclass}]" height="15">
                            <input type=text size="30" name=mappingsalespersonfields[[{$listitem.oxid_field_name}]] value="[{$listitem.salesperson_field_name}]" [{if ($listitem.salesperson_field_required)}]readonly[{/if}]>
                        </td>
                        <td valign="top" class="[{$listclass}]" height="15">
                            <input type=hidden name=mappingincludefields[[{$listitem.oxid_field_name}]] value=0>
                            <input type=checkbox name=mappingincludefields[[{$listitem.oxid_field_name}]] value=1  [{if ($listitem.include)}]checked[{/if}] [{if ($listitem.salesperson_field_required)}]onclick="return false" style="color: #EBEBE4;" [{/if}]>
                        </td>
                        <td valign="top" class="[{$listclass}]" height="15">
                            <input type=hidden name=mappingisnumericsortfields[[{$listitem.oxid_field_name}]] value=0>
                            <input type=checkbox name=mappingisnumericsortfields[[{$listitem.oxid_field_name}]] value=1  [{if ($listitem.is_numeric_sort)}]checked[{/if}] [{if ($listitem.salesperson_field_required)}]onclick="return false" style="color: #EBEBE4;" [{/if}]>                            
                        </td>                    
                    </tr>
                    [{if $blWhite == "2"}]
                    [{assign var="blWhite" value=""}]
                    [{else}]
                    [{assign var="blWhite" value="2"}]
                    [{/if}]
                    [{/foreach}]
                </table>                
                </dt>
                <div class="spacer"></div>
                <input type="submit" value="[{oxmultilang ident="cel_RESET_MAPPING"}]" name="reset_mapping"/>
            </dl>   
        </div>
    </div>-->

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS"}]</b></a>
            <dl>
                <dt>
                <select name=confstrs[sCel_CategorySearchMode] [{$readonly}]>
                    <option value="0" [{if $confstrs.sCel_CategorySearchMode == 0}] SELECTED[{/if}]>[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_OFF"}]</option>
                    <option value="1" [{if $confstrs.sCel_CategorySearchMode == 1}] SELECTED[{/if}]>[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_CATEGORY"}]</option>
                    <option value="2" [{if $confstrs.sCel_CategorySearchMode == 2}] SELECTED[{/if}]>[{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_FULL_CATEGORY"}]</option>
                </select>                
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SORTING_FIELD"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_CATEGORY_LEGEND"}]
                </dd>
                <div class="spacer"></div>
            </dl>        
        </div>
    </div>

    <!--<div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_DEFAULT_SEARCH_PARAMETERS"}]</b></a>
            <dl>
                <dt>
                <input type=text class="txt" name=confstrs[iCel_DefaultAnswerId] value="[{$confstrs.iCel_DefaultAnswerId}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ANSWER_ID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ANSWER_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl>            
            <dl>
                <dt>
                <input type=text class="txt" name=confstrs[iCel_DefaultEffectOnSearchPath] value="[{$confstrs.iCel_DefaultEffectOnSearchPath}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_EFFECT_ON_SEARCH_PATH"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_EFFECT_ON_SEARCH_PATH"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text class="txt" name=confstrs[sCel_DefaultPriceColum] value="[{$confstrs.sCel_DefaultPriceColum}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_PRICE_COLUM"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_PRICE_COLUM"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text class="txt" name=confstrs[iCel_DefaultPageSize] value="[{$confstrs.iCel_DefaultPageSize}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_PAGE_SIZE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_PAGE_SIZE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <select name=confstrs[sCel_DefaultSortingfield] [{$readonly}]>
                    [{foreach key=key item=item from=$oView->getIncludedSortingFields()}]
                    <option value="[{$key}]" [{if $confstrs.sCel_DefaultSortingfield == $key}] SELECTED[{/if}]>[{$item}]</option>
                    [{/foreach}]
                </select>                
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SORTING_FIELD"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SORTING_FIELD"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                 <dt>
                     <input type=hidden name=confbools[bCel_DefaultNumericsort] value=false>
                     <input type=checkbox name=confbools[bCel_DefaultNumericsort] value=true  [{if ($confbools.bCel_DefaultNumericsort)}]checked[{/if}] [{$readonly}]>
                     [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_NUMERIC_SORT"}]
                 </dt>
                 <dd>
                     [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_NUMERIC_SORT"}]
                 </dd>
                 <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=hidden name=confbools[bCel_DefaultAscending] value=false>
                <input type=checkbox name=confbools[bCel_DefaultAscending] value=true  [{if ($confbools.bCel_DefaultAscending)}]checked[{/if}] [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ASCENDING"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_NUMERIC_ASCENDING"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text size="30" name=confstrs[sCel_DefaultSearchProfile] value="[{$confstrs.sCel_DefaultSearchProfile}]" [{$readonly}]>
                    [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>-->

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_DISPLAY_SEARCH_RESULTS_SETTINGS"}]</b></a>
            <dl>
                <dt>
                <select name=confstrs[iCel_MaxNoneLeadQuestions] [{$readonly}]>
                    [{foreach key=key item=item from=$oView->getMaxNoneLeadQuestions()}]
                    <option value="[{$key}]" [{if $confstrs.iCel_MaxNoneLeadQuestions == $key}] SELECTED[{/if}]>[{$item}]</option>
                    [{/foreach}]
                </select>                 
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_QUESTIONS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_QUESTIONS"}]
                </dd>
                <div class="spacer"></div>
            </dl>            
            <dl>
                <dt>
                <select name=confstrs[iCel_MaxNoneLeadAnswers] [{$readonly}]>
                    [{foreach key=key item=item from=$oView->getMaxNoneLeadAnswers()}]
                    <option value="[{$key}]" [{if $confstrs.iCel_MaxNoneLeadAnswers == $key}] SELECTED[{/if}]>[{$item}]</option>
                    [{/foreach}]
                </select>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_ANSWERS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_ANSWERS"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <!--<dl>
                <dt>
                    <input type=hidden name=confbools[bCel_DisplayLeadAnswers] value=false>
                    <input type=checkbox name=confbools[bCel_DisplayLeadAnswers] value=true  [{if ($confbools.bCel_DisplayLeadAnswers)}]checked[{/if}] [{$readonly}]>
                    [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_DISPLAY_LEAD_ANSWERS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_DISPLAY_LEAD_ANSWERS"}]
                </dd>
                <div class="spacer"></div>
            </dl>-->
            <dl>
                <dt>
                <select name=confstrs[iCel_MaxLeadAnswers] [{$readonly}]>
                    [{foreach key=key item=item from=$oView->getMaxLeadAnswers()}]
                    <option value="[{$key}]" [{if $confstrs.iCel_MaxLeadAnswers == $key}] SELECTED[{/if}]>[{$item}]</option>
                    [{/foreach}]
                </select>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_MAX_LEAD_ANSWERS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_MAX_LEAD_ANSWERS"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="60" name=confstrs[sCel_AlternativeProductsMsg] value="[{$confstrs.sCel_AlternativeProductsMsg}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ALTERNATIVE_PRODUCTS_MSG"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ALTERNATIVE_PRODUCTS_MSG"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_AUTOCOMPLETE"}]</b></a>
            <dl>
                <dt>
                <input type=text size="60" name=confstrs[sCel_AutocompleteUrl] value="[{$confstrs.sCel_AutocompleteUrl}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_AUTOCOMPLETE_URL"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_AUTOCOMPLETE_URL"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_CustomerName] value="[{$confstrs.sCel_CustomerName}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_CUSTOMER_NAME"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_CUSTOMER_NAME"}]
                </dd>
                <div class="spacer"></div>
            </dl>            
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this); return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_ANALYTICS"}]</b></a>
            <!--<dl>
                <dt>
                <input type=checkbox name="sCel_analyticsEnabled" [{if $sCel_analyticsEnabled}]checked[{/if}]/>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ANALYTICS_ENABLED"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_ACTIVATE"}]
                </dd>
                <div class="spacer"></div>
            </dl>-->     
            <dl>
                <dt>
                <input type=text size="60" name=confstrs[sCel_AnalyticsUrl] value="[{$confstrs.sCel_AnalyticsUrl}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ANALYTICS_URL"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_URL"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_AnalyticsCustomerId] value="[{$confstrs.sCel_AnalyticsCustomerId}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_ID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_AnalyticsCustomerName] value="[{$confstrs.sCel_AnalyticsCustomerName}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_NAME"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_NAME"}]
                </dd>
                <div class="spacer"></div>
            </dl>              
        </div>
    </div>


    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_EXPORT_SETTINGS"}]</b></a>
            <dl>
                <dt>
                <input type=text size="60" name=confstrs[sCel_FtpServer] value="[{$confstrs.sCel_FtpServer}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_FTP_SERVER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_FTP_SERVER"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_Ftp_User] value="[{$confstrs.sCel_Ftp_User}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_FTP_USER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_FTP_USER"}]
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_Ftp_Password] value="[{$confstrs.sCel_Ftp_Password}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_FTP_PASSWORD"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_FTP_PASSWORD"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_Export_Delimiter] value="[{$confstrs.sCel_Export_Delimiter}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_EXPORT_DELIMITER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_EXPORT_DELIMITER"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                <input type=text size="30" name=confstrs[sCel_Export_FieldWrapper] value="[{$confstrs.sCel_Export_FieldWrapper}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_EXPORT_FIELDWRAPPER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_EXPORT_FIELDWRAPPER"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div> 

    <!--<div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_SYNCHRO_SETTINGS"}]</b></a>
            <dl>
                <dt>
                <input type=text size="60" name=confstrs[sCel_synchroTimeInterval] value="[{$confstrs.sCel_synchroTimeInterval}]" [{$readonly}]>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_FTP_SERVER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_SYNCHRO_TIMEINTERVAL"}]
                </dd>
                <div class="spacer"></div>
            </dl>      
        </div>
    </div>-->

    <!--<div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);
          return false;" class="rc"><b>[{oxmultilang ident="cel_SHOP_OPTIONS_GROUP_SALESPERSON_LIVESIGHT_OPTIONS"}]</b></a>
            <dl>
                <dt>
                <input type=checkbox name="sCel_liveSightEnabled" [{if $sCel_liveSightEnabled}]checked[{/if}]/>
                [{oxinputhelp ident="cel_HELP_SHOP_SETTINGS_SALESPERSON_FTP_SERVER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="cel_SHOP_SETTINGS_SALESPERSON_LIVESIGHT_ACTIVATE"}]
                </dd>
                <div class="spacer"></div>
            </dl>      
        </div>
    </div>--> 
    
    <br>

    <input type="submit" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value = 'save'" [{$readonly}]>


</form>
<style>
.groupExp {
    padding-top: 5px;
    padding-bottom: 5px;
}
</style>
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]