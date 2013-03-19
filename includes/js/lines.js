function connect(div1, div2, offS1, offS2, color, thickness) {
    alert(div1.offsetLeft+" "+div2);
    var off1 = new Object, off2 = new Object;
    var off1 = GetBox(div1);
    var off2 = GetBox(div2);
    alert(off1.left+" "+off1.top);
    // bottom right
    var x1 = off1.left + 0.5*off1.width;
    var y1 = off1.top + offS1*off1.height/100;
    // top right
    var x2 = off2.left + 0.5*off2.width;
    var y2 = off2.top + offS2*off2.height/100;
    alert("x1 "+x1+"x2 "+x2+"y1 "+y1+"y2 "+y2);
    // distance
    var length = Math.sqrt(((x2 - x1) * (x2 - x1)) + ((y2 - y1) * (y2 - y1)));
    // center
    var cx = ((x1 + x2) / 2) - (length / 2);
    var cy = ((y1 + y2) / 2) - (thickness / 2);
    // angle
    var angle = Math.atan2((y1 - y2), (x1 - x2)) * (180 / Math.PI);
    // make hr
    var htmlLine = "<div style='padding:0px; margin:0px; height:" + thickness + "px; background-color:" + color + "; line-height:1px; position:absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -moz-transform:rotate(" + angle + "deg); -webkit-transform:rotate(" + angle + "deg); -o-transform:rotate(" + angle + "deg); -ms-transform:rotate(" + angle + "deg); transform:rotate(" + angle + "deg);' />";
    //
    alert(htmlLine);
    document.body.innerHTML += htmlLine;
}

function getOffset( el ) { // return element top, left, width, height
    alert(el);
    var _x = 0;
    var _y = 0;
    var _w = el.offsetWidth|0;
    var _h = el.offsetHeight|0;
    alert(_w+" height"+_h);
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x, width: _w, height: _h };
}

function GetBox (div) {

                // Internet Explorer, Firefox 3+, Google Chrome, Opera 9.5+, Safari 4+
                var rect = div.getBoundingClientRect ();
                x = rect.left;
                y = rect.top;
                w = rect.right - rect.left;
                h = rect.bottom - rect.top;

                alert (" Left: " + x + "\n Top: " + y + "\n Width: " + w + "\n Height: " + h);
    return { top: y, left: x, width: w, height: h };
                
            }

window.bestPath = function () {
    var divday1 = document.getElementById('day1');
    var divband111 = document.getElementById('band111');
    var divband89 = document.getElementById('band89');
    var divband73 = document.getElementById('band73');
    var divband23 = document.getElementById('band23');
    var divband114 = document.getElementById('band114');
    var divband5 = document.getElementById('band5');
    var divband171 = document.getElementById('band171');
    var divband96 = document.getElementById('band96');
    var divband74 = document.getElementById('band74');
    var divband51 = document.getElementById('band51');
    var divband34 = document.getElementById('band34');
    var divband124 = document.getElementById('band124');
    var divband98 = document.getElementById('band98');
    var divband86 = document.getElementById('band86');
    var divband61 = document.getElementById('band61');
    var divband42 = document.getElementById('band42');
    var divband78 = document.getElementById('band78');
    var divband132 = document.getElementById('band132');
    var divband10 = document.getElementById('band10');
    var divbandbeer = document.getElementById('band-1');
    var divband101 = document.getElementById('band101');
    var divband143 = document.getElementById('band143');
    var divband68 = document.getElementById('band68');
   connect(divday1, divband111, 0, 0, "#0F0", 5);
    
   connect(divband111, divband111, 0, 88.89, "#0F0", 5);

   connect(divband111, divband89, 88.89, 33.33, "#0F0", 5);
   connect(divband89, divband89, 33.33, 100, "#0F0", 5);
    connect(divband89, divband73, 100, 50, "#0F0", 5);
    connect(divband73, divband73, 50, 90, "#0F0", 5);
    connect(divband73, divband23, 90, 33.33, "#0F0", 5);
    connect(divband23, divband23, 33.33, 100, "#0F0", 5);
    connect(divband23, divband114, 100, 44.44, "#0F0", 5);
    connect(divband114, divband114, 44.44, 88.89, "#0F0", 5);
    connect(divband114, divband5, 88.89, 11.11, "#0F0", 5);
    connect(divband5, divband5, 11.11, 55.56, "#0F0", 5);
    connect(divband5, divband171, 55.56, 0, "#0F0", 5);
    connect(divband171, divband171, 0, 55.56, "#0F0", 5);
    connect(divband171, divband96, 55.56, 0, "#0F0", 5);
    connect(divband96, divband96, 0, 100, "#0F0", 5);
    connect(divband96, divband74, 100, 40, "#0F0", 5);
    connect(divband74, divband74, 40, 80, "#0F0", 5);
    connect(divband74, divband51, 80, 33.33, "#0F0", 5);
    connect(divband51, divband51, 33.33, 77.78, "#0F0", 5);
    connect(divband51, divband34, 77.78, 11.11, "#0F0", 5);
    connect(divband34, divband34, 11.11, 100, "#0F0", 5);
    connect(divband34, divbandbeer, 100, 53.33, "#0F0", 5);
    connect(divbandbeer, divbandbeer, 53.33, 55.56, "#0F0", 5);
    connect(divbandbeer, divband124, 55.56, 0, "#0F0", 5);
    connect(divband124, divband124, 0, 40, "#0F0", 5);
    connect(divband124, divband98, 40, 41.67, "#0F0", 5);
    connect(divband98, divband98, 41.67, 100, "#0F0", 5);
    connect(divband98, divband86, 100, 20, "#0F0", 5);
    connect(divband86, divband86, 20, 60, "#0F0", 5);
    connect(divband86, divband61, 60, 14.29, "#0F0", 5);
    connect(divband61, divband61, 14.29, 100, "#0F0", 5);
    connect(divband61, divband42, 100, 85.71, "#0F0", 5);
    connect(divband42, divband42, 85.71, 100, "#0F0", 5);
    connect(divband42, divband78, 100, 16.67, "#0F0", 5);
    connect(divband78, divband78, 16.67, 100, "#0F0", 5);
    connect(divband78, divband132, 100, 28.57, "#0F0", 5);
    connect(divband132, divband132, 28.57, 85.71, "#0F0", 5);
    connect(divband132, divband10, 85.71, 42.86, "#0F0", 5);
    connect(divband10, divband10, 42.86, 61.9, "#0F0", 5);
    connect(divband10, divbandbeer, 61.9, 85.56, "#0F0", 5);
    connect(divbandbeer, divbandbeer, 85.56, 87.78, "#0F0", 5);
    connect(divbandbeer, divband101, 87.78, 0, "#0F0", 5);
    connect(divband101, divband101, 0, 20, "#0F0", 5);
    connect(divband101, divband143, 20, 22.22, "#0F0", 5);
    connect(divband143, divband143, 22.22, 44.44, "#0F0", 5);
    connect(divband143, divband68, 44.44, 33.33, "#0F0", 5);
    
}