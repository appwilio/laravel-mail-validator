/**
 * Created by m on 16.08.16.
 */
var uploadsList = function (uploadsListUrl) {
    var $list = $("#js-uploads-list");

    this.update = function (d) {

        if ("undefined" !== typeof(d)) {

            var content = "";
            for (var i = 0; i < d.length; i++) if (d.hasOwnProperty(i)) {
                var item = d[i];
                content += "" +
                    "<tr>" +
                    "<td><input type='checkbox' name='importFile[" + item.id + "]' checked='checked' value='" + item.id + "'/></td>" +
                    "<td>" + item.original_name + "</td>" +
                    "<td>" + item.created_at + "</td>" +
                    "<td>" + item.import_status + "</td>" +
                    "<td>" + item.validation_status + "</td>" +
                    "<td>" + item.emails_count + "</td>" +
                    "</tr>";
            }
            $list.html(content);
        } else {
            $list.html("");
        }
    };

    this.updateData = function () {
        var self = this;
        $.ajax(
            {
                url: uploadsListUrl,
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

var uploadList = new uploadsList(uploadsListUrl);



$(function () {
    uploadList.updateData();
    // setInterval(function (url) {
    //     uploadList.updateData(url);
    // }, 10000, uploadsListUrl);
});
