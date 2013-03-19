function connect(div1, div2, offS1, offS2, color, thickness) {
//    alert(div1.offsetLeft+" "+div2);
    var off1 = new Object, off2 = new Object;
    var off1 = GetBox(div1);
    var off2 = GetBox(div2);
//    alert(off1.left+" "+off1.top);
    // bottom right
    var x1 = off1.left + 0.5*off1.width;
    var y1 = off1.top + offS1*off1.height/100;
    // top right
    var x2 = off2.left + 0.5*off2.width;
    var y2 = off2.top + offS2*off2.height/100;
//    alert("x1 "+x1+"x2 "+x2+"y1 "+y1+"y2 "+y2);
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
//   alert(htmlLine);
    document.body.innerHTML += htmlLine;
}

function getOffset( el ) { // return element top, left, width, height
    alert(el);
    var _x = 0;
    var _y = 0;
    var _w = el.offsetWidth|0;
    var _h = el.offsetHeight|0;
//    alert(_w+" height"+_h);
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

//                alert (" Left: " + x + "\n Top: " + y + "\n Width: " + w + "\n Height: " + h);
    return { top: y, left: x, width: w, height: h };
                
            }

