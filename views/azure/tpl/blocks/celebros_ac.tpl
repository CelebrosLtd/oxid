[{$smarty.block.parent}]
[{if $oxcmp_shop->cel_GetAutocompleteLink()}]
<script language="javascript">
var celAutocompleteCustomerName = "[{ $oxcmp_shop->cel_GetAutocompleteCustomerName() }]";
var celAutocompleteLink = "[{$oxcmp_shop->cel_GetAutocompleteLink()}]";
var celTargetLocation = "[{ $oViewConf->getSslSelfLink() }]stoken=[{$oViewConf->getSessionChallengeToken()}]"; 
</script>
[{oxscript include=$oViewConf->getACScriptUrl() priority=1000}]
[{oxscript include=$oViewConf->getModuleUrl("celebros_conversionpro", "out/src/js/ac.js") priority=1001}]
[{/if}]