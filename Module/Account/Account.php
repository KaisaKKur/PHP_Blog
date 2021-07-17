<?php
namespace KKur;

require_once 'KDateTime.php';
require_once 'KType.php';
require_once 'Module/MySQLi/MySQLi.php';
require_once 'Module/MySQLi/MySQLi_STMT.php';
require_once 'Module/Blog/Blog.php';

const DefaultUsername = "Soul";
const DefaultPassword = "";

class Account {

    public static function getProfile() : array {
        try {
            $ID = $_SESSION['UserID'];
            $query = "SELECT `Username`, `Password`, `Nickname`, `Email`, `PhoneNumber`, `Birthday`, `Sex` FROM `Users` WHERE `ID` = $ID";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($username, $password, $nickname, $email, $phoneNumber, $birthday, $sex);
            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                return array(
                    "Username" => $username,
                    "Password" => $password,
                    "Nickname" => $nickname,
                    "Email" => $email,
                    "PhoneNumber" => $phoneNumber,
                    "Birthday" => $birthday,
                    "Sex" => $sex
                );
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
    }

    public static function logIn() : bool {
        $isSuccess = false;
        try {
            if(isset($_POST['log-in'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if (empty($username) || empty($password)) {
                    throw new \Exception("None");
                } else {
                    $query = "SELECT `ID`, Password FROM `Users` WHERE `Username` = ?";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
                    $MySQLi_STMT->_mysqli_stmt->bind_param("s", $username);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $MySQLi_STMT->_mysqli_stmt->store_result();
        
                    if ($MySQLi_STMT->_mysqli_stmt->num_rows > 0) {
                        $MySQLi_STMT->_mysqli_stmt->bind_result($ID, $Password);
                        if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                            $passwordChecked = strcmp(sha1($password), $Password) == 0 ? true : false;
                            if ($passwordChecked) {
                                $_SESSION['UserID'] = $ID;
                                $_SESSION['Username'] = $username;
                                $isSuccess = true;
                            } else {
                                throw new \Exception("PasswordUnmatch");
                            }
                        } else {
                            throw new \Exception("fetch");
                        }
                    } else {
                        throw new \Exception("NoUser");
                    }
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function signOut() : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['sign-out'])) {
                $isSuccess = true;
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function verifyCode(string $verificationCode) : bool {
        $verify = false;
        try {
            $query = "SELECT `Code` FROM `Sign_Up_Verification_Codes`";
            $MySQLi = new \KKur\MySQLi();
            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
            $MySQLi_STMT->_mysqli_stmt->execute();
            $MySQLi_STMT->_mysqli_stmt->bind_result($code);
            while ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                if (strcmp($verificationCode, $code) == 0) {
                    $verify = true;
                    break;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $verify;
    }

    public static function signUp() : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['sign-up'])) {
                $verificationCode = $_POST['verification-code'];
                
                if (Account::verifyCode($verificationCode)) {
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirm-password'];

                    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                        throw new \Exception("None");
                    } else {
                        if (preg_match("/^[a-zA-Z]\w{4,28}[a-zA-Z0-9]$/", $username) == 0) { // 6-30 words a-zA-Z0-9 _
                            throw new \Exception("InvalidUsername");
                        }

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            throw new \Exception("InvalidEmail");
                        }

                        if (preg_match("/^[\w.]{8,40}$/", $password) == 0) { // 8-40 word a-zA-Z0-9 _ .
                            throw new \Exception("InvalidPassword");
                        }

                        if (strcmp($confirmPassword, $password) != 0) {
                            throw new \Exception("PasswordUnmatch");
                        }

                        {
                            $query = "SELECT COUNT(`Username`) FROM `Users` WHERE `Username` = ?";
                            $MySQLi = new \KKur\MySQLi();
                            $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
                            $MySQLi_STMT->_mysqli_stmt->bind_param("s", $username);
                            $MySQLi_STMT->_mysqli_stmt->execute();
                            $MySQLi_STMT->_mysqli_stmt->bind_result($isTaken);
                            if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                                if ($isTaken > 0) {
                                    throw new \Exception("UsernameTaken");
                                } else {
                                    $query = "INSERT INTO `Users`(`Username`, Password, `Email`) VALUES (?, ?, ?)";
                                    $passwordSHA1 = sha1($password);
                                    $MySQLi_STMT->_mysqli_stmt->prepare($query);
                                    $MySQLi_STMT->_mysqli_stmt->bind_param("sss", $username, $passwordSHA1, $email);
                                    $MySQLi_STMT->_mysqli_stmt->execute();

                                    $getIDQuery = "SELECT `ID` FROM `Users` WHERE `Username` = ?";
                                    $MySQLi_STMT->_mysqli_stmt->prepare($getIDQuery);
                                    $MySQLi_STMT->_mysqli_stmt->bind_param("s", $username);
                                    $MySQLi_STMT->_mysqli_stmt->execute();
                                    $MySQLi_STMT->_mysqli_stmt->bind_result($ID);
                                    if (!$MySQLi_STMT->_mysqli_stmt->fetch()) {
                                        throw new \Exception("LogInFailed");
                                    }
                                    $_SESSION['UserID'] = $ID;
                                    $_SESSION['Username'] = $username;
                                    $isSuccess = true;
                                }
                            }
                        }
                    }
                } else {
                    echo "
                        <script type='text/javascript'>
                          var show = \"Hasn't a Verification Code? Why? Soul，你是怎么到这里的，没有 Admin 的通行证，no body can 摆弄 KKur 的核心！\";
                          alert(show);
                        </script>
                    ";
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function updateProfile() : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['update-profile'])) {
                $nickname = $_POST['nickname'];
                $email = $_POST['email'];
                $phoneNumber =$_POST['phone-number'];
                $birthday = $_POST['birthday'];
                $sex = $_POST['sex'];

                if (empty($nickname)) {
                    $nicknameQuery = "`Nickname` = DEFAULT";
                } else {
                    if (preg_match("/^[a-zA-Z0-9]\w{1,28}[a-zA-Z0-9]$/", $nickname) == 0) { // 3-30 words a-zA-Z0-9 _
                        throw new \Exception("InvalidNickname");
                    } else {
                        $nicknameQuery = "`Nickname` = '$nickname'";
                    }
                }
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("InvalidEmail");
                } else {
                    $emailQuery = "`Email` = '$email'";
                }

                if (empty($phoneNumber)) {
                    $phoneNumberQuery = "`PhoneNumber` = DEFAULT";
                } else {
                    if (preg_match("/^[0-9]{7,20}$/", $phoneNumber) == 0) {
                        throw new \Exception("InvalidPhoneNumber");
                    } else {
                        $phoneNumberQuery = "`PhoneNumber` = '$phoneNumber'";
                    }
                }

                if (empty($birthday)) {
                    $birthdayQuery = "`Birthday` = DEFAULT";
                } else {
                    if (!\KKur\KDateTime::isDateValid($birthday)) {
                        throw new \Exception("InvalidBirthday");
                    } else {
                        $birthdayQuery = "`Birthday` = '$birthday'";
                    }
                }

                if (empty($sex)) {
                    $sexQuery = "`Sex` = DEFAULT";
                } else {
                    if (\KKur\KType::stringToBool($sex)) {
                        $sexQuery = "`Sex` = true";
                    } else {
                        $sexQuery = "`Sex` = false";
                    }
                }

                {
                    $ID = $_SESSION['UserID'];
                    $query = "UPDATE `Users` SET $nicknameQuery, $emailQuery, $phoneNumberQuery, $birthdayQuery, $sexQuery WHERE `ID`= $ID";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $query);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $isSuccess = true;
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }
    
    public static function updatePassword() : bool {
        $isSuccess = false;
        try {
            if (isset($_POST['update-password'])) {
                $oldPassword = $_POST['old-password'];
                $newPassword = $_POST['new-password'];
                $confirmPassword = $_POST['confirm-password'];

                if (empty($oldPassword)) {
                    throw new \Exception("InvalidOldPasswor");
                } else {
                    $oldPasswordQuery = "SELECT `Password` FROM `Users` WHERE `ID` = ?";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $oldPasswordQuery);
                    $MySQLi_STMT->_mysqli_stmt->bind_param("s", $_SESSION['UserID']);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $MySQLi_STMT->_mysqli_stmt->bind_result($password);
                    if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                        if (strcmp(sha1($oldPassword), $password) == 0) {
                            if (!empty($newPassword)) {
                                if (preg_match("/^[\w.]{8,40}$/", $newPassword) == 0) { // 8-40 word a-zA-Z0-9 _ .
                                    throw new \Exception("InvalidPassword");
                                }
                            } else {
                                throw new \Exception("InvalidNewPasswor");
                            }
        
                            if (!empty($confirmPassword)) {
                                if (strcmp($confirmPassword, $newPassword) != 0) {
                                    throw new \Exception("PasswordUnmatch");
                                }
                            } else {
                                throw new \Exception("InvalidConfirmPasswor");
                            }

                            {
                                $ID = $_SESSION['UserID'];
                                $newPasswordSHA1 = sha1($newPassword);
                                $query = "UPDATE `Users` SET Password = '$newPasswordSHA1' WHERE `ID` = $ID";
                                $MySQLi_STMT->_mysqli_stmt->prepare($query);
                                $MySQLi_STMT->_mysqli_stmt->execute();
                                $isSuccess = true;
                            } 
                        } else {
                            throw new \Exception("ErrorOldPassword");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

    public static function delete() {
        $isSuccess = false;
        try {
            if (isset($_POST['delete'])) {
                $requestPassword = $_POST['request-password'];

                if (!empty($requestPassword)) {
                    $getPasswordQuery = "SELECT `Password` FROM `Users` WHERE `ID` = ?";
                    $MySQLi = new \KKur\MySQLi();
                    $MySQLi_STMT = new \KKur\MySQLi_STMT($MySQLi->_get_mysqli(), $getPasswordQuery);
                    $MySQLi_STMT->_mysqli_stmt->bind_param("s", $_SESSION['UserID']);
                    $MySQLi_STMT->_mysqli_stmt->execute();
                    $MySQLi_STMT->_mysqli_stmt->bind_result($password);
                    if ($MySQLi_STMT->_mysqli_stmt->fetch()) {
                        if (strcmp(sha1($requestPassword), $password) == 0) {
                            // Delete User
                            $query = "DELETE FROM `Users` WHERE `ID` = ?";
                            $MySQLi_STMT->_mysqli_stmt->prepare($query);
                            $MySQLi_STMT->_mysqli_stmt->bind_param("s", $_SESSION['UserID']);
                            $MySQLi_STMT->_mysqli_stmt->execute();
                            $isSuccess = true;
                        } else {
                            throw new \Exception("ErrorPassword");
                        }
                    }
                } else {
                    throw new \Exception("InvalidPassword");
                }
            }
        } catch (\Exception $e) {
            echo "Error=" . $e->getMessage();
        }
        return $isSuccess;
    }

}

