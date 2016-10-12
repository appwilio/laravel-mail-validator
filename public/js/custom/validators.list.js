/**
 * Created by m on 16.08.16.
 */
var ValidationsList = function (validatorsListUrl) {
    var $list = $("#js-validators-list");

    this.update = function (d) {

        if ("undefined" !== typeof(d)) {

            var content = "";
            for (var i = 0; i < d.length; i++) if (d.hasOwnProperty(i)) {
                var item = d[i];
                content += "" +
                    "<tr>" +
                    "<td>" + i + "</td>" +
                    "<td>" + item.type + "</td>" +
                    "<td>" + item.key + " </td>" +
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
    this.updateData = function () {
        var self = this;
        $.ajax(
            {
                url: validatorsListUrl,
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

var validationsList = new ValidationsList(validatorsListUrl);

$(function () {
    validationsList.updateData();
    setInterval(function () {
        validationsList.updateData();
    }, 10000);
});
