$(function () {
  load();
})

var page = 1;

function sendData(url, data, callback) {
  try {
    data["token"] = cookie.get("token");
    $.post(url, data, function (data, status) {
      callback(data, status);
    });
  } catch (error) {
    popups.dialog(error);
  }
}

function previous(mode = "access") {
  if (page > 1) {
    page = page - 1;
    fetchData(page, mode);
  } else {
    mdui.snackbar({
      message: "已经到顶了呢！",
      placement: "top",
      closeable: true
    })
  }
}

function next(mode = "access") {
  page = page + 1;
  fetchData(page, mode);
}

function fetchData(page, mode = "access", pageSize = 20) {
  if (mode == "access") {
    sendData("/v2/access", {
      page: page,
      pageSize: pageSize
    }, function (response) {
      displayData(response.data, page, mode);
    })
  }
}

function displayData(data, page, mode) {
  var list = `<mdui-segmented-button-group style="position: fixed;top: 80vh;z-index: 2301;right: 3vh;">
  <mdui-segmented-button onclick="previous('${mode}')">Previous</mdui-segmented-button>
  <mdui-segmented-button id="page">${page}</mdui-segmented-button>
  <mdui-segmented-button onclick="next('${mode}')">Next</mdui-segmented-button>
</mdui-segmented-button-group>
  <mdui-card class="mdui-table">
    <table>
      <thead>
        <tr>`;
  for (var key in data[0]) {
    list += `<th>${key}</th>`;
  }
  list += `</tr>
      </thead>
      <tbody>`;
  $.each(data, function (index, item) {
    list += `<tr>`;
    for (var key in item) {
      list += `<td>${item[key]}</td>`;
    }
    list += `</tr>`;
  });
  list += `
      </tbody>
    </table></mdui-card>`;
  $("#data").html(list);
}

function load() {
  var url = (window.location.pathname).replace("/miku/", "");
  if (url == "/miku") {
    url = "";
  }
  $(".nav").val(url)
  switch (url) {
    case "":
      $(".nav").val("dash")
      HotPlugin();
      TrendChart();
      break;
    case "log":
      fetchData(page, "access")
      break;
    case "api":
      $.get(
        url = "/v2/info",
        data = { "for": "status" },
        function (data, status) {
          if (status == "success") {
            var data = data.data;
            if (!empty(data)) {
              var list = `<div class="grid api">`;
              for (var key in data) {
                plu = data[key];
                var top_str = (plu.top == "true") ? "<mdui-badge id='top_'>置顶</mdui-badge>" : "<mdui-badge id='top_' style='display: none;'>置顶</mdui-badge>";
                list += `<mdui-card id="${plu.uuid}" style="margin-bottom: 0px;width: 95%;">
                <div class="post-title">${plu.name} <div class="post-meta-detail">v${plu.version}</div>${top_str}</div>
                <div class="post-meta">
                  <div class="post-meta-detail">${plu.class} @${plu.author}</div>
                  <div class="post-meta-devide">|</div>
                  <div class="post-meta-detail"><a href="javascript:top_('${plu.uuid}')">置顶/取消置顶</a></div>
                </div>
                <mdui-divider></mdui-divider>
                <table style="width:100%;">
                <tbody>
                  <tr>
                    <td>
                    <br>
                    ${plu.type}<br><div class="post-meta-detail">分类</div></td>
                    <td>
                      <mdui-switch checked="${plu.status}"></mdui-switch>
                    </td>
                  </tr>
                </tbody>
              </table>
              </mdui-card>`;
              };
              list += `</div>
              <script>
              $(".api mdui-card[id]").each(function () {
                $(this).on("change", function () {
                  var send = {
                    for: "status",
                    data: {
                      [$(this).attr("id")]: $(this).find("mdui-switch").prop("checked")
                    }
                  };
                  
                  sendData("/v2/edit", send, function (data) {
                    if (data.status == 200) {
                      popups.snaker.add("已保存");
                    } else {
                      popups.snaker.add(data.data);
                    }
                  });
                });
              });</script>`;
              $("#data").html(list);
            } else {
              $("#data").html(`<mdui-card>
              <div class="post-title">暂无API</div>
              <mdui-divider></mdui-divider>
              <a
                  href="javascript:sendData('/v2/update',{},function (responseData) {popups.dialog(responseData.data);});">更新API列表</mdui-button>
            </a>`);
            }
          }
        }
      );
      break;
    case "system":
      $.get(
        url = "/v2/system", { token: cookie.get("token") }, function (data) {
          var data = data.data;
          $("#data").html(`<div class="text-center grid">
          <mdui-card>
          <div class="chart1"></div>
          <p>内存(共${data.memory.max}MB)</p>
          </mdui-card>
          <mdui-card>
          <div class="post-meta-detail">系统信息</div>
          <br>
          <p>系统名称：${data.name}</p>
          <p>系统版本：${data.version}</p>
          <p>系统架构：${data.arch}</p>
          <p>完整信息：${data.all}</p>
          </mdui-card>
          <mdui-card>
          <div class="chart2"></div>
          <p>磁盘(共${data.disk.max}MB)</p>
          </mdui-card></div>`)
          const progressBar1 = new ProgressBar.Circle(".chart1", {
            color: "#3498db",
            strokeWidth: 10,
            trailWidth: 10,
            step: function (state, bar) {
              bar.setText(`${data.memory.percentage}%<br>已使用${data.memory.usage}MB`);
            }
          });
          progressBar1.animate(data.memory.percentage / 100);
          progressBar1.path.setAttribute("stroke", "#39C5BB");
          const progressBar2 = new ProgressBar.Circle(".chart2", {
            color: "#3498db",
            strokeWidth: 10,
            trailWidth: 10,
            step: function (state, bar) {
              bar.setText(`${data.disk.percentage}%<br>已使用${data.disk.usage}MB`);
            }
          });
          progressBar2.animate(data.disk.percentage / 100);
          progressBar2.path.setAttribute("stroke", "#39C5BB");
        });
      break;
    case "announce":
      $.get(
        url = "/v2/archives",
        data = {
          id: "all",
          type: "announce"
        }, function (data) {
          var list = `<table><thead>
          <tr><th>ID</th>
          <th>Title</th>
          <th>发布时间</th>
          <th>修改时间</th>
          <th>操作</th>
          </tr></thead><tbody>`;
          var data = data.data;
          for (var key in data) {
            var id = data[key].id;
            var title = data[key].title;
            var date = data[key].date;
            var modified = data[key].modified;
            list += `<tr>
            <td>${id}</td>
            <td>${title}</td>
            <td>${date}</td>
            <td>${modified}</td>
            <td>
            <a href="javascript:archives.modified(${id})">编辑</a>&nbsp;
            <a href="" onclick="archives.delete(${id}, this, event)">删除</a>&nbsp;
            </td></tr>`;

          }
          $("#data").html(`<mdui-card class="mdui-table">
          ${list}</tbody></table></mdui-card>
          <br>
          <mdui-card style="margin: 10px;">
            <header class="post-header">
              <font class="post-title" id="title" href="#">操作栏</font>
            </header>
            <div class="post-centent">
              <a href="javascript:archives.draw_new()">发布新</a>
            </div>
          </mdui-card>
          <mdui-card id="edit_area">
          </mdui-card>
          `)
          archives.draw_new();
        });
      break;
    case "option":
      $("body").append(`<mdui-fab icon="save" onclick="save()" style="z-index: 2301;position:fixed;bottom:15vh;right:5vw;"></mdui-fab>`);
      $.post(
        url = "/v2/info",
        data = {
          for: "options",
          token: cookie.get("token")
        },
        function (data) {
          var setting = `<mdui-card>`;
          var data = data.data;
          for (var key in data) {
            var value = data[key][0];
            if (value === "true") {
              setting += `
                <p>${data[key][1]}
                <mdui-switch id="${key}" checked></mdui-switch>
                </p>`;
            } else if (value === "false") {
              setting += `
                <p>${data[key][1]}
                <mdui-switch id="${key}"></mdui-switch>
                </p>`;
            } else if (key == "theme_color") {
              setting += `
              <p>${data[key][1]}
                <input type="color" id="${key}" value="${value}"></input>
              </p>`;
            } else if (key == "banner_description") {
              setting += `
              <p>${data[key][1]}
                <textarea id="${key}"></textarea>
              </p>`;
              var content = value;
            } else {
              setting += `
                <p>${data[key][1]}
                <mdui-text-field autosize id="${key}" value="${value}"></mdui-text-field>
                </p>`;
            }
          }
          setting += `</mdui-card>`;
          $("#data").html(setting);
          tinymce.init({
            selector: "#banner_description",
            language_url: "/assets/js/zh-Hans.js",
            language: "zh-Hans",
            plugins: "preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help emoticons autosave autoresize",
            toolbar: "undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | \
          styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
          table image media charmap emoticons pagebreak insertdatetime preview | fullscreen | lineheight",
            toolbar_sticky: true,
            autoresize_max_height: 500,
            autoresize_min_height: 350,
            autoresize_on_init: false,
            autosave_ask_before_unload: false,
            setup: function (editor) {
              editor.on('init', function (e) {
                tinymce.activeEditor.setContent(content);
              })
            }
          })
        }
      )
      break;
    default:
      break;
  }
}

function save() {
  i = 0;
  var url = (window.location.pathname).replace("/miku/", "");
  if (url == "/miku") {
    url = "";
  }
  switch (url) {
    case "option":
      var data = settingObj = {};
      $("#data [id]").each(function () {
        var input = $(this);
        data[input.attr("id")] = input.val();
      });
      data["banner_description"] = tinyMCE.activeEditor.getContent();
      $("#data mdui-switch").each(function () {
        var checkbox = $(this);
        data[checkbox.attr("id")] = checkbox.prop("checked");
      });
      var send = {
        for: "option",
        data: data
      };
      i = 1;
      break;
    default:
      popups.dialog("无需操作");
  }
  if (i == 1) {
    sendData("/v2/edit", send, function (data) {
      if (data.status == 200) {
        popups.dialog(data.data);
      } else {
        popups.dialog(data.data);
      }
    });
  }
}

function top_(uuid) {
  var badge = $("#" + uuid).find("mdui-badge[id='top_']");
  var top = badge.css("display") == "none";
  sendData("/v2/top", {
    data: {
      [uuid]: top
    }
  }, function (data) {
    if (data.status == 200) {
      if (top) {
        badge.show()
      } else {
        badge.hide()
      }
    }
    popups.snaker.add(data.data);
  })
}

function check_update() {
  $("#latest_version").html("正在获取");
  $.ajax({
    url: "https://api.github.com/repos/molanp/mikuapi/releases/latest",
    method: "GET",
    success: function (data, status) {
      if (status === "success") {
        $("#latest_version").html(`<a href="${data.html_url}" target="_blank">${data.name}(点击查看)</a>`);
        $("#update_info").val(data.body);
      }
    },
    error: function (xhr) {
      $("#latest_version").html((xhr.responseJSON ? xhr.responseJSON.message : xhr.status) + `(${xhr.status})`);
    }
  });
}

function resetpassword() {
  var content = `
    <mdui-text-field clearable label="新的密码" id="new"></mdui-text-field>
    <mdui-text-field clearable label="再输一次" id="again"></mdui-text-field>`;

  mdui.dialog({
    headline: "修改密码",
    body: content,
    actions: [{
      text: "取消"
    },
    {
      text: "确定",
      onClick: function () {
        var newPassword = $("#new").val();
        var newPasswordAgain = $("#again").val();
        if (newPassword === "" || newPasswordAgain === "") {
          popups.dialog("密码不能为空");
          return;
        }

        sendData("/v2/auth/login", {
          "type": "pass",
          "token": cookie.get("token"),
          "new": newPassword,
          "again": newPasswordAgain
        }, function (data) {
          mdui.dialog({
            body: data.data,
            actions: [{
              text: "OK",
              onClick: function () {
                loginout();
              }
            }]
          });
        });
      }
    }]
  });
}

function loginout() {
  sendData("/v2/auth/logout",
    { "token": cookie.get("token") },
    function () { })
  cookie.remove("user");
  cookie.remove("token");
  window.location.href = "/miku";
}

var HotChart;

function HotPlugin(unit = "year") {
  $.post(url = "/v2/hot", { unit: unit, token: cookie.get("token") })
    .done(function (data) {
      var names = [];
      var values = [];
      for (var item in data.data) {
        names.push(data.data[item]["name"]);
        values.push(data.data[item]["count"]);
      }

      if (HotChart) {
        HotChart.destroy();
      }

      var canvas = document.getElementById("hot-trend-chart");
      var ctx = canvas.getContext("2d");

      HotChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: names,
          datasets: [{
            label: "热门API",
            data: values,
            backgroundColor: "rgb(57, 197, 187, 0.2)",
            borderColor: "rgb(57, 197, 187)",
            borderWidth: 1
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
}

function TrendChart() {
  $.get(url = "/v2/hot", data = { token: cookie.get("token") })
    .done(function (data) {
      var dates = [];
      var values = [];
      for (var item in data.data) {
        dates.push(data.data[item]["date"]);
        values.push(data.data[item]["count"]);
      }
      var ctx = document.getElementById("api-trend-chart").getContext("2d");
      new Chart(ctx, {
        type: "line",
        data: {
          labels: dates,
          datasets: [{
            label: "API调用次数",
            data: values,
            borderColor: "rgba(75, 192, 192, 1)",
            backgroundColor: "rgba(0, 0, 0, 0)"
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false
            }
          },
        }
      });
    })
}

const archives = {
  draw_new: function () {
    var id = "miku_" + Math.random().toString(36).substr(2, 8);
    $("#edit_area").html(`<header class="post-header">
    <font class="post-title" id="title" href="#">发布新公告</font>
  </header>
  <div class="post-content">
    <mdui-text-field autosize id="post-title" label="公告标题" value="${date} 公告" placeholder="请输入公告标题"></mdui-text-field>
    公告内容
    <textarea id="${id}"></textarea>
  </div>
  <mdui-button href="javascript:archives.post()">发布</mdui-button>`);
    tinymce.init({
      selector: "#" + id,
      language_url: "/assets/js/zh-Hans.js",
      language: "zh-Hans",
      plugins: "preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help emoticons autosave autoresize",
      toolbar: "undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | \
    styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
    table image media charmap emoticons pagebreak insertdatetime preview | fullscreen | lineheight",
      toolbar_sticky: true,
      autoresize_max_height: 500,
      autoresize_min_height: 350,
      autoresize_on_init: false,
      autosave_ask_before_unload: false,
    });
  },
  post: function () {
    $.ajax({
      url: "/v2/archives",
      data: {
        title: $("#post-title").val(),
        content: tinyMCE.activeEditor.getContent(),
        token: cookie.get("token")
      },
      type: "POST",
      success: function (response) {
        popups.snaker.add(response.data);
      }
    })
  },
  mod_post: function (id) {
    $.ajax({
      url: "/v2/archives",
      data: {
        id: id,
        title: $("#post-title").val(),
        content: tinyMCE.activeEditor.getContent(),
        token: cookie.get("token")
      },
      type: "POST",
      success: function (response) {
        popups.snaker.add(response.data);
      }
    })
  },
  modified: function (id) {
    $.get(
      url = "/v2/archives",
      data = {
        id: id,
        type: "announce"
      }, function (data) {
        var id = "miku_" + Math.random().toString(36).substr(2, 8);
        $("#edit_area").html(`<header class="post-header">
        <font class="post-title" id="title" href="#">编辑公告</font>
      </header>
      <div class="post-content">
        <mdui-text-field autosize id="post-title" label="公告标题" value="${data.data.title}" placeholder="请输入公告标题"></mdui-text-field>
        公告内容
        <textarea id="${id}"></textarea>
      </div>
      <mdui-button href="javascript:archives.mod_post(${data.data.id})">提交修改</mdui-button>`);
        tinymce.init({
          selector: "#" + id,
          language_url: "/assets/js/zh-Hans.js",
          language: "zh-Hans",
          plugins: "preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help emoticons autosave autoresize",
          toolbar: "undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | \
        styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
        table image media charmap emoticons pagebreak insertdatetime preview | fullscreen | lineheight",
          toolbar_sticky: true,
          autoresize_max_height: 500,
          autoresize_min_height: 350,
          autoresize_on_init: false,
          autosave_ask_before_unload: false,
          setup: function (editor) {
            editor.on('init', function (e) {
              tinymce.activeEditor.setContent(data.data.content);
            })
          }
        })
      })
  },
  delete: function (id, row) {
    event.preventDefault();
    $.ajax({
      url: "/v2/archives",
      data: {
        id: id,
        token: cookie.get("token")
      },
      type: "DELETE",
      success: function (response) {
        row.parentNode.parentNode.parentNode.removeChild(row.parentNode.parentNode);
        popups.snaker.add(response.data);
      }
    })
  }
}