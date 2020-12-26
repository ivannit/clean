function setSizeSession(url) {
    var formData = new FormData();
    formData.append("screenwidth", window.screen.width);
    vanillaLoad(url + "/scripts/size.php", formData, null);
}

function sharpenBackground() {
    landing = document.getElementById("landing");
    landing.classList.remove("zoom");
    landing.classList.add("full");
}

function inputPicture(id) {
    var pictures = document.getElementsByName("pictures[]");
    if (pictures.length < 50) {
        var label = document.createElement("label");
        var input = document.createElement("input");
        document.getElementById(id).append(label);
        input.setAttribute("type", "file");
        input.setAttribute("name", "pictures[]");
        input.setAttribute("accept", "image/*");
        input.setAttribute("title", "Bild-Datei auswählen");
        label.append(input);
        label.append(document.createElement("br"));
        input.click();
        if (pictures.length === 50) {
            document.getElementById("add-pix").style.display = "none";
        }
    }
}

function customFrequency() {
    var oldSelect = document.getElementById("repeat");
    var daysInput = document.getElementById("days");
    if (oldSelect.value === "Tagesintervall...") {
        oldSelect.name = "switch";
        daysInput.name = "repeat";
        daysInput.classList.remove("hidden");
    } else {
        document.getElementById("repeat").name = "repeat";
        document.getElementById("days").name = "switch";
        document.getElementById("days").classList.add("hidden");
    }
}

function vanillaLoad(php, data, callback) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            if (typeof callback === "function") {
                callback(this.responseText);
            }
        }
    };
    xhttp.open("POST", php, true);
    xhttp.send(data);
}

function saveNote(url, formid) {
    showRunning();
    var date = document.getElementById("expiry-date").value;
    var time = document.getElementById("expiry-time").value;
    var formData = new FormData(document.getElementById(formid));
    formData.append("text", document.getElementById("text").value);
    formData.append("expiry", date + "T" + time);
    formData.append("repeat", document.getElementsByName("repeat")[0].value);
    formData.append("alarm", document.getElementById("alarm").checked);
    vanillaLoad(url + "/notes/save", formData, function () {
        location.reload();
    });
}

function updateNote(obj) {
    var classes = obj.parentNode.className.split(" ");
    var row = document.getElementById(classes[0]);
    var url = row.className;
    var newtext = row.getElementsByClassName("newtext")[0].firstElementChild.value;
    var newexpiry = row.getElementsByClassName("newexpiry")[0].firstElementChild.value;
    var newrepeat = row.getElementsByClassName("newrepeat")[0].firstElementChild.value;
    var newalarm = row.getElementsByClassName("newalarm")[0].firstElementChild.checked;
    var formData = new FormData();
    var dt = new Date(newexpiry);
    try {
        dt.toISOString();
    } catch (err) {
        console.log = err.message;
        newexpiry = new Date().toISOString().slice(0, 10) + "T00:00";
    }
    formData.append("newtext", newtext);
    formData.append("newexpiry", newexpiry);
    formData.append("newrepeat", validateFrequency(newrepeat));
    formData.append("newalarm", newalarm);
    formData.append("noteid", classes[0].replace(/\D/g, ''));
    vanillaLoad(url + "/notes/update", formData, function () {
        location.reload();
    });
}

function validateFrequency(str) {
    var pattern = /^Einmalig|Täglich|Wöchentlich|Monatlich|Jährlich|[1-9][0-9]*$/;
    var result = str.match(pattern);
    if (result.length > 0) {
        return str;
    } else {
        return "Täglich";
    }
}

function filterList(url) {
    var formData = new FormData(document.getElementById("filter-form"));
    vanillaLoad(url + "/scripts/notes.php", formData, showList);
}

function showList(response) {
    document.getElementById("list").innerHTML = response;
}

function transformToArea(obj) {
    var content = obj.value;
    obj.parentNode.innerHTML = '<textarea id="area" onblur="transformToInput(this)" onchange="updateNote(this)">'
            + content + '</textarea>';
    var area = document.getElementById("area");
    area.focus();
    area.setSelectionRange(area.value.length, area.value.length);
}

function transformToInput(obj) {
    var content = obj.innerHTML;
    obj.parentNode.innerHTML = '<input type="text" value="' + content + '" onclick="transformToArea(this)">';
}

function resizePix(obj, h) {
    obj.style.height = h;
    obj.style.transition = ".1s ease-in";
}

function highlightRow(noteid, opac) {
    var row = document.getElementById(noteid);
    var imgs = row.getElementsByTagName("img");
    var cells = document.getElementsByClassName(noteid);
    for (var i = 0; i < cells.length; i++) {
        cells[i].style.opacity = opac;
        cells[i].style.transition = ".1s ease-in";
    }
    for (var i = 0; i < imgs.length; i++) {
        imgs[i].style.opacity = opac;
        imgs[i].style.transition = ".1s ease-in";
    }
}

function maximizePix(url, pixid) {
    var filter = document.getElementById("filter-form");
    var list = document.getElementById("list");
    var a = document.getElementById("maxpixa");
    var img = document.getElementById("maxpix");
    filter.style.opacity = ".2";
    list.style.opacity = ".2";
    a.style.opacity = "1 !important";
    a.href = url + "/notes/maxpix/" + pixid;
    img.src = url + "/scripts/pix.php?id=" + pixid;
    showRunning();
}

function closePix(source) {
    document.getElementById("maxpix").src = source;
    document.getElementById("maxpix").style.maxHeight = 0;
    document.getElementById("maxpix-wrapper").classList.add("hidden");
    document.getElementById("filter-form").style.opacity = .8;
    document.getElementById("list").style.opacity = 1;
}

function showRunning() {
    var img = document.getElementById("maxpix");
    var wrapper = document.getElementById("maxpix-wrapper");
    img.style.maxHeight = window.screen.height / 1.6;
    img.style.maxWidth = window.screen.width / 1.6;
    wrapper.classList.remove("hidden");
}

function expandCreate() {
    document.getElementById("create").style.maxHeight = "50em";
}

function collapseCreate() {
    document.getElementById("create").style.maxHeight = "4em";
}

//CANVAS
var x, y, free, drawing, canvas, ctx, initiated;
var hh = 400;
var cnt = 10;
var m = 0;

function initCanvas() {
    var sketchpad = document.getElementById("sketchpad");
    if (sketchpad !== null) {
        sketchpad.style.visibility = "visible";
        canvas = document.getElementById("canvas");
        free = drawing = initiated = false;
        ctx = canvas.getContext("2d");
        ctx.fillStyle = "transparent";
        hh = canvas.width;
        ctx.fillRect(0, 0, hh, canvas.height);
        initiated = true;
        canvas.addEventListener("mousedown", moDown);
        canvas.addEventListener("mouseup", moUp);
    }
}

function clearCanvas() {
    canvas.width = canvas.width; //YES. THAT'S - WEIRD -
}

function moDown(event) {
    x = event.offsetX;
    y = event.offsetY;
    drawing = true;
    canvas.addEventListener("mousemove", function (e) {
        if (drawing === true) {
            freeDraw(x, y, e.offsetX, e.offsetY);
            x = e.offsetX;
            y = e.offsetY;
        }
    });
}

function moUp(event) {
    freeDraw(x, y, event.offsetX, event.offsetY);
    if (drawing === true) {
        x = y = 0;
        drawing = false;
    }
}
function freeDraw(x1, y1, x2, y2) { //FREI MAUS
    ctx.lineWidth = document.getElementById("linewidth").value;
    ctx.strokeStyle = document.getElementById("linecolor").value;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();
    ctx.closePath();
}

function downloadCanvas() {
    var image = canvas.toDataURL("image/png", 1.0)
            .replace("image/png", "image/octet-stream");
    var link = document.createElement('a');
    link.download = "skizze.png";
    link.href = image;
    link.click();
}

/**
 Mandelbrot set
 square image between -2-2i and 2+2i
 z⌄(n+1)=z⌄(n)^(2)+c remains bounded
 property of complex numbers: i^2=-1
 */
//credits: http://slicker.me/fractals/fractals.htm
function appleman() {
    if (m < 8) { // Erste Iterationen
        m++;
    } else { // Beschleunigung
        m += 4;
    }
    for (var a = 0; a < hh; a++) {
        for (var b = 0; b < hh; b++) {
            calculate(a, b, m);
        }
    }
    var title = (--cnt === 0) ? "Fertig :)" : "Noch " + cnt + "x klicken...";
    document.getElementById("apple").setAttribute("title", title);
}

function fillFractal(a, b, color) {
    ctx.beginPath();
    ctx.rect(a * 1, b * 1, 1, 1);
    ctx.fillStyle = '#' + color + color + color;
    ctx.fill();
}

function calculate(a, b, maxiter) {
    var re = -2 + a / (hh / 4);
    var im = -2 + b / (hh / 4);
    var x = 0;
    var y = 0;
    var z = 0;
    var k = 1;
    do {
        var t = x * y;
        x = x * x - y * y + re;
        y = 2 * t + im;
        z = x * x + y * y;
        c = k.toString(16);
        fillFractal(a, b, c)
    } while (k++ < maxiter && z < 4);
}

function showAppleWarning() {
    document.getElementById("apple-title").classList.remove("hidden");
}

function hideAppleWarning() {
    document.getElementById("apple-title").classList.add("hidden");
}