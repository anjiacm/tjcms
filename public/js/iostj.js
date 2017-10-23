
var film = window.location.pathname.substring(1,window.location.pathname.lastIndexOf("/")),
    film = film.substring(2),
    qd = getURLParameter('qd') || 'yrlm_1';
var arr_qd = qd.split('_'),
    qdname=typeof(arr_qd[0])!='undefined'&& arr_qd[0] ?arr_qd[0]:'yrlm',
    qdid=typeof(arr_qd[1])!='undefined' && arr_qd[1] ?arr_qd[1]:1;
if(typeof(arr_qd[2])!='undefined'&& arr_qd[2]){
    qdid=arr_qd[2];
}
qdid = isNaN(qdid) ? 1 : qdid;
qd=qdname+'_'+film+'_'+parseInt(qdid);
var theos,themodel;

function  phoneos(){
    Array.prototype.contains = function(needle) {
        for (i in this) {
            if (this[i].indexOf(needle) > 0)
                return i;
        }
        return -1;
    }

    var device_type = navigator.userAgent;//获取userAgent信息

    var md = new MobileDetect(device_type);//初始化mobile-detect
    //document.write(md);//打印到页面
    console.log(md);
    var os = md.os();//获取系统
    var model = "";
    if (os == "iOS") {//ios系统的处理
        os = md.os() + md.version("iPhone");
        model = md.mobile();
    } else if (os == "AndroidOS") {//Android系统的处理
        os = md.os() + md.version("Android");
        var sss = device_type.split(";");
        var i = sss.contains("Build/");
        if (i > -1) {
            model = sss[i].substring(0, sss[i].indexOf("Build/"));
        }
    }
    theos=os;themodel=model;
}

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}
phoneos();
console.log(theos);
console.log(themodel);
console.log(qd);
gopost();
function gopost() {
    $.ajax({
        type: "post",
        url: "http://tjnew.pingoula.net/?r=api/default/phone",
        data: {system:theos,iphonetype:themodel,film:film,qdname:qdname,qdid:qdid,qd:qd},
        dataType : "jsonp",
        jsonp: "videocallback",
        jsonpCallback:"filecallback",
        success: function (data) {
             // alert(data);

        }
    });

}