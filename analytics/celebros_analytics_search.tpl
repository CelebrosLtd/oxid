[{$smarty.block.parent}]

[{if $oViewConf->isCelAnalyticsEnabled()}]
    [{if $sCelAnalyticsType}]
    [{assign var="sToolBoxURL" value=$oViewConf->getToolboxScript()}]
    [{oxscript include=$sToolBoxURL}]
    <form>
        <input type="hidden" name="cel_ShowComments" id="cel_ShowComments" value="true" />
        <input type="hidden" name="cel_customerId" id="cel_customerId" value="[{$oViewConf->getCelebrosCustomerId()}]" />
        <input type="hidden" name="cel_searchHandle" id="cel_searchHandle" value="[{$oViewConf->getCelebrosSearchSessionId()}]" />
        <input type="hidden" name="cel_searchLogHandle" id="cel_searchLogHandle" value="[{$oViewConf->getCelebrosSearchLogHandle()}]" />
[{if $sCelAnalyticsType == 'details'}]
        <input type="hidden" name="cel_productSKU" id="cel_productSKU" value="[{$oDetailsProduct->getId()}]" />
        <input type="hidden" name="cel_productName" id="cel_productName" value="[{$oDetailsProduct->oxarticles__oxtitle->value}]" />
[{assign var="oCelProductPrice" value=$oDetailsProduct->getPrice()}]
        <input type="hidden" name="cel_productPrice" id="cel_productPrice" value="[{$oCelProductPrice->getBruttoPrice()}]" />
[{/if}]
        <input type="hidden" name="cel_pageReferer" id="cel_pageReferer" value="[{$oViewConf->getHTTPReferer()}]" />
        <input type="hidden" name="cel_webSessionId" id="cel_webSessionId" value="[{$oViewConf->getWebsessionId()}]" />
        <input type="hidden" name="cel_analyticsType" id="cel_analyticsType" value="[{$sCelAnalyticsType}]" />
    </form>
    [{oxscript include=$oViewConf->getModuleUrl('celebros_conversionpro')|cat:"analytics/celebros_analytics_search.js"}]
    <div id="CelebrosAnalyticsCommentDiv1"></div>
    [{/if}]
[{/if}]