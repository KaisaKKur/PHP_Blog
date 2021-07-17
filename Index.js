
function getRoot() {
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var root = protocol + "//" + hostname + "/";
    return root;
}

function linkNavListByCategory() {
    $(document).ready(function(){
        var root = getRoot();
        $("#nav-list-life").attr("href", root + "Blog/ListByCategory.php?CategoryName=Life");
        $("#nav-list-study").attr("href", root + "Blog/ListByCategory.php?CategoryName=Study");
        $("#nav-list-other").attr("href", root + "Blog/ListByCategory.php?CategoryName=Other");
    });
}

function linkArchiveListByDate() {
    $(document).ready(function(){
        var newArchiveListByDateHTML = '\
            <h4 class="fst-italic">Archives</h4>\
            <ol class="list-unstyled mb-0">\
              <li><a id="date-July-2021" href="#">July 2021</a></li>\
              <li><a id="date-june-2021" href="#">June 2021</a></li>\
              <li><a id="date-may-2021" href="#">May 2021</a></li>\
              <li><a id="date-april-2021" href="#">April 2021</a></li>\
              <li><a id="date-march-2021" href="#">March 2021</a></li>\
              <li><a id="date-february-2021" href="#">February 2021</a></li>\
              <li><a id="date-january-2021" href="#">January 2021</a></li>\
            </ol>\
        ';
        $("#archives").append(newArchiveListByDateHTML);
        var root = getRoot();
        $("#date-July-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=July-2021");
        $("#date-june-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=June-2021");
        $("#date-may-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=May-2021");
        $("#date-april-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=April-2021");
        $("#date-march-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=March-2021");
        $("#date-february-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=February-2021");
        $("#date-january-2021").attr("href", root + "Blog/ArchiveListByDate.php?ArchiveDate=January-2021");
    });
}

function showTop(value) {
    $(document).ready(function(){
        $("#top-" + value['SerialNumber'] + "-title").text(value["Title"]);
        $("#top-" + value['SerialNumber'] + "-content").text(value["Content"]);
        $("#top-" + value['SerialNumber'] + "-date").text(value["Date"]);
        $("#top-" + value['SerialNumber'] + "-follow").attr("href", value["Follow"]);
    });
}

function showLatest(value) {
    $(document).ready(function(){
        $("#latest-" + value['SerialNumber'] + "-title").text(value["Title"]);
        $("#latest-" + value['SerialNumber'] + "-date").text(value["Date"]);
        $("#latest-" + value['SerialNumber'] + "-content").text(value["Content"]);
        $("#latest-" + value['SerialNumber'] + "-follow").attr("href", value["Follow"]);
    });
}

function initContactLink() {
    $(document).ready(function(){
        $("#link-github").attr("href", getRoot());
        $("#link-here").attr("href", getRoot());
    });
}

function goTo403() {
    var root = getRoot();
    window.location.href = root + "Error/403.html";
}

function goToIndex() {
    var root = getRoot();
    window.location.href = root;
}

