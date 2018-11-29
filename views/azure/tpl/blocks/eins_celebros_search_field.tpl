[{$smarty.block.parent}]
[{if $oxcmp_shop->cel_GetAutocompleteLink()}]
<script type="text/javascript" src="http://[{$oxcmp_shop->cel_GetAutocompleteLink()}]/AutoComplete/Scripts/CelebrosAutoCompleteV2.js"></script>
<script language="javascript">
CelebrosAutoComplete("[{ $oxcmp_shop->cel_GetAutocompleteCustomerName() }]", "searchParam", onSelect, "[{$oxcmp_shop->cel_GetAutocompleteLink()}]", "[{ $oxcmp_shop->cel_GetAutocompleteLink()}]");
function onSelect(aParameter) {
    if ((aParameter["SelectedURL"] != "") && (aParameter["IsAutoComplete"])) {
        window.location = aParameter["SelectedURL"];
    } else {
        window.location = "[{ $oViewConf->getSslSelfLink() }]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=celebros_search&searchparam=" + escape(aParameter["SelectedQuery"]);
    }
}
</script>	
[{/if}]