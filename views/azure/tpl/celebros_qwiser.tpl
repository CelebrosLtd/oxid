[{capture append="oxidBlock_content"}]
[{assign var="search_title" value="PAGE_SEARCH_SEARCH_TITLE"|oxmultilangassign}]
[{assign var="searchparamforhtml" value=$oView->getSearchParamForHtml() }]
[{assign var="template_title" value="$search_title - $searchparamforhtml"}]
[{assign var="search_head" value="PAGE_SEARCH_SEARCH_HITSFOR"|oxmultilangassign}]
[{assign var="search_head" value=$oView->getArticleCount()|cat:" "|cat:$search_head|cat:" &quot;"|cat:$searchparamforhtml|cat:"&quot;"}]
[{assign var='rsslinks' value=$oView->getRssLinks() }]

[{*//string str="\u1000";
http://il1.php.net/mb_convert_encoding
*}]
[{if $rsslinks.searchArticles}]
    [{assign var="imgUrl" value=$oViewConf->getImageUrl()|cat:"rss.png"}]
        [{assign var="search_head" value="`$search_head` <a class=\"rss external\" id=\"rssSearchProducts\" href=\"`$rsslinks.searchArticles.link`\" title=\"`$rsslinks.searchArticles.title`\"><img src=\"$imgUrl\" alt=\"`$rsslinks.searchArticles.title`\"><span class=\"FXgradOrange corners glowShadow\">`$rsslinks.searchArticles.title`</span></a>"}]
[{/if}]

[{assign var='customMessages' value=$oView->getCustomMsgs() }]
[{if $customMessages}]
[{foreach from=$customMessages item=custMessage}]
[{$custMessage->custom_message}]
[{/foreach}]
[{/if}]
[{assign var='bannerCampaigns' value=$oView->getBannerCampaigns() }]
[{if $bannerCampaigns}]
	[{* Please notice that it is possible to have more than one banner campaign *}]
	<a href='[{$bannerCampaigns[0]->banner_landing_page}]'><img style="margin-bottom: 9px; margin-top: 9px;" id="imgBanner" src="[{$bannerCampaigns[0]->banner_image}]"></a>
[{/if}]
  <h1 class="pageHead">[{$search_head}]</h1>
  [{block name="search_results"}]

	[{* ########### START - change for celebros interface ########################## *}]
	[{assign var="template_title" value="cel_QWISER_TITLE"|oxmultilangassign}]
	
	[{ assign var="template_location" value=" <a href=\""|cat:$oViewConf->getSelfLink()|cat:"cl=search"|cat:$searchlink|cat:"\">"|cat:$searchparam|cat:"</a>" }]
	
	[{if $aSearchPath->Count > 0 }]
	  [{assign var="iStartIndex" value="1"}]
	  [{foreach from=$aSearchPath->Items item=oSearchPath}]
	    [{ assign var="template_location" value=$template_location|cat:" / <a href=\""|cat:$oViewConf->getSelfLink()|cat:"cl=search&iQWAction=3&sQWSearchHandle="|cat:$SearchHandle|cat:"&iQWStartIndex="|cat:$iStartIndex|cat:$searchlink|cat:"\">"|cat:$oSearchPath->Answers->Items[0]->Text|cat:"</a>"}]
	    [{assign var="iStartIndex" value=$iStartIndex+1}]
	  [{/foreach}]
	[{/if}]

	[{if $oView->getArticleCount() }]
    <div class="listRefine clear topRound">
        [{block name="search_top_listlocator"}]
        [{include file="celebros_listlocator.tpl" locator=$pageNavigation listDisplayType=true itemsPerPage=true sort=true}]
        [{/block}]
    </div>
	[{*[{else}]
    <div class="msg">[{ oxmultilang ident="PAGE_SEARCH_SEARCH_NOITEMSFOUND" }]
    </div>
	*}]
  [{/if}]

    [{assign var='alternativeProductsMsg' value=$oView->getAlternativeProductsMsg() }]
    [{if $alternativeProductsMsg}]
    <div class="msg">
        [{oxmultilang ident="FRONTEND_ALTERNATIVE_PRODUCTS"}]: 
        [{foreach from=$alternativeProductsMsg item=altmsg}]
    	<a href="[{$oViewConf->getSelfLink()}]cl=search&searchparam=[{$altmsg}]&stoken=[{$oViewConf->getSessionChallengeToken()}]">[{$altmsg}]</a>
        [{/foreach}]
    </div>
    [{/if}]
  
	[{* Service error messages *}]
	[{if $ErrorMessage }]
		<div class="error">
		  [{ oxmultilang ident="cel_QWISER_ERROR" }] : [{ $ErrorMessage }]
		</div>
	[{/if}]
	[{* Recomended message and Additional suggestions*}]
	[{if $RecommendedMessage || $aAdditionalSuggestions}] 
	<div class="msg">
		[{if $RecommendedMessage}][{ $RecommendedMessage }][{/if}]
		
		[{if $aAdditionalSuggestions->Count}]
		  <br><br>[{ oxmultilang ident="cel_QWISER_SUGGESTIONS" }]: 
		  [{foreach from=$aAdditionalSuggestions->Items item=Suggestion }]
		    <b><a href="[{$oViewConf->getSelfLink()}]&cl=search&searchparam=[{$Suggestion|escape:'url'}]">[{$Suggestion}]</a></b>
		  [{/foreach}]
		  ?
		[{/if}]
	</div>	
	[{/if}]	  
  
	[{* Lead Question *}]
	[{if $LeadQuestion }]
	  [{strip}]
	  
		[{assign var="moreAnswersText" value="cel_QWISER_MOREANANSVERSFORQUESTION"|oxmultilangassign|cat:$LeadQuestion->SideText }]
		[{assign var="tplPageSize" value=4 }]
		[{assign var="tplCurrentPage" value=$smarty.request.tplSetPage|default:"1" }]
		[{math assign="tplTotalCount" equation="acnt+ecnt" acnt=$LeadQuestion->Answers->Count|default:0 ecnt=$LeadQuestion->ExtraAnswers->Count|default:0 }]
		[{math assign="tplPageCount" equation="ceil(tcnt/psize)" tcnt=$tplTotalCount psize=$tplPageSize }]
		
		[{if $tplCurrentPage > 1 && $tplCurrentPage le $tplPageCount }]
		  [{assign var="tplPrevPage" value=$tplCurrentPage-1}]
		[{/if}]
		
		[{if $tplCurrentPage lt $tplPageCount }]
		  [{assign var="tplNextPage" value=$tplCurrentPage+1}]
		[{/if}]
		
		[{assign var="tplCounter" value=0}]
		[{assign var="column1" value=""}]
		[{assign var="column2" value=""}]
		[{assign var="select1" value=""}]
		[{assign var="select2" value=""}]
		[{assign var="moreAnswersCount" value=0}]
		
		[{assign var="tplCurrentAnswersCount" value=0}]
		[{if $LeadQuestion->HasExtraAnswers}]
		  [{assign var="tplDoLoops" value=2}]
		[{else}]
		  [{assign var="tplDoLoops" value=1}]
		[{/if}]
		
		[{math assign="tplCellWidth" equation="100/(psize)" psize=$tplPageSize format="%.2f" }]
		
		[{section name="loop" loop=$tplDoLoops }]
		    [{cycle assign="AnswersArray" values="Answers,ExtraAnswers"}]
			[{foreach from=$LeadQuestion->$AnswersArray->Items item=oAnswer key=Answerkey}]
			  [{assign var="tplCounter" value=$tplCounter+1 }]
			  [{math assign="tplPageNr" equation="ceil(tcnt/psize)" tcnt=$tplCounter psize=$tplPageSize }]
			  
			  [{capture assign="tmp1"}]
			  <td align="center" width="[{ $tplCellWidth }]%" valign="top" id="tplAnswerL1_[{$tplCounter}]" class="[{if $tplPageNr == $tplCurrentPage }]tplVisibleCell[{else}]tplHiddenCell[{/if}]">
			  	<a href="[{$oViewConf->getSelfLink()}]cl=search&iQWAction=2&sQWAnswerId=[{ $oAnswer->Id }][{$searchlink}]">
			  	  <img src="[{ $oViewConf->getPictureDir()}][{$oAnswer->ImageUrl }]" border="0" alt="[{ $oAnswer->Text }]">
			  	</a>  
		      </td>
		      [{/capture}]
		      [{assign var="column1" value=$column1|cat:$tmp1}]
		      
		      [{capture assign="tmp2"}]
			  <td  align="center" width="[{ $tplCellWidth }]%" id="tplAnswerL2_[{$tplCounter}]" class="[{if $tplPageNr == $tplCurrentPage }]tplVisibleCell[{else}]tplHiddenCell[{/if}]">
		        <a href="[{$oViewConf->getSelfLink()}]cl=search&iQWAction=2&sQWAnswerId=[{ $oAnswer->Id }][{$searchlink}]" class="categorylink">[{ $oAnswer->Text }] ([{ $oAnswer->ProductCount }])</a>
		      </td>
		      [{/capture}]
		      [{assign var="column2" value=$column2|cat:$tmp2}]
		      
		      [{capture assign="tmp3"}]
		        [{if $tplPageNr != $tplCurrentPage }]
		          <option value="[{ $oAnswer->Id }]">[{ $oAnswer->Text }] ([{ $oAnswer->ProductCount }])</option>
		          [{assign var="moreAnswersCount" value=$moreAnswersCount+1}]
		        [{/if}]
		      [{/capture}]
		      [{assign var="select1" value=$select1|cat:$tmp3}]
		      
		      [{capture assign="tmp4"}]
		        tplOptions[[{$tplCounter}]] = new Array("[{ $oAnswer->Id }]","[{ $oAnswer->Text }] ([{ $oAnswer->ProductCount }])");
		      [{/capture}]
		      [{assign var="select2" value=$select2|cat:$tmp4}]
		      
		    [{/foreach}]
	    [{/section}]
	    
	    [{capture assign="allpages"}]
		  <table width="100%" cellspacing="5" >
	        <tr>[{$column1}]</tr>
	        <tr>[{$column2}]</tr>
	      </table>
	    [{/capture}]
	  [{/strip}]
	<div class="categorytitlerow">
	  [{ $LeadQuestion->Text }]
	</div>
	<div class="categorydetailsrow">
	    <style>
	      td.tplHiddenCell { display:none; }
	      td.tplVisibleCell { display:inline; }
	      html>body  td.tplVisibleCell { display:table-cell; }
	    </style>
	    <script language="JavaScript" type="text/javascript">
	  	  var tplPageSize=[{ $tplPageSize }];
	  	  var tplCurrentPage=[{ $tplCurrentPage }];
	  	  var tplTotalCount=[{ $tplTotalCount }];
	  	  var tplPageCount=[{ $tplPageCount }];
	  	  
	  	  var tplOptions = new Array();
	  	  [{ $select2 }]
	  	  
	  	  function tplPrevPage()
	  	  {
	  	     tplSetPage(tplCurrentPage-1);
	  	     return false;
	  	  }
	  	  
	  	  function tplNextPage()
	  	  {
	  	     tplSetPage(tplCurrentPage+1);
	  	     return false;
	  	  }
	  	  
	  	  function tplSetPage(tplPage)
	  	  {
	  	    var start = (tplPage-1)*tplPageSize+1;
	  	    var end	  = tplPage*tplPageSize;
	  	    var offset = (tplCurrentPage - tplPage) * tplPageSize;
	  	 
	  	    var select = document.getElementById("tplSelectBox");
	        select.options.length = 1;
	        select.selectedIndex = 0;
	
	  	    for( var j = 1; j <= tplTotalCount; j++)
	  	    {
	  	        select.options[j] = new Option( tplOptions[j][1],tplOptions[j][0]);
	  	        select.options[j].innerHTML = tplOptions[j][1];
	  	    }
	  	    
	  	    for( var i = end; i >= start ; i-- )
	  	    {
				if( (i+offset) <= tplTotalCount)
				{
					document.getElementById("tplAnswerL1_"+(i+offset)).className = "tplHiddenCell";
					document.getElementById("tplAnswerL2_"+(i+offset)).className = "tplHiddenCell";
				}
				
				if(i <= tplTotalCount)
				{
					document.getElementById("tplAnswerL1_"+i).className = "tplVisibleCell";
					document.getElementById("tplAnswerL2_"+i).className = "tplVisibleCell";
					select.options[i] = null;
				}
	  	    }
	  	    
	  	    select.options[0].innerHTML = "[{ $moreAnswersText }] ("+(select.options.length-1)+")";
	
	  	    tplCurrentPage = tplPage; 
	  	    
	  	    
	  	    var tplPrevLink = document.getElementById("tplPrevPageLink");
	  	    if(tplCurrentPage>1 && tplCurrentPage <= tplPageCount)
	  	    {	
	  	    	tplPrevLink.style.display="inline";
	  	    	tplPrevLink.disabled="false";
	  	    }
	  	    else
	  	    {
	  	    	tplPrevLink.style.display="none";
	  	    	tplPrevLink.disabled="true";
	  	    }
	  	    
	  	    var tplNextLink = document.getElementById("tplNextPageLink");
	  	    if(tplCurrentPage < tplPageCount)
	  	    {	
	  	    	tplNextLink.style.display="inline";
	  	    	tplNextLink.disabled="false";
	  	    }
	  	    else
	  	    {
	  	    	tplNextLink.style.display="none";
	  	    	tplNextLink.disabled="true";
	  	    }
	  	    
	  	  }
	  	  
		</script>
	  
	    <table cellspacing="0" style="margin-right:-3px;min-width:100%;width:94%;">
	     <tr>
	       <td align="left">
	         <a id="tplPrevPageLink" href="[{$oViewConf->getSelfLink()}]cl=search[{$searchlink}]&tplSetPage=[{$tplPrevPage}]" style="display:[{if $tplPrevPage}]inline[{else}]none[{/if}]" onClick="tplPrevPage(); return false"><img src="[{$oViewConf->getImageUrl()}]filledqwiserleftarrow.gif" border="0"></a>
	       </td>
	       <td id="tplCurrentAnswersBox">[{ $allpages }]</td>
	       <td align="right">
	         <a id="tplNextPageLink" href="[{$oViewConf->getSelfLink()}]cl=search[{$searchlink}]&tplSetPage=[{$tplNextPage}]" style="display:[{if $tplNextPage}]inline[{else}]none[{/if}]" onClick="tplNextPage(); return false"><img src="[{$oViewConf->getImageUrl()}]filledqwiserrightarrow.gif" border="0"></a>
	       </td>
	    </tr>
	    [{if $moreAnswersCount}]
	      <tr>
	      	<td colspan="3" align="right">
	      	<div class="categoryline"></div>
	      	<form name="answer" action="[{$shop->selfactionlink}]" method="post" style="margin:0;padding:0">
	    	  [{ $shop->hiddensid }]
	    	  <input type="hidden" name="cl" value="[{$shop->cl}]">
			  <input type="hidden" name="iQWAction" value="2">	
			  <input type="hidden" name="sQWSearchHandle" value="[{ $sCelebrosLogHandle }]">	
	          <input type="hidden" name="searchparam" value="[{$searchparam}]">
	    	  <input type="hidden" name="listtype" value="[{$sListType}]">
	      	  <input type="hidden" name="tplSetPage" value="1">
	      	  <select name="sQWAnswerId" onchange="submit();" id="tplSelectBox" >
	      	    <option value="" class="categorylink">[{ $moreAnswersText }] ([{ $moreAnswersCount }])</option>
	      	    [{$select1}]
	      	  </select>
	      	  <noscript>   
	            <input class="font10 fontgray1 fontbold address_deliverybutton" type="submit" value="GO!" >
	          </noscript>
	      	</form>
	      	</td>
	      </tr>	
	      [{/if}]
	      
	    </table>
	</div>
	[{/if}]
	
[{* ########### END - change for celebros interface ############################# *}]
	
	[{if $oView->getArticleList() }]
    [{foreach from=$oView->getArticleList() name=search item=product}]
      [{include file="widget/product/list.tpl" type=$oView->getListDisplayType() listId="searchList" products=$oView->getArticleList() }]
    [{/foreach}]
  [{/if}]

<div class="listRefine clear bottomRound">	
  [{if $oView->getArticleCount() }]
    [{include file="celebros_listlocator.tpl" locator=$pageNavigation listDisplayType=true }]
  [{/if}]
</div>
  [{/block}]
[{ insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{assign var="template_title" value="PAGE_SEARCH_SEARCH_TITLE"|oxmultilangassign}]

[{include file="layout/page.tpl" title=$template_title location="PAGE_SEARCH_SEARCH_LOCATION"|oxmultilangassign sidebar="Left"}]