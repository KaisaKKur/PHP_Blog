
function clearBlogArticleList() {
    $(document).ready(function () {
        $("#list-life").empty();
        $("#list-study").empty();
        $("#list-other").empty();
    });
}

function showBlogArticleList(value) {
    var root = getRoot();
    $(document).ready(function () {
        var newListHTML = '\
            <a id=' + value['BlogArticleID'] + ' href="' + root + "Blog/Editor.php?BlogArticleID=" + value['BlogArticleID'] + '" class="list-group-item list-group-item-action">' + value['Title'] + '</a>\
        ';

        $("#list-" + value['List'].toLowerCase()).append(newListHTML);
    });
}

function showBlogArticleToForm(value) {
    console.debug(value);
    $(document).ready(function () {
        $("#title").val(value['Title']);
        $("#category").val(value['CategoryName']);
        $("#content").val(value['Content']);
        $("#checkbox-tags input:checkbox").attr('checked', false);
        value['Tags'].forEach(element => {
            $("#checkbox-tags input:checkbox[value='" + element + "']").attr('checked', true);
        });
        $("#new-update").val("update");

        $("#delete").attr("disabled", false);
    });
}

function showCommentsToForm(value) {
    $(document).ready(function () {
        var newCommentsHTML = '\
            <div class="bg-light border mt-3 mb-2 p-2">\
              <div class="mb-1">\
                <textarea class="form-control bg-white" rows="1" disabled>' + value["Content"] + '</textarea>\
              </div>\
              <div class="input-group">\
                <span class="input-group-text bg-light">Comment By</span>\
                <input type="text" class="form-control bg-white" value="' + value["UserEmail"] + '" disabled>\
                <span class="input-group-text bg-light text-muted"><p class="m-0">On:&nbsp</p>' + value['Date'] + '</span>\
                <button type="submit" name="delete-comment" value="' + value["ID"] + '" id="delete-comment" class="btn btn-danger col-2">Delete</button>\
              </div>\
            </div>\
        ';

        $("#comments").append(newCommentsHTML);
    });
}

function newEditorPage() {
    var root = getRoot();
    window.location.href = root + "Blog/Editor.php";
}

function reloadPage(value) {
    var root = getRoot();
    window.location.href = root + "Blog/Editor.php?BlogArticleID=" + value;
}

