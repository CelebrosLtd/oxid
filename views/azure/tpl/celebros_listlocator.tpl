[{include file="widget/locator/attributes.tpl"}]
<div class="refineParams clear[{if $place eq "bottom"}] bottomParams[{/if}]">
    [{if $locator}]
        [{if $pageNavigation->changePage}]
            <div class="pager [{if $place eq "bottom"}] lineBox[{/if}]" id="itemsPager[{$place}]">
            [{if $pageNavigation->previousPage }]
                <a class="prev" href="[{$pageNavigation->previousPage}]">[{oxmultilang ident="WIDGET_PRODUCT_LOCATOR_PREV"}]</a>
            [{/if}]
                [{assign var="i" value=1}]
                [{assign var="minI" value=$oViewConf->getMinPage($pageNavigation->actPage,4)}]
                [{assign var="maxI" value=$oViewConf->getMaxPage($pageNavigation->actPage,4)}]

                [{foreach key=iPage from=$pageNavigation->changePage item=page}]
        [{if $iPage > $minI && $iPage < $maxI}]
                    [{ if $iPage == $i }]
                       <a href="[{$page->url}]" class="page[{if $iPage == $pageNavigation->actPage }] active[{/if}]">[{$iPage}]</a>
                       [{assign var="i" value=$i+1}]
                    [{ elseif $iPage > $i}]
                       <a href="[{$page->url}]" class="page[{if $iPage == $pageNavigation->actPage }] active[{/if}]">[{$iPage}]</a>
                       [{assign var="i" value=$iPage+1}]
                    [{ elseif $iPage < $i }]
                       <a href="[{$page->url}]" class="page[{if $iPage == $pageNavigation->actPage }] active[{/if}]">[{$iPage}]</a>
                       [{assign var="i" value=$iPage+1}]
                    [{/if}]
        [{elseif $iPage == $maxI}]
        ...
        <a href="[{$pageNavigation->lastpage}]">[{$pageNavigation->NrOfPages}]</a>
        [{elseif $iPage == $minI}]
        <a href="[{$pageNavigation->firstpage}]">1</a>
        ...
        [{/if}]
                [{/foreach}]
            [{if $pageNavigation->nextPage }]
                <a class="next" href="[{$pageNavigation->nextPage}]">[{oxmultilang ident="WIDGET_PRODUCT_LOCATOR_NEXT"}]</a>
            [{/if}]
             </div>
        [{/if}]

        [{*include file="widget/locator/paging.tpl" pages=$locator place=$place*}]
    [{/if}]
    [{if $sort }]
        [{include file="widget/locator/sort.tpl"}]
    [{/if}]
    [{if $itemsPerPage }]
        [{include file="widget/locator/itemsperpage.tpl"}]
    [{/if}]
    [{*if $listDisplayType }]
        [{include file="widget/locator/listdisplaytype.tpl"}]
    [{/if*}]
</div>
