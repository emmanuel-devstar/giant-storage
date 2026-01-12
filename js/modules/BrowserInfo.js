class BrowserInfo {
    constructor($) {   
        var browserInfo = this.getBrowserInfo();           
        let html = document.querySelector("html");   
        
        html.classList.add(browserInfo["name"]);
        html.classList.add(browserInfo["ver"]);             
    }    
    
    getBrowserInfo()
    {
        var ua = navigator.userAgent, tem,
            M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1]))
        {
            tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
            return 'IE '+(tem[1] || '');
        }
        if(M[1]=== 'Chrome')
        {
            tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
            if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
        }
        M = M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if((tem= ua.match(/version\/(\d+)/i))!= null)
        M.splice(1, 1, tem[1]);

        var browserInfo = M.join(' ').split(" ");
        name = browserInfo[0].toLowerCase();
        
        var ver = browserInfo[0]+'-'+browserInfo[1];
        
        return {name: name, ver: ver}
    }
}
  
export default BrowserInfo

