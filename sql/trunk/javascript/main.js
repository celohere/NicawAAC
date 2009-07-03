google_ad_client = "pub-8407977271374637"
google_ad_slot = "9338844566";
google_ad_width = 125;
google_ad_height = 125;

redirect_event = null;

//mmmhmhm, have a cookie?
var Cookies = {
    init: function () {
        var allCookies = document.cookie.split('; ');
        for (var i=0;i<allCookies.length;i++) {
            var cookiePair = allCookies[i].split('=');
            this[cookiePair[0]] = cookiePair[1];
        }
    },
    get: function (cookie_name)
    {
        var results = document.cookie.match ( cookie_name + '=(.*?)(;|$)' );

        if ( results )
            return ( unescape ( results[1] ) );
        else
            return null;
    },
    create: function (name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
        this[name] = value;
    },
    erase: function (name) {
        this.create(name,'',-1);
        this[name] = undefined;
    }
}
Cookies.init();

function redirect(url, get)
{
    window.location = url+'?'+encodeURI(get);
}

//this loads html content between element_id tags. warn displays loading message.
function ajax(element_id, script_url, get, warn) {
    if (warn)
        document.getElementById(element_id).innerHTML = '<div style="position: absolute; background-color: #990000; color: white;">Loading...</div>';

    new Ajax.Updater(element_id, script_url, { 
        method: 'post',
        parameters: encodeURI(get) + '&ajax=true',
        onComplete: function() {
            document.getElementById('iobox').style.left = Cookies.get('iobox_x');
            document.getElementById('iobox').style.top = Cookies.get('iobox_y');
            document.getElementById('iobox').style['visibility'] = 'visible';
        }
    });

    //reset logout timer
    ticker = 0;
}

function getRef(obj){
    return (typeof obj == "string") ?
    document.getElementById(obj) : obj;
}

function setStyle(obj,style,value){
    getRef(obj).style[style]= value;
}

// found on some website, no idea how it works :D :D
function startDrag(e){
    // determine event object
    if(!e){
        var e=window.event
        };
    // determine target element
    var targ=e.target?e.target:e.srcElement;
    if(targ.className!='draggable'){
        return
    };
    // calculate event X,Y coordinates
    offsetX=e.clientX;
    offsetY=e.clientY;
    // assign default values for top and left properties
    if(!targ.style.left){
        targ.style.left=offsetX+'px'
        };
    if(!targ.style.top){
        targ.style.top=offsetY+'px'
        };
    // calculate integer values for top and left properties
    coordX=parseInt(targ.style.left);
    coordY=parseInt(targ.style.top);
    drag_node=targ;
    // move element
    document.onmousemove=dragDiv;
    document.onmouseup=stopDrag;
}
// continue dragging
function dragDiv(e){
    if(!e){
        var e=window.event
        };
    if(!drag_node){
        return
    };
    // move element
    drag_node.style.left=coordX+e.clientX-offsetX+'px';
    drag_node.style.top=coordY+e.clientY-offsetY+'px';
    return false;
}
// stop dragging
function stopDrag(){
    drag_node=null;
}
document.onmousedown=startDrag;

function iobox_mouseup(){
    if(document.getElementById('iobox').parentNode.id == 'form') {
        Cookies.create('iobox_x',document.getElementById('iobox').style.left,1);
        Cookies.create('iobox_y',document.getElementById('iobox').style.top,1);
    }
}

//retrieves input data from element childs
function getParams(obj) {
    var getstr = '';
    for (i=0; i<obj.childNodes.length; i++) {
        if (obj.childNodes[i].tagName == "INPUT") {
            if (obj.childNodes[i].type == "text" || obj.childNodes[i].type == "password") {
                getstr += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
            }
            if (obj.childNodes[i].type == "checkbox") {
                if (obj.childNodes[i].checked) {
                    getstr += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
                } else {
                    getstr += obj.childNodes[i].name + "=&";
                }
            }
            if (obj.childNodes[i].type == "radio") {
                if (obj.childNodes[i].checked) {
                    getstr += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
                }
            }
        }
        if (obj.childNodes[i].tagName == "SELECT") {
            var sel = obj.childNodes[i];
            getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
        }
        if (obj.childNodes[i].tagName == "TEXTAREA") {
            var sel = obj.childNodes[i];
            getstr += sel.name + "=" + sel.value + "&";
        }
     
    }
    return getstr;
}

function calcFlags(){
    var flags = 0;
    var flagNode = document.getElementById('groups');
    for (var i = 0; i < flagNode.elements.length; i++){
        if(flagNode.elements[i].checked){
            flags = flags*1 + flagNode.elements[i].value*1;
        }
    }
    document.getElementById('groups__flags').value = flags;
}

function server_state()
{
    ajax('server_state','status.php','',false);
    setTimeout ("server_state()",60000);
}

function menu_toggle(node){
    if(node.nextSibling.style['display'] == 'none'){
        node.nextSibling.style['display'] = 'block'
    }else{
        node.nextSibling.style['display'] = 'none'
    }

}

function input_clear(node)
{
    if(node.style.fontStyle == 'italic') {
        node.value = '';
        node.style.fontStyle='normal';
    }
}

setTimeout ('server_state()',60000);

var Guild = {
    parseInvite : function(table_id) {
        document.getElementById(table_id).innerHTML +=
        '<tr><td>name</tr></td>';
    }
}