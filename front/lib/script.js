//navbarPart

$(document).ready(function(){
	$("#navbarPart").load("navbar.html");
    $("#footerPart").prev().append("<div class=\"push\"></div>");
    $("#footerPart").load("footer.html");
}
)

function goToByScroll(id){
      // Remove "link" from the ID
    id = id.replace("link", "");
      // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
        'slow');
}

function ubb2html(sUBB)
{
        var i,sHtml=String(sUBB),arrcode=new Array(),cnum=0;

        sHtml=sHtml.replace(/[<>&"]/g,function(c){return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'}[c];});
        sHtml=sHtml.replace(/\r?\n/g,"<br />");
        
        sHtml=sHtml.replace(/\[code\s*(?:=\s*([^\]]+?))?\]([\s\S]*?)\[\/code\]/ig,function(all,t,c){//code特殊处理
                cnum++;arrcode[cnum]=all;
                return "[\tubbcodeplace_"+cnum+"\t]";
        });

        sHtml=sHtml.replace(/\[(\/?)(b|u|i|s|sup|sub)\]/ig,'<$1$2>');
        sHtml=sHtml.replace(/\[color\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/ig,'<font color="$1">');
        sHtml=sHtml.replace(/\[size\s*=\s*(\d+?)\s*\]/ig,'<font size="$1">');
        sHtml=sHtml.replace(/\[font\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/ig,'<font face="$1">');
        sHtml=sHtml.replace(/\[\/(color|size|font)\]/ig,'</font>');
        sHtml=sHtml.replace(/\[back\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/ig,'<span style="background-color:$1;">');
        sHtml=sHtml.replace(/\[\/back\]/ig,'</span>');
        for(i=0;i<3;i++)sHtml=sHtml.replace(/\[align\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\](((?!\[align(?:\s+[^\]]+)?\])[\s\S])*?)\[\/align\]/ig,'<p align="$1">$2</p>');
        sHtml=sHtml.replace(/\[img\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/img\]/ig,'<img src="$1" alt="" />');
        sHtml=sHtml.replace(/\[img\s*=([^,\]]*)(?:\s*,\s*(\d*%?)\s*,\s*(\d*%?)\s*)?(?:,?\s*(\w+))?\s*\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*)?\s*\[\/img\]/ig,function(all,alt,p1,p2,p3,src){
                var str='<img src="'+src+'" alt="'+alt+'"',a=p3?p3:(!isNum(p1)?p1:'');
                if(isNum(p1))str+=' width="'+p1+'"';
                if(isNum(p2))str+=' height="'+p2+'"'
                if(a)str+=' align="'+a+'"';
                str+=' />';
                return str;
        });
        sHtml=sHtml.replace(/\[emot\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\/\]/ig,'<img emot="$1" />');
        sHtml=sHtml.replace(/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/ig,'<a href="$1">$1</a>');
        sHtml=sHtml.replace(/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/ig,'<a href="$1">$2</a>');
        sHtml=sHtml.replace(/\[email\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/email\]/ig,'<a href="mailto:$1">$1</a>');
        sHtml=sHtml.replace(/\[email\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]+?)\s*\[\/email\]/ig,'<a href="mailto:$1">$2</a>');
        sHtml=sHtml.replace(/\[quote\]([\s\S]*?)\[\/quote\]/ig,'<blockquote>$1</blockquote>');
        sHtml=sHtml.replace(/\[flash\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/flash\]/ig,function(all,w,h,url){
                if(!w)w=480;if(!h)h=400;
                return '<embed type="application/x-shockwave-flash" src="'+url+'" wmode="opaque" quality="high" bgcolor="#ffffff" menu="false" play="true" loop="true" width="'+w+'" height="'+h+'"/>';
        });
        sHtml=sHtml.replace(/\[media\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*(\d+)\s*)?)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/media\]/ig,function(all,w,h,play,url){
                if(!w)w=480;if(!h)h=400;
                return '<embed type="application/x-mplayer2" src="'+url+'" enablecontextmenu="false" autostart="'+(play=='1'?'true':'false')+'" width="'+w+'" height="'+h+'"/>';
        });
        sHtml=sHtml.replace(/\[table\s*(?:=\s*(\d{1,4}%?)\s*(?:,\s*([^\]"]+)(?:"[^\]]*?)?)?)?\s*\]/ig,function(all,w,b){
                var str='<table';
                if(w)str+=' width="'+w+'"';
                if(b)str+=' bgcolor="'+b+'"';
                return str+'>';
        });
        sHtml=sHtml.replace(/\[tr\s*(?:=\s*([^\]"]+?)(?:"[^\]]*?)?)?\s*\]/ig,function(all,bg){
                return '<tr'+(bg?' bgcolor="'+bg+'"':'')+'>';
        });
        sHtml=sHtml.replace(/\[td\s*(?:=\s*(\d{1,2})\s*,\s*(\d{1,2})\s*(?:,\s*(\d{1,4}%?))?)?\s*\]/ig,function(all,col,row,w){
                return '<td'+(col>1?' colspan="'+col+'"':'')+(row>1?' rowspan="'+row+'"':'')+(w?' width="'+w+'"':'')+'>';
        });
        sHtml=sHtml.replace(/\[\/(table|tr|td)\]/ig,'</$1>');
        
        sHtml=sHtml.replace(/\[\*\]((?:(?!\[\*\]|\[\/list\]|\[list\s*(?:=[^\]]+)?\])[\s\S])+)/ig,'<li>$1</li>');
        sHtml=sHtml.replace(/\[list\s*(?:=\s*([^\]"]+?)(?:"[^\]]*?)?)?\s*\]/ig,function(all,type){
                var str='<ul';
                if(type)str+=' type="'+type+'"';
                return str+'>';
        });
        sHtml=sHtml.replace(/\[\/list\]/ig,'</ul>');
        
        for(i=1;i<=cnum;i++)sHtml=sHtml.replace("[\tubbcodeplace_"+i+"\t]", arrcode[i]);

        sHtml=sHtml.replace(/(^|<\/?\w+(?:\s+[^>]*?)?>)([^<$]+)/ig, function(all,tag,text){
                return tag+text.replace(/[\t ]/g,function(c){return {'\t':'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',' ':'&nbsp;'}[c];});
        });
        function isNum(s){if(s!=null&&s!='')return !isNaN(s);else return false;}
        
        return sHtml;
}

function getGetByUrl(){
	var str = window.location.href;
	var strArr= str.split("?");
	var resArr={};
	if(strArr.length<=1||strArr[1]==""){
		return resArr;
	}
	var strArr=strArr[1].split("&");
	for (var i = 0; i < strArr.length; i++) {
		var strArr1=String(strArr[i]).split("=");
		if(strArr1.length!=2){
			continue;
		}
		resArr[strArr1[0]]=strArr1[1];
	}
	return resArr;
}

function getByrGetByUrl(){
	var str = window.location.href;
	var strArr= str.split("?");
	var resArr={};
	if(strArr.length<=1||strArr[1]==""){
		return resArr;
	}
	//以#号分割为部分1和部分2
	var strArr=strArr[1].split("#");
	if(strArr.length<=1||strArr[1]==""){
		return resArr;
	}
	var strArr1=strArr[0].split("=");
	if(strArr1.length<=1||strArr1[1]==""){
		return resArr;
	}
	resArr[strArr1[0]]=strArr1[1];
	var strArr2=strArr[1].split("&");
	for (var i = 0; i < strArr2.length; i++) {
		var strTerm=String(strArr2[i]).split("=");
		if(strTerm.length!=2){
			continue;
		}
		resArr[strTerm[0]]=strTerm[1];
	}
	return resArr;
}

function setCookie(name,value,millisecond)
{
	if (millisecond) {
		var date = new Date();
		date.setTime(date.getTime()+millisecond);
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/;";
	// document.cookie = name+"="+value+expires+";";
}
		
function getCookie(name)
{
	var arr,reg=new RegExp('(^| )'+name+'=([^;]*)(;|$)');
	if(arr=document.cookie.match(reg)) return unescape(arr[2]);
	else return null;
}

function setAllCookie(arr,millisecond){
	for (var k in arr){
	    if (arr.hasOwnProperty(k)) {
	    	setCookie(k,arr[k],millisecond);
	    }
	}
}

function delCookie(name) {
	setCookie(name,"",-1);
}

function delAllCookie() {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++)
  		delCookie(cookies[i].split("=")[0] );
}