
function showBlogArticle(value) {
    $(document).ready(function () {
        $("#blog-article-title").text(value["Title"]);
        $("#blog-article-content").text(value["Content"]);
        $("#blog-article-date").text(value["Date"]);
    });
}

function showComment(value) {
    $(document).ready(function () {
        $("#comments").attr("class", "");
        $("#comments").children("p").remove();

        var newHTML = '\
            <div class="mx-4 border rounded mt-2 mb-1 p-2">\
              <div class="mb-1">\
                <textarea name="comment" class="form-control bg-transparent" id="comment" rows="1" disabled>' + value['Content'] + '</textarea>\
              </div>\
              <div class="input-group">\
                <span class="input-group-text bg-light">Comment By</span>\
                <input type="text" name="email" class="form-control bg-transparent" aria-describedby="email" value="' + value['UserEmail'] + '" disabled>\
                <span class="input-group-text bg-light text-muted"><p class="m-0">On:&nbsp</p>' + value['Date'] + '</span>\
              </div>\
            </div>\
        ';

        $("#comments").append(newHTML);
    });
}

