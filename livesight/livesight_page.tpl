[{$smarty.block.parent}]
[{if $oViewConf->isLiveSightEnabled()}]
<!-- BEGIN: LiveSight.Celebros-->
<script type="text/javascript">
    (function()
    {
        var ms = document.createElement("script");
        var site = ("https:" == document.location.protocol) ?
                "https://col-ls.celebros.com/cjs.aspx" : "http://col-ls.celebros.com/cjs.aspx";
        ms.src = site;
        ms.setAttribute("async", "true");
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(ms, s);
    })();
</script>
<!-- END: LiveSight.Celebros -->
[{/if}]