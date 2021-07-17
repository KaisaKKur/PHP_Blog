<?php
namespace KKur;

require_once 'Module/MySQLi/MySQLi.php';
require_once 'Module/MySQLi/MySQLi_STMT.php';

class Blog {

    public static function editBlogArticle(int $blogArticleID) : array {
        $editor = [];
        try {
            if (isset($_POST['save'])) {
                if (strcmp($_POST['save'], "new") == 0) {
                    $editor = [
                        "Name" => "new",
                        "Status" => false,
                        "BlogArticleID" => 0
                    ];

                    $title = $_POST['title'];
                    $category = $_POST['category'];
                    $content = $_POST['content'];
                    $tags = isset($_POST['tags']) ? $_POST['tags'] : NULL;

                    if (!empty($title)) {
                        if (!empty($content)) {
                            $getCategoryIDQuery = "SELECT `ID` FROM `Categories` WHERE `Name` = ?";
                            $MySQLi = new \KKur\MySQLi();
                            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getCategoryIDQuery);
                            $MySQLi_STMT->_mysqli_stmt->bind_param("s", $category);
                            $MySQLi_STMT->_mysqli_stmt->execute();
                            $MySQLi_STMT->_mysqli_stmt->bind_result($CategoryID);

                            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                                $query = "INSERT INTO `Blog_Articles`(`UserID`, `CategoryID`, `Title`, `Content`) VALUES(?, ?, ?, ?)";
                                $MySQLi_STMT->_mysqli_stmt->prepare($query);
                                $MySQLi_STMT->_mysqli_stmt->bind_param("ssss", $_SESSION['UserID'], $CategoryID, $title, $content);
                                $MySQLi_STMT->_mysqli_stmt->execute();                         

                                $newBlogArticleID = $MySQLi->_get_mysqli()->insert_id;

                                if (!empty($tags)) {
                                    foreach ($tags as $tag) {
                                        $insertTagQuery = "INSERT INTO `Blog_Article_Tag`(`BlogArticleID`, `TagID`) VALUES($newBlogArticleID, $tag)";
                                        $MySQLi_STMT->_mysqli_stmt->prepare($insertTagQuery);
                                        $MySQLi_STMT->_mysqli_stmt->execute();
                                    }
                                }

                                $editor['BlogArticleID'] = $newBlogArticleID;
                                $editor['Status'] = true;
                            }
                        } else {
                            throw new \Exception("InvalidContent");
                        }
                    } else {
                        throw new \Exception("InvalidTitle");
                    }
                } elseif (strcmp($_POST['save'], "update") == 0) {
                    $editor = [
                        "Name" => "update",
                        "Status" => false,
                        "BlogArticleID" => 0
                    ];

                    $title = $_POST['title'];
                    $category = $_POST['category'];
                    $content = $_POST['content'];
                    $tags = isset($_POST['tags']) ? $_POST['tags'] : array();

                    if (!empty($title)) {
                        if (!empty($content)) {
                            $getCategoryIDQuery = "SELECT `ID` FROM `Categories` WHERE `Name` = ?";
                            $MySQLi = new \KKur\MySQLi();
                            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getCategoryIDQuery);
                            $MySQLi_STMT->_mysqli_stmt->bind_param("s", $category);
                            $MySQLi_STMT->_mysqli_stmt->execute();
                            $MySQLi_STMT->_mysqli_stmt->bind_result($CategoryID);

                            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                                $query = "UPDATE `Blog_Articles` SET `Title` = '$title', `CategoryID` = '$CategoryID', `Content` = '$content' WHERE `ID` = $blogArticleID";
                                $MySQLi_STMT->_mysqli_stmt->prepare($query);
                                $MySQLi_STMT->_mysqli_stmt->execute();

                                $deleteTagesByBlogArticleIDQuery = "DELETE FROM `Blog_Article_Tag` WHERE `BlogArticleID` = $blogArticleID";
                                $MySQLi_STMT->_mysqli_stmt->prepare($deleteTagesByBlogArticleIDQuery);
                                $MySQLi_STMT->_mysqli_stmt->execute();

                                if (!empty($tags)) {
                                    foreach ($tags as $tag) {
                                        $insertNewTagQuery = "INSERT INTO `Blog_Article_Tag`(`BlogArticleID`, `TagID`) VALUES($blogArticleID, $tag)";
                                        $MySQLi_STMT->_mysqli_stmt->prepare($insertNewTagQuery);
                                        $MySQLi_STMT->_mysqli_stmt->execute();
                                    }
                                }

                                $editor['BlogArticleID'] = $blogArticleID;
                                $editor['Status'] = true;
                            }
                        }
                    }
                }
            } 
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $editor;
    }

    public static function getTop(int $serialNumber) : array|false {
        try {
            $query = "SELECT `ID`, `Title`, `Content`, `Date` FROM `Blog_Articles` ORDER BY `PageView` DESC LIMIT $serialNumber";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $title, $content, $date);
            for ($i = 1; $MySQLi_STMT->_mysqli_stmt->fetch(); $i++) {
                if ($i == $serialNumber) {
                    if (strlen($content) > 60) {
                        $resultContent = substr($content, 0, 60) . "...";
                    } elseif (strlen($content) == 60) {
                        $resultContent = $content . "...";
                    } else {
                        $resultContent = $content;
                    }
                    return array(
                        "ID" => $ID,
                        "Title" => $title,
                        "Content" => $resultContent,
                        "Date" => $date
                    );
                }
            }
            return false;
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
    }

    public static function getLatest(int $serialNumber) : array|false {
        try {
            $query = "SELECT `ID`, `Title`, `Content`, `Date` FROM `Blog_Articles` ORDER BY `Date` DESC LIMIT $serialNumber";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $title, $content, $date);
            for ($i = 1; $MySQLi_STMT->_mysqli_stmt->fetch(); $i++) {
                if ($i == $serialNumber) {
                    if (strlen($content) > 150) {
                        $resultContent = substr($content, 0, 150) . "...";
                    } elseif (strlen($content) == 150) {
                        $resultContent = $content . "...";
                    } else {
                        $resultContent = $content;
                    }
                    return array(
                        "ID" => $ID,
                        "Title" => $title,
                        "Content" => $resultContent,
                        "Date" => $date
                    );
                }
            }
            return false;
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
    }

    public static function getBlogArticleByID(int $BlogArticleID) : array {
        $array = [];
        try {
            $query = "SELECT `Title`, `CategoryID`, `Content`, `Date` FROM `Blog_Articles` WHERE `ID` = $BlogArticleID";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($title, $categoryID, $content, $date);
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $array = [
                    "Title" => $title,
                    "Content" => $content,
                    "Date" => $date
                ];
            }

            $getCategoryNameQuery = "SELECT `Name` FROM `categories` WHERE `ID` = $categoryID";
            $MySQLi_STMT->_mysqli_stmt->prepare($getCategoryNameQuery);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($categoryName);
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $array["CategoryName"] = $categoryName;
            }

            $getTagIDQuery = "SELECT `TagID` FROM `Blog_Article_Tag` WHERE `BlogArticleID` = $BlogArticleID";
            $getTagIDMySQLi = new \KKur\MySQLi();
            $getTagIDMySQLi_STMT = new \KKur\MySQLi_STMT($getTagIDMySQLi->_get_mysqli(), $getTagIDQuery);
            $getTagIDMySQLi_STMT->_mysqli_stmt->execute();
            $getTagIDMySQLi_STMT->_mysqli_stmt->bind_result($tag);
            $tags = [];
            while ($getTagIDMySQLi_STMT->_mysqli_stmt->fetch()) {
                array_push($tags, $tag);
            }

            $array["Tags"] = $tags;
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function newComment(int $BlogArticleID) : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['save'])) {
                $userEmail = $_POST['email'];
                $comment = $_POST['comment'];

                if (!empty($userEmail)) {
                    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                        if (!empty($comment)) {
                            $query = "INSERT INTO `Comments`(`BlogArticleID`, `UserEmail`, `Content`) VALUES($BlogArticleID, '$userEmail', '$comment')";
                            $MySQLi = new \KKur\MySQLi();
                            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
                            $MySQLi_STMT->_mysqli_stmt->execute();
                            $isSuccess = true;
                        } else {
                            throw new \Exception("InvalidComment");
                        }
                    } else {
                        throw new \Exception("InvalidEmail");
                    }
                } else {
                    throw new \Exception("InvalidUserEmail");
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function getComments(int $BlogArticleID) : array {
        $array = [];
        try {
            $query = "SELECT `UserEmail`, `Content`, `Date` FROM `Comments` WHERE `BlogArticleID` = $BlogArticleID ORDER BY `Date` DESC";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($userEmail, $content, $date);
            for ($i = 0; $MySQLi_STMT->_mysqli_stmt->fetch() && $i < 10; $i++) {
                $cell = [
                    "UserEmail" => $userEmail,
                    "Content" => $content,
                    "Date" => $date
                ];
                array_push($array, $cell);
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function getAllComments(int $BlogArticleID) : array {
        try {
            $array = [];
            $query = "SELECT `ID`, `UserEmail`, `Content`, `Date` FROM `Comments` WHERE `BlogArticleID` = $BlogArticleID ORDER BY `Date` DESC";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $userEmail, $content, $date);
            while ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $cell = [
                    "ID" => $ID,
                    "UserEmail" => $userEmail,
                    "Content" => $content,
                    "Date" => $date
                ];
                array_push($array, $cell);
            }
            return $array;
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
    }

    public static function getAllByLatest() : array {
        $array = [];
        try {
            $userID = $_SESSION['UserID'];
            $query = "SELECT `ID`, `CategoryID`, `Title`, `Content`, `Date` FROM `Blog_Articles` WHERE `UserID` = $userID ORDER BY `Date` DESC";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $categoryID, $title, $content, $date);
            while ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $cell = [
                    "ID" => $ID,
                    "Title" => $title,
                    // "Content" => $content,
                    "Date" => $date
                ];

                $getCategoryNameQuery = "SELECT `Name` FROM `categories` WHERE `ID` = $categoryID";
                $getCategoryNameMySQLi = new \KKur\MySQLi();
                $getCategoryNameMySQLi_STMT = new \KKur\MySQLi_STMT($getCategoryNameMySQLi->_get_mysqli(), $getCategoryNameQuery);
                $getCategoryNameMySQLi_STMT->_mysqli_stmt->execute();
                $getCategoryNameMySQLi_STMT->_mysqli_stmt->bind_result($categoryName);
                if ($getCategoryNameMySQLi_STMT->_mysqli_stmt->fetch()) {
                    $cell["CategoryName"] = $categoryName;
                }
                array_push($array, $cell);
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function isNewEditorPage() : bool {
        try {
            if (isset($_POST['new'])) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
    }

    public static function deleteBlogArticleByID(int $blogArticleID) : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['delete'])) {
                $deleteByBlogArticleIDQuery = "DELETE FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
                $MySQLi = new \KKur\MySQLi();
                $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $deleteByBlogArticleIDQuery);
                if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                    $isSuccess = true;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function exist(int $blogArticleID) {
        $exist = false;
        $confirmBlogArticleIDQuery = "SELECT `ID` FROM `Blog_Articles`";
        $confirmBlogArticleIDMySQLi = new \KKur\MySQLi();
        $confirmBlogArticleIDMySQLi_STMT = new \KKur\MySQLi_STMT($confirmBlogArticleIDMySQLi->_get_mysqli(), $confirmBlogArticleIDQuery);
        $confirmBlogArticleIDMySQLi_STMT->_mysqli_stmt->execute();
        $confirmBlogArticleIDMySQLi_STMT->_mysqli_stmt->bind_result($confirmBlogArticleID);
        while ($confirmBlogArticleIDMySQLi_STMT->_mysqli_stmt->fetch()) {
            if ($blogArticleID == $confirmBlogArticleID) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    public static function deleteBlogArticleByIDWithouSubmit(int $blogArticleID) :bool {
        $isSuccess = false;
        try {
            if (Blog::exist($blogArticleID)) {
                $deleteByBlogArticleIDQuery = "DELETE FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
                $MySQLi = new \KKur\MySQLi();
                $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $deleteByBlogArticleIDQuery);
                if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                    $isSuccess = true;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function deleteCommentByID() : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['delete-comment'])) {
                $commentID = $_POST['delete-comment'];
                $delete = "DELETE FROM `Comments` WHERE `ID` = $commentID";
                $MySQLi = new \KKur\MySQLi();
                $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $delete);
                if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                    $isSuccess = true;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function getBlogArticleByCategoryName(string $categoryName) : array {
        $array = [];
        try {
            $getCategoryIDQuery = "SELECT `ID` FROM `Categories` WHERE `Name` = '$categoryName'";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getCategoryIDQuery);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($categoryID);
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $query = "SELECT `ID`, `Title`, `Content`, `Date` FROM `Blog_Articles` WHERE `CategoryID` = $categoryID ORDER BY `Date` DESC";
                $MySQLi_STMT->_mysqli_stmt->prepare($query);
                $MySQLi_STMT->_mysqli_stmt->execute();
                $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $title, $content, $date);
                while ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                    if (strlen($content) > 150) {
                        $resultContent = substr($content, 0, 150) . "...";
                    } elseif (strlen($content) == 150) {
                        $resultContent = $content . "...";
                    } else {
                        $resultContent = $content;
                    }

                    $cell = [
                        "ID" => $ID,
                        "Title" => $title,
                        "Content" => $resultContent,
                        "Date" => $date
                    ];

                    array_push($array, $cell);
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function getBlogArticleByArchiveDate(string $archiveStartDate, string $archiveEndDate) : array {
        $array = [];
        try {
            $query = "SELECT `ID`, `Title`, `Content`, `Date` FROM `Blog_Articles` WHERE `Date` >= '$archiveStartDate' AND `Date` < '$archiveEndDate'";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $title, $content, $date);
            while ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                if (strlen($content) > 150) {
                    $resultContent = substr($content, 0, 150) . "...";
                } elseif (strlen($content) == 150) {
                    $resultContent = $content . "...";
                } else {
                    $resultContent = $content;
                }

                $cell = [
                    "ID" => $ID,
                    "Title" => $title,
                    "Content" => $resultContent,
                    "Date" => $date
                ];

                array_push($array, $cell);
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function addPageView(int $blogArticleID) : bool {
        $isSuccess = false;
        try {
            $getPageviewQuery = "SELECT `PageView` FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getPageviewQuery);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($pageview);
        
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                $newPageView = $pageview + 1;
                $query = "UPDATE `Blog_Articles` SET `PageView`= $newPageView WHERE `ID` = $blogArticleID";
                $MySQLi_STMT->_mysqli_stmt->prepare($query);
                if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                    $isSuccess = true;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function addLikeCount(int $blogArticleID) : array {
        $array = [];
        try {
            if (isset($_POST['like'])) {
                if (strcmp($_POST['like'], "like") == 0) {
                    $getLikeCountQuery = "SELECT `LikeCount` FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getLikeCountQuery);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $MySQLi_STMT->_mysqli_stmt->bind_result($likeCount);
                
                    if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                        $newLikeCount = $likeCount + 1;
                        $query = "UPDATE `Blog_Articles` SET `LikeCount`= $newLikeCount WHERE `ID` = $blogArticleID";
                        $MySQLi_STMT->_mysqli_stmt->prepare($query);
                        if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                            $array = [
                                "status" => "unlike"
                            ];
                        }
                    }
                } elseif (strcmp($_POST['like'], "unlike") == 0) {
                    $getLikeCountQuery = "SELECT `LikeCount` FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getLikeCountQuery);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $MySQLi_STMT->_mysqli_stmt->bind_result($likeCount);
                
                    if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                        $newLikeCount = $likeCount - 1;
                        $query = "UPDATE `Blog_Articles` SET `LikeCount`= $newLikeCount WHERE `ID` = $blogArticleID";
                        $MySQLi_STMT->_mysqli_stmt->prepare($query);
                        if ($MySQLi_STMT->_mysqli_stmt->execute()) {
                            $array = [
                                "status" => "like"
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $array;
    }

    public static function getUserIDByBlogArticleID(int $blogArticleID) : int {
        $userID = 0;
        try {
            $query = "SELECT `UserID` FROM `Blog_Articles` WHERE `ID` = $blogArticleID";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($userID);
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $userID;
    }

}

