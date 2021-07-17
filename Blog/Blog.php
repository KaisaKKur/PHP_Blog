<?php
session_start();

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
require_once 'Blog.js';
\KKur\KJavaScript::JS__();

require_once 'Framework/Head.html';
require_once 'Framework/Header.html';
require_once 'Account/ShowAccount.php';

require_once 'KCSS.php';
\KKur\KCSS::__CSS();
require_once 'Framework/Blog.css';
\KKur\KCSS::CSS__();

require_once 'Framework/NavListByCategory.html';

echo "
    <script type='text/javascript'>
      linkNavListByCategory();
    </script>
";

require_once 'Module/Blog/Blog.html';

echo "
    <script type='text/javascript'>
      linkArchiveListByDate();
    </script>
";

require_once 'Module/Blog/Blog.php';

$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString, $queryArray);

$blogArticleID = $queryArray['BlogArticleID'];

if (\KKur\Blog::addPageView($blogArticleID)) {
}

$likeStatus = \KKur\Blog::addLikeCount($blogArticleID);
if (!empty($likeStatus)) {
    if (strcmp($likeStatus['status'], "unlike") == 0) {
        echo "
            <script type='text/javascript'>
              $('#like-button').val('unlike');
              $('#like').attr('class', 'bi bi-hand-thumbs-up-fill');
            </script>
        ";
    } elseif (strcmp($likeStatus['status'], "like") == 0) {
        echo "
            <script type='text/javascript'>
              $('#like-button').val('like');
              $('#like').attr('class', 'bi bi-hand-thumbs-up');
            </script>
        ";
    }
}

if (\KKur\Blog::newComment($blogArticleID)) {
}

loadBlogArticleByID($blogArticleID);
loadCommentsByBlogArticleID($blogArticleID);

require_once 'Framework/Footer.html';
require_once 'Framework/Foot.html';

echo "
    <script type='text/javascript'>
      initContactLink();
    </script>
";



/* Function
*/
function loadBlogArticleByID(int $blogArticleID) {
    $blogArticle = \KKur\Blog::getBlogArticleByID($blogArticleID);

    $blogArticleTitle = $blogArticle['Title'];
    $blogArticleContent = $blogArticle['Content'];
    $blogArticleDate = $blogArticle['Date'];
        
    $blogArticleArray = "Title: \"$blogArticleTitle\", Content: \"$blogArticleContent\", Date: \"$blogArticleDate\"";

    echo "
        <script type='text/javascript'>
          var val = {" . $blogArticleArray . "};
          showBlogArticle(val);
        </script>
    ";
}

function loadCommentsByBlogArticleID(int $blogArticleID) {
    $comments = \KKur\Blog::getComments($blogArticleID);

    if (count($comments) > 0) {
        foreach ($comments as $comment) {
            $commentUserEmail = $comment['UserEmail'];
            $commentContent = $comment['Content'];
            $commentDate = $comment['Date'];

            $commentArray = "UserEmail: \"$commentUserEmail\", Content: \"$commentContent\", Date: \"$commentDate\"";

            echo "
                <script type='text/javascript'>
                  var val = {" . $commentArray . "};
                  showComment(val);
                </script>
            ";
        }
    }
}

