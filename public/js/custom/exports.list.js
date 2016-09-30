/**
 * Created by m on 16.08.16.
 */
var ExportsList = function () {
    var $list = $("#js-exports-list"),
        $exportWarning = $("#js-export-warning"),
        $exportButton = $("#js-export-button");

    this.update = function (d) {

        if ("undefined" !== typeof(d)) {

            var content = "";
            for (var i = 0; i < d.length; i++) if (d.hasOwnProperty(i)) {
                var item = d[i];
                content += "" +
                    "<tr>" +
                    "<td>" + i + "</td>" +
                    "<td>" + item.name + "</td>" +
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
    };

    this.enableExport = function () {
        $exportButton.show();
        $exportWarning.hide();
    };

    this.disableExport = function () {
        $exportButton.hide();
        $exportWarning.show();
    };

    this.checkPendingValidations = function (url) {
        var self = this;
        $.ajax({
            url: url,
            dataType: "json",
            success: function (r) {
                if (false === r) {
                    self.enableExport();
                } else {
                    self.disableExport();
                }
            },
            error: function () {
                self.disableExport();
            }
        });
    }
};

var exportsList = new ExportsList();

$(function () {
    exportsList.updateData(exportsListUrl);
    exportsList.checkPendingValidations(isPendingUrl);
    setInterval(function (url) {
        exportsList.updateData(url);
        exportsList.checkPendingValidations(isPendingUrl);
    }, 10000, exportsListUrl);
});
