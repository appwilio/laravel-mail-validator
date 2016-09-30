/**
 * Created by m on 16.08.16.
 */
var uploadsList = function () {
    var $list = $("#js-uploads-list");

    this.update = function (d) {

        if ("undefined" !== typeof(d)) {

            var content = "";
            for (var i = 0; i < d.length; i++) if (d.hasOwnProperty(i)) {
                var item = d[i];
                content += "" +
                    "<tr>" +
                    "<td>" + i + "</td>" +
                    "<td>" + item.original_name + "</td>" +
                    "<td>" + item.created_at + "</td>" +
                    "<td>" + item.finished + "</td>" +
                    "<td>" + item.updated_at + "</td>" +
                    "</tr>";
            }
            $list.html(content);
        } else {
            $list.html("");
        }
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
    }
};

var uploadList = new uploadsList();



$(function () {
    uploadList.updateData(uploadsListUrl);
    setInterval(function (url) {
        uploadList.updateData(url);
    }, 10000, uploadsListUrl);
});
