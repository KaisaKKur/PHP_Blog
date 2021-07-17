<?php
session_start();

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
require_once 'ListByCategory.js';
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

require_once 'Module/Blog/ListByCategory.html';

echo "
    <script type='text/javascript'>
      linkArchiveListByDate();
    </script>
";

require_once 'Module/Blog/Blog.php';

// Get CategoryName by URL Params
$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString, $queryArray);
$categoryName = null;

if (!empty($queryArray)) {
    $categoryName = $queryArray['CategoryName'];
}

showAllBlogArticleByCategory($categoryName);

require_once 'Framework/Footer.html';
require_once 'Framework/Foot.html';

echo "
    <script type='text/javascript'>
      initContactLink();
    </script>
";



/* Function
*/
function showAllBlogArticleByCategory(string $categoryName) {
    $blogArticles = \KKur\Blog::getBlogArticleByCategoryName($categoryName);

    if (!empty($blogArticles)) {
        echo "
              <script type='text/javascript'>
                  $('#blog-article-null').remove();
              </script>
          ";

        foreach ($blogArticles as $blogArticle) {
            $blogArticleID = $blogArticle['ID'];
            $blogArticleTitle = $blogArticle['Title'];
            $blogArticleContent = $blogArticle['Content'];
            $blogArticleDate = $blogArticle['Date'];

            $blogArticleArray = "BlogArticleID: \"$blogArticleID\", Title: \"$blogArticleTitle\", Content: \"$blogArticleContent\", Date: \"$blogArticleDate\"";
        
            echo "
                <script type='text/javascript'>
                  var val = {" . $blogArticleArray . "};
                  showAllBlogArticle(val);
                </script>
            ";
        }
    }
}

