$(function () {
    $.get("/v2/sitemap");
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

function lazyload(x) {
    id = parseInt(x.id ?? 0);
    x.id = id + 1;
    $.get(
        url = "/v2/info",
        data = {
            page: id
        }
    )
        .done(function (data) {
            if (data.data.length != 0) {
                api(data.data);
            } else {
                x.style.display = "none";
                mdui.snackbar({
                    message: "已经到顶了哦！",
                    placement: "top",
                    closeable: true
                })
            }
        })
}