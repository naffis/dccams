
var _u=navigator.userAgent.toLowerCase();
function _ua(t) {
    return _u.indexOf(t) != -1;
}

function _uan(t) {
    if (!window.RegExp) {return 0;}
    var r=new RegExp(t + "([0-9]*)");
    var s=r.exec(_u);
    var ret=0;
    if (s.length >= 2) {ret=s[1];}
    return ret;
}

function _activeX() {
    var success=false;
    eval('try {new ActiveXObject("Microsoft.XMLDOM");success=true;} catch (e) {}');
    return success;
}

function _compat() {
    return ((_ua('opera') && (_ua('opera 7.5') || _ua('opera/8'))) ||(_ua('safari') && _uan('safari/') >= 125) ||(_ua('msie') &&!_ua('msie 4') && !_ua('msie 5.0') && !_ua('msie 5.1') &&!_ua('msie 3') && !_ua('powerpc') && _activeX()) ||(document.getElementById && window.XSLTProcessor &&window.XMLHttpRequest && !_ua('netscape6') &&!_ua('netscape/7.0')));
}

var _c=_compat();
function _load() {
    if (!_c) 
    	return;
    document.write('<' + 'script src="http://maps.google.com/maps?file=js" type="text/javascript"><' + '/script>');
}

function _display(_x, width, height) {
    if (!_c) 
    	return;
    document.getElementById('loading').style.display='none';
    
    var _cont=document.getElementById('map');
    _m=new _Map(_cont, null, width, height, 0);
    _m.loadVPageStr(_x);
    _m.showMapControl();
    if (_m.mapTypes.length > 1) {
        var _e=document.getElementById('maptoggle');
        _m.createSpecToggleLinks(_e, true);
    }          
}


