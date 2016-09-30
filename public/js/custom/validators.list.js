/**
 * Created by m on 16.08.16.
 */
var ValidationsList = function () {
    var $list = $("#js-validators-list");

    this.update = function (d) {

        if ("undefined" !== typeof(d)) {

            var content = "";
            for (var i = 0; i < d.length; i++) if (d.hasOwnProperty(i)) {
                var item = d[i];
                content += "" +
                    "<tr>" +
                    "<td>" + i + "</td>" +
                    "<td>" + item.key + "</td>" +
                    "<td>" + item.valid + "</td>" +
                    "<td>" + item.invalid + "</td>" +
                    "<td>" + item.pending + "</td>" +
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

var validationsList = new ValidationsList();

$(function () {
    validationsList.updateData(validatorsListUrl);
    setInterval(function (url) {
        validationsList.updateData(url);
    }, 10000, validatorsListUrl);
});
