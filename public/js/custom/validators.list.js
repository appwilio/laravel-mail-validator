/**
 * Created by m on 16.08.16.
 */
var Template = function () {
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
    }
};

var template = new Template();

function updateData(url) {
    $.ajax(
        {
            url: url,
            success: function (r) {
                template.update(r);
            },
            error: function (e) {
                console.error(e)
            }
        }
    );
}

$(function () {
    updateData(validatorsListUrl);
    setInterval(function (url) {
        updateData(url);
    }, 10000, validatorsListUrl);
});
