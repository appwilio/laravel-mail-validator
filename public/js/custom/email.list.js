/**
 * Created by m on 16.08.16.
 */
var Template = function () {
    var $total = $("#js-total"),
        $list = $("#js-emails-list"),
        $nextPage = $("#js-next"),
        $prevPage = $("#js-prev");

    this.update = function (data) {

        if("undefined" !== typeof(data.total)) {
            $total.text(data.total);
        } else {
            $total.text("Unknown")
        }

        if("undefined" != typeof(data.next_page_url) && null != data.next_page_url ) {
            $nextPage.attr("href", data.next_page_url);
        } else {
            $nextPage.attr("href", "#");
        }

        if("undefined" != typeof(data.prev_page_url) && null != data.prev_page_url ) {
            $prevPage.attr("href", data.prev_page_url);
        } else {
            $prevPage.attr("href", "#");
        }

        if("undefined" !== typeof(data.data)) {

            var content = "";
            for(var i=0; i<data.data.length; i++) {
                var item = data.data[i];
                content+="" +
                    "<tr>" +
                        "<td>"+item.id+"</td>"+
                        "<td>"+item.address+"</td>"+
                    "</tr>"
            }
            console.log(content);
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
    updateData(paginate);
    $(document).on("click", "#js-next, #js-prev", function (e) {
        e.preventDefault();
        var href = e.target.getAttribute("href");
        console.log(href);
        if("#" != href) {
            updateData(href);
        }
        return false;
    })
});
