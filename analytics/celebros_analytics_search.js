sCelAnalyticsType = document.getElementById('cel_analyticsType').value;
console.log(sCelAnalyticsType);
if (sCelAnalyticsType == 'search') {
    CelebrosAnalytics.ShowComments = document.getElementById('cel_ShowComments').value;
    CelebrosAnalytics.customerid = document.getElementById('cel_customerId').value;
    CelebrosAnalytics.pagereferrer = document.getElementById('cel_pageReferer').value;
    CelebrosAnalytics.qwisersearchsessionid = document.getElementById('cel_searchHandle').value;
    console.log(document.getElementById('cel_searchHandle').value);
    CelebrosAnalytics.websessionid = document.getElementById('cel_webSessionId').value;
    CelebrosAnalytics.qwisersearchloghandle = document.getElementById('cel_searchLogHandle').value;
//CelebrosAnalytics.issecured = <issecured>; 
//CelebrosAnalytics.datacollector = <datacollector>; 
//CelebrosAnalytics.userid = <userid>; 
    console.log(CelebrosAnalytics.AI_LogSearchResult());
}
else if(sCelAnalyticsType == 'details') {
    CelebrosAnalytics.ShowComments = document.getElementById('cel_ShowComments').value; 
    CelebrosAnalytics.customerid = document.getElementById('cel_customerId').value; 
    CelebrosAnalytics.pagereferrer = document.getElementById('cel_pageReferer').value;
    CelebrosAnalytics.websessionid = document.getElementById('cel_webSessionId').value;
    CelebrosAnalytics.productsku = document.getElementById('cel_productSKU').value; 
    CelebrosAnalytics.productname = document.getElementById('cel_productName').value; 
    CelebrosAnalytics.productprice = document.getElementById('cel_productPrice').value; 
    CelebrosAnalytics.qwisersearchsessionid = document.getElementById('cel_searchHandle').value;
//    CelebrosAnalytics.issecured = <issecured>; 
//    CelebrosAnalytics.qwisersearchsessionid = <qwisersearchsessionid>; 
//    CelebrosAnalytics.productvariant = <productvariant>; 
//    CelebrosAnalytics.productcategory = <productcategory>; 
//    CelebrosAnalytics.datacollector = <datacollector>; 
//    CelebrosAnalytics.userid = <userid>; 
    CelebrosAnalytics.AI_LogProduct();
}