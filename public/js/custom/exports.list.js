/**
 * Created by m on 16.08.16.
 */
var ExportsList = function (exportsListUrl, isPendingUrl) {
    var $list = $("#js-exports-list"),
        $exportWarning = $("#js-export-warning"),
        $exportButton = $("#js-export-button");

    this.update = function (d) {

        if (undefined !== d) {
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

    this.updateData = function () {
        var self = this;
        $.ajax(
            {
                url: exportsListUrl,
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

    this.export = function (url) {
        var self = this;

        data = {
            "importFile": [],
            "exclude": {
                "prefix": [],
                "suffix": []
            }
        };

        $("#js-uploads-list").find("input:checked").each(function(i, e){ data.importFile.push(e.value)});
        $("#js-exclude-prefix-list").find("input:checked").each(function(i, e){ data.exclude.prefix.push(e.value)});
        $("#js-exclude-suffix-list").find("input:checked").each(function(i, e){ data.exclude.suffix.push(e.value)});

        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (r) {
                console.log(r);
            },
            error: function (r) {
                console.error(r)
            },
            complete: function (r) {
                self.updateData();
            }
        })
    };

    this.checkPendingValidations = function () {
        var self = this;
        $.ajax({
            url: isPendingUrl,
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

var exportsList = new ExportsList(exportsListUrl, isPendingUrl);

$(function () {
    exportsList.updateData();
    // exportsList.checkPendingValidations(isPendingUrl);
    $("#js-export-button").on("click", function () {
        exportsList.export($(this).data("ajax-url"));
    });
    setInterval(function () {
        exportsList.updateData();
    }, 10000);
});
