function connect(div1, div2, color, thickness) {
    var off1 = getDayOffset(div1);
    var off2 = getOffset(div2);
    // bottom right
    var x1 = off1.left + off1.width;
    var y1 = off1.top + off1.height;
    // top right
    var x2 = off2.left + off2.width;
    var y2 = off2.top;
    // distance
    var length = Math.sqrt(((x2-x1) * (x2-x1)) + ((y2-y1) * (y2-y1)));
    // center
    var cx = ((x1 + x2) / 2) - (length / 2);
    var cy = ((y1 + y2) / 2) - (thickness / 2);
    // angle
    var angle = Math.atan2((y1-y2),(x1-x2))*(180/Math.PI);
    // make hr
    var htmlLine = "<div style='padding:0px; margin:0px; height:" + thickness + "px; background-color:" + color + "; line-height:1px; position:absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -moz-transform:rotate(" + angle + "deg); -webkit-transform:rotate(" + angle + "deg); -o-transform:rotate(" + angle + "deg); -ms-transform:rotate(" + angle + "deg); transform:rotate(" + angle + "deg);' />";
    //
    alert(htmlLine);
    document.body.innerHTML += htmlLine; 
}

function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    var _w = el.offsetWidth|0;
    var _h = el.offsetHeight|0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x, width: _w, height: _h };
}

function getDayOffset( el ) {
    var _x = 0;
    var _y = 0;
    var _w = el.offsetWidth|0;
    var _h = el.offsetHeight|0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x, width: 0, height: _h };
}

window.bestPath = function() {
    var divday1 = document.getElementById('day1');
    var divband111in = document.getElementById('band111');
    var divband111out = document.getElementById('band111');
    var divband89in = document.getElementById('band111');
    var divband89out = document.getElementById('band111');
    var div3 = document.getElementById('band89');
    var div4 = document.getElementById('band73');
    var div5 = document.getElementById('band23');
    var div6 = document.getElementById('band114');
    var div7 = document.getElementById('band5');
    var div8 = document.getElementById('band171');
    var div9 = document.getElementById('band96');
    var div10 = document.getElementById('band74');
    var div11 = document.getElementById('band51');
    var div12 = document.getElementById('band34');
    var div13 = document.getElementById('band-1');
    var div14 = document.getElementById('band124');
    connect(div1, div2, "#0F0", 5);
}
