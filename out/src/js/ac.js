jQuery(document).ready(function() {
    jQuery('#searchParam').attr('autocomplete', 'off');
    CelebrosAutoCompleteV3(
        celAutocompleteCustomerName,
        "searchParam",
        celebrosOnSelect,
        celAutocompleteLink,
        celAutocompleteLink,
        {
            acShowType: "centered",
            btnSearch: "search_button",
            maxControl: "server"                    
        }
    );
});
function celebrosOnSelect(aParameter) {
    if ((aParameter["SelectedURL"] != "") && (aParameter["IsAutoComplete"])) {
        var sCmp = (aParameter["SelectedURL"].indexOf("?") == -1) ? "?" : "&";
        window.location = aParameter["SelectedURL"] + sCmp + "cmp=cel" + "&trigger=ac";
    } else { 
        var targetLocation = celTargetLocation+"&cl=search&searchparam=" + encodeURIComponent(aParameter["SelectedQuery"]);
        if (aParameter["IsAutoComplete"].toString().toLowerCase() == "true") targetLocation += "&Trigger=ac";
        window.location = targetLocation;
    }
};