/**
 * Created by m on 16.08.16.
 */
var ExcludeList = function () {
    var responseArrays = [
        {
            "key": "prefix",
            "list": $("#js-exclude-prefix-list"),
            "preTemplate": "",
            "postTemplate": "<small>@something.ru</small>"
        },
        {
            "key": "suffix",
            "list": $("#js-exclude-suffix-list"),
            "preTemplate": "<small>something@something</small>",
            "postTemplate": ""
        }
    ];

    this.update = function (d) {
        responseArrays.forEach(function (exclude) {
            var $list = exclude.list;
            if (undefined !== d[exclude.key]) {
                var data = d[exclude.key],
                    content = "";

                for (var i = 0; i < data.length; i++) if (data.hasOwnProperty(i)) {
                    var item = data[i];
                    content += "" +
                        "<tr>" +
                            "<td><input type='checkbox' name='exclude[" + item.id + "]' value='" + item.id + "' checked='checked'/></td>"+
                            "<td>" + exclude.preTemplate + '<strong>' + item.value + '</strong>' + exclude.postTemplate + "</td>" +
                            "<td>" +
                                "<a href='" + item.url + "'>delete</a>" +
                            "</td>" +
                        "</tr>";
                }
                $list.html(content);
            } else {
                $list.html("");
            }

        });
    };

    this.updateData = function (url) {
        var self = this;
        $.ajax(
            {
                url: url,
                dataType: "json",
                success: function (r) {
                    self.update(r);
                },
                error: function (e) {
                    console.error(e)
                }
            }
        );
    };

    this.sendExclude = function ($form, url) {
        var self = this,
            data = $form.serialize();

        return $.ajax({
            url: url,
            data: data,
            method: "post",
            dataType: "json",
            success: function (r) {
                $form.find("input[type=text]").each(function (i, e) {
                    $(e).val("");
                });
            },
            error: function (r) {
                console.error(r);
            }
        })
    }
};

var excludeList = new ExcludeList();

$(function () {
    excludeList.updateData(excludesListUrl);
    $("#js-exclude-prefix-form, #js-exclude-suffix-form").on("submit", function (e) {
        e.preventDefault();
        excludeList.sendExclude($(this), excludesCreateUrl).then(function () {
            window.location.reload();
        });
        return false;
    });
    // setInterval(function (url) {
    //     excludeList.updateData(url);
    // }, 10000, excludesListUrl);
});
