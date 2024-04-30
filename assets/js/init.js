var scripts = [
    "/assets/js/cookie.js",
    "/assets/js/marked.min.js",
    "/assets/js/theme.js",
    "/assets/js/fancybox.umd.js",
    "/assets/js/highlight.min.js"
];
var csses = [
    "/assets/css/mark.css",
    "https://unpkg.com/mdui@2/mdui.css",
    "https://registry.npmmirror.com/@fancyapps/ui/5.0.33/files/dist/fancybox/fancybox.css",
    "https://registry.npmmirror.com/highlight.js/11.9.0/files/styles/default.min.css",
    "/assets/css/style.css",
    "/assets/css/theme.php",
];
for (var i = 0; i < scripts.length; i++) {
    $.getScript(scripts[i]);
}
for (var i = 0; i < csses.length; i++) {
    $("head").append('<link rel="stylesheet" href="' + csses[i] + '">');
}
window.onload = function () {
    adjustSidebar();
    $('body').append('<mdui-fab id="ToTop" icon="vertical_align_top"></mdui-fab>');
    $(window).scroll(function () {
        if ($(this).scrollTop() > $("mdui-top-app-bar").height()) {
            $('#ToTop').fadeIn();
            $("mdui-top-app-bar").attr("scrolling", "").css("opacity", 0.85);
        } else {
            $('#ToTop').fadeOut();
            $("mdui-top-app-bar").removeAttr("scrolling").css("opacity", 1);
        }
    });
    $('#ToTop').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });
}

window.addEventListener("resize", function () {
    adjustSidebar();
});

function empty(e) {
    if (Array.isArray(e)) {
        return e.length === 0;
    }
    switch (e) {
      case "":
      case 0:
      case "0":
      case null:
      case false:
      case undefined:
        return true;
      default:
        return false;
    }
  }

function adjustSidebar() {
    if ($(window).width() > 1090) {
        sider(2);
    } else if ($(window).width() > 900) {
        sider(1);
    } else {
        sider(0);
    }
}

function sider(side = 0) {
    hljs.highlightAll();
    switch (side) {
        case 0:
            $(".archives, .overview").appendTo($(".drawer")).show();
            $(".menu").show();
            $(".left, .right").css({
                flexBasis: "0%"
            }).hide();
            $(".center").css({
                flexBasis: "100%"
            });
            break;
        case 1:
            $(".archives").appendTo($(".left")).show();
            $(".overview").appendTo($(".left")).show();
            $(".menu").hide();
            $(".left").css({
                flexBasis: "25%"
            }).show();
            $(".right").css({
                flexBasis: "0%"
            }).hide();
            $(".center").css({
                flexBasis: "75%"
            });
            break;
        case 2:
            $(".archives").appendTo($(".left")).show();
            $(".overview").appendTo($(".right")).show();
            $(".menu").hide();
            $(".left, .right").css({
                flexBasis: "25%"
            }).show();
            $(".center").css({
                flexBasis: "50%"
            });
            break;
    }
}

function truncateText(text, maxLength) {
    if (text.length > maxLength) {
        return text.substring(0, maxLength) + "...";
    } else {
        return text;
    }
}

function search() {
    mdui.prompt({
        headline: "搜索API",
        confirmText: "确定",
        cancelText: "Cancel",
        onConfirm: (value) => window.location.href = "/?s=" + value,
    });
}