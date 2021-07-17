
function showAllBlogArticle(value) {
    $(document).ready(function () {
        var root = getRoot();
        var newHTML = '\
            <div class="card mb-4">\
              <div class="card-body">\
                <h5 class="card-title">' + value["Title"] + '</h5>\
                <h6 class="card-subtitle mb-2 text-muted">' + value["Date"] + '</h6>\
                <p class="card-text">' + value["Content"] + '</p>\
                <a href="' + root + "Blog/Blog.php?BlogArticleID=" + value["BlogArticleID"] + '" class="card-link">Follow</a>\
              </div>\
            </div>\
        ';

        $("#blog-articles").append(newHTML);
    });
}

