<div class="categoryBox">
    <ul class="tree" id="tree">
        [{foreach from=$questions item=oQuestion}]
            [{assign var="aDynProps" value=$oQuestion->DynamicProperties}]
            [{assign var="blIsHierarchical" value=$aDynProps->DynamicProperty.IsHierarchical}]
            <li class="exp"><a href="[{$oViewConf->getSelfLink()}]cl=search&iQWAction=4&iQWPage=0&sQWQuestionId=[{$oQuestion->Id}][{$searchlink}]"><i></i>[{$oQuestion->SideText}]</a>
                <ul>
                    [{foreach from=$oQuestion->Answers->Items item=oAnswer key=Answerkey}]
                        <li class="end">
                            [{if $blIsHierarchical == "False"}]
                                [{if !$oView->answerIsInSearchPath($oAnswer)}]
                                <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=2&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]" class="withCheckbox"><input type="checkbox" />[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                                [{else}]
                                <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=11&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]" class="withCheckbox"><input type="checkbox" checked="checked"/>[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                                [{/if}]
                            [{else}]
                                <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=2&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]"><i></i>[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                            [{/if}]
                        </li>
                    [{/foreach}]

                    [{if $oQuestion->HasExtraAnswers}]
                        [{foreach from=$oQuestion->ExtraAnswers->Items item="oAnswer"}]
                            <li class="end extra-answers hidden">
                                [{if $blIsHierarchical == "False"}]
                                    [{if !$oView->answerIsInSearchPath($oAnswer)}]
                                    <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=2&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]" class="withCheckbox"><input type="checkbox" />[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                                    [{else}]
                                    <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=11&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]" class="withCheckbox"><input type="checkbox" checked="checked"/>[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                                    [{/if}]
                                [{else}]
                                    <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=2&sQWAnswerId=[{$oAnswer->Id}][{$searchlink}]"><i></i>[{$oAnswer->Text}] ([{$oAnswer->ProductCount}]) </a>
                                [{/if}]
                            </li>
                        [{/foreach}]
                        <li class="end extra-answers-button">
                            <a href="[{$oViewConf->getSelfLink()}]stoken=[{$oViewConf->getSessionChallengeToken()}]&cl=search&iQWAction=4&iQWPage=1&sQWQuestionId=[{$oQuestion->Id}][{$searchlink}]" title="[{oxmultilang ident="cel_QWISER_MOREANSWERS"}]" data-altval="[{oxmultilang ident="cel_QWISER_LESSANSWERS"}]" style="text-align:right">[{oxmultilang ident="cel_QWISER_MOREANSWERS"}]</a>
                        </li>            
                    [{/if}]            

                </ul>
            </li>
        [{/foreach}]
        <script type="text/javascript" href="[{$oViewConf->getModuleUrl('celebros_conversionpro')}]views/azure/src/js/cel_refinmentstree.js"></script>
    </ul>
</div>