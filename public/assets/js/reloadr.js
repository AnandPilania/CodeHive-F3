window.reloadr=function(n){
    var r,t=[];
    return{
        start:function(e){
            r=setInterval(function(){
                n.ajax({
                    url:e.url,
                    method:"GET",
                    dataType:"json",
                    success:function(n){
                        if(t.length<=0)t=n;else for(var r in n)n[r]>t[r]&&location.reload()
                    }
                })
            },e.freq)
        }
    }
}($);