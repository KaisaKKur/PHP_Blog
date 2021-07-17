<?php
session_start();

require_once 'KJavaScript.php';
\KKur\KJavaScript::__JS();
require_once 'Index.js';
\KKur\KJavaScript::JS__();

require_once 'Framework/Head.html';
require_once 'Framework/Header.html';

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

require_once 'Framework/Index.html';

echo "
    <script type='text/javascript'>
      linkArchiveListByDate();
    </script>
";

require_once 'Account/ShowAccount.php';
require_once 'Module/Blog/Blog.php';

showTop(2);
showLatest(5);

echo "
    <script type='text/javascript'>
      initContactLink();
    </script>
";

require_once 'Framework/Footer.html';
require_once 'Framework/Foot.html';



/* Function
*/
function showTop(int $amount) {
    for ($i = 1; $i <= $amount; $i++) {
        $top = \KKur\Blog::getTop($i);

        if ($top) {
            $topID = $top['ID'];
            $topFollow = "Blog/Blog.php?BlogArticleID=$topID";
            $topTitle = $top['Title'];
            $topContent = $top['Content'];
            $topDate = $top['Date'];

            $topArray = "SerialNumber: $i, Follow: \"$topFollow\", Title: \"$topTitle\", Content: \"$topContent\", Date: \"$topDate\"";

            echo "
                <script type='text/javascript'>
                  var val = {" . $topArray . "};
                  showTop(val);
                </script>
            ";
        }
    }
}

function showLatest(int $amount) {
    for ($i = 1; $i <= $amount; $i++) {
        $latest = \KKur\Blog::getLatest($i);

        if ($latest) {
            $latestID = $latest['ID'];
            $latestFollow = "Blog/Blog.php?BlogArticleID=$latestID";
            $latestTitle = $latest['Title'];
            $latestContent = $latest['Content'];
            $latestDate = $latest['Date'];

            $latestArray = "SerialNumber: $i, Follow: \"$latestFollow\", Title: \"$latestTitle\", Content: \"$latestContent\", Date: \"$latestDate\"";

            echo "
                <script type='text/javascript'>
                  var val = {" . $latestArray . "};
                  showLatest(val);
                </script>
            ";
        }
    }
}

