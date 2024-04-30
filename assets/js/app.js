$(function () {
    window.page = 1;
    $.get("/v2/sitemap");
    $.get(
        url = "/v2/info",
    )
        .done(function (data) {
            $("#app_api").html("");
            api(data.data);
        })
})

function api(data) {
    item = "";
    for (var name in data) {
        var top_str = (data[name].top == "true") ? `
        <div class="post-meta-devide">|</div>
        <div class="post-meta-detail">
            <mdui-icon name="push_pin" style="font-size: 14.5px;"></mdui-icon>置顶
        </div>` : "";
        item += `
            <mdui-card class="text-center" href="docs${data[name].path}">
                <header class="post-header text-center">
                    <a class="post-title" href="docs${data[name].path}">${name}</a>
                    <div class="post-meta">
                        <div class="post-meta-detail">
                            <mdui-icon name="account_circle" style="font-size: 14.5px;"></mdui-icon>${data[name].author}
                        </div>
                        <div class="post-meta-devide">|</div>
                        <div class="post-meta-detail">
                            <mdui-icon name="access_time" style="font-size: 14.5px;"></mdui-icon>${data[name].time}
                        </div>
                        <div class="post-meta-devide">|</div>
                        <div class="post-meta-detail">
                            <mdui-icon name="equalizer" style="font-size: 14.5px;"></mdui-icon>${data[name].count}次
                        <div class="post-meta-devide">|</div>
                        <div class="post-meta-detail post-meta-detail-words">
                            <mdui-icon name="bookmark_border" style="font-size: 14.5px;"></mdui-icon>${data[name].type}
                        </div>
                        ${top_str}
                    </div>
                </header>
                <div class="post-content">
                    ${truncateText(marked.parse(data[name].api_profile), 101)}
                </div>
            </mdui-card>`
    }
    $("#app_api").append(item);
}



window.addEventListener('scroll',
    mdui.throttle(() => {
        console.log('update');
        var windowHeight = $(window).height();
        var documentHeight = $(document).height();
        var scrollTop = $(window).scrollTop();
        var scrollBottom = documentHeight - (windowHeight + scrollTop);
        if (scrollBottom <= 1 && window.page >= 0) {
            $('html, body').animate({ scrollTop: scrollTop });
            window.page += 1
            $.get(
                url = "/v2/info",
                data = {
                    page: window.page
                }
            )
                .done(function (data) {
                    if (data.data.length != 0) {
                        api(data.data);
                    } else {
                        mdui.snackbar({
                            message: "已经到顶了哦！",
                            placement: "top",
                            closeable: true
                        })
                        window.page = -1;
                    }
                })
        }
    }, 500)
)
