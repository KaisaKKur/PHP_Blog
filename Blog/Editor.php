<?php
session_start();

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
require_once 'Editor.js';
\KKur\KJavaScript::JS__();

if (!isset($_SESSION['UserID'])) {
    echo "
        <script type='text/javascript'>
          goTo403();
        </script>
    ";
}

require_once 'Framework/Head.html';
require_once 'Framework/Header.html';
require_once 'Account/ShowAccount.php';

require_once 'KCSS.php';
\KKur\KCSS::__CSS();
require_once 'Framework/Blog.css';
\KKur\KCSS::CSS__();

require_once 'Module/Blog/Editor.html';
require_once 'Module/Blog/Blog.php';

// Get BlogArticleID by URL Params
$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString, $queryArray);
$blogArticleID = 0;

if (!empty($queryArray)) {
    $blogArticleID = $queryArray['BlogArticleID'];
}

if ($blogArticleID > 0) {
    $userID = \KKur\Blog::getUserIDByBlogArticleID($blogArticleID);
    if ($userID != $_SESSION['UserID']) {
        echo "
            <script type='text/javascript'>
            goTo403();
            </script>
        ";
    }
}

// New or Modify a Blog Article
$editor = \KKur\Blog::editBlogArticle($blogArticleID);

if (!empty($editor)) {
    if (strcmp($editor['Name'], "new") == 0) {
        if ($editor['Status']) {
            $newBlogArticleID = $editor['BlogArticleID'];
            echo "
                <script type='text/javascript'>
                  reloadPage('$newBlogArticleID');
                </script>
            ";
        }
    } elseif (strcmp($editor['Name'], "update") == 0) {
        if ($editor['Status']) {
            loadBlogArticleToList();
            // Load Blog Article to Form
            loadBlogArticleToForm($blogArticleID);
        }
    }
}

// New a Editor Page
if (\KKur\Blog::isNewEditorPage()) {
    echo "
        <script type='text/javascript'>
          newEditorPage();
        </script>
    ";
}

// Delete a Blog Article and its Tags
if (\KKur\Blog::deleteBlogArticleByID($blogArticleID)) {
    echo "
          <script type='text/javascript'>
            newEditorPage();
          </script>
    ";
}

// Delete the Comment of Blog Article by ID
if (\KKur\Blog::deleteCommentByID()) {
}

// Init Blog Article List
loadBlogArticleToList();

// Load Blog Article to Form
loadBlogArticleToForm($blogArticleID);

require_once 'Framework/Footer.html';
require_once 'Framework/Foot.html';



/* Function
*/

// Modify: Load to Form
function loadBlogArticleToForm(int $blogArticleID) {
    if ($blogArticleID > 0) {
        $blogArticle = \KKur\Blog::getBlogArticleByID($blogArticleID);

        $blogArticleTitle = $blogArticle['Title'];
        $blogArticleCategory = $blogArticle['CategoryName'];
        $blogArticleContent = $blogArticle['Content'];
        $blogArticleTags = $blogArticle['Tags'];

        $blogArticleTagToString = "[ ";
        for ($i = 0; $i < count($blogArticleTags); $i++) { 
            $blogArticleTagToString .= $blogArticleTags[$i] . ",";
        }
        $blogArticleTagToString = substr($blogArticleTagToString, 0, strlen($blogArticleTagToString) - 1);
        $blogArticleTagToString .= " ]";

        $blogArticleArray = "Title: \"$blogArticleTitle\", CategoryName: \"$blogArticleCategory\", Content: \"$blogArticleContent\", Tags: $blogArticleTagToString";
        
        echo "
            <script type='text/javascript'>
              var val = {" . $blogArticleArray . "};
              showBlogArticleToForm(val);
            </script>
        ";

        loadCommentToForm($blogArticleID);
    }
}

// if not null, Load Comment to Form
function loadCommentToForm(int $blogArticleID) {
    echo "
        <script type='text/javascript'>
          $('#comment-collapse').attr('class', 'col-12 mt-3');
        </script>
    ";
    // Show All Comments
    $comments = \KKur\Blog::getAllComments($blogArticleID);

    if (count($comments) > 0) {
        echo "
            <script type='text/javascript'>
              $('#comments').empty();
              $('#comments').attr('style', 'height: 600px; overflow-y:scroll');
            </script>
        ";

        foreach ($comments as $comment) {
            $commentID = $comment['ID'];
            $commentUserEmail = $comment['UserEmail'];
            $commentContent = $comment['Content'];
            $commentDate = $comment['Date'];
    
            $commentArray = "ID:\"$commentID\", UserEmail: \"$commentUserEmail\", Content: \"$commentContent\", Date: \"$commentDate\"";

            echo "
                <script type='text/javascript'>
                  var val = {" . $commentArray . "};
                  showCommentsToForm(val);
                </script>
            ";
        }
    }
}

// List
function loadBlogArticleToList() {
    $blogArticles = \KKur\Blog::getAllByLatest();

    if (count($blogArticles) > 0) {
        echo "
            <script type='text/javascript'>
              clearBlogArticleList();
            </script>
        ";

        foreach ($blogArticles as $blogArticle) {
            $blogArticleID = $blogArticle['ID'];
            $blogArticleTitle = $blogArticle['Title'];
            $categoryName = $blogArticle['CategoryName'];

            $blogArticleArray = "BlogArticleID: \"$blogArticleID\", List:\"$categoryName\", Title: \"$blogArticleTitle\"";

            echo "
                <script type='text/javascript'>
                  var val = {" . $blogArticleArray . "};
                  showBlogArticleList(val);
                </script>
            ";
        }
    }
}

