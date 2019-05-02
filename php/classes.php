<?php
/**
 * Class voor groepen
 */
class jsonData
{

  function __construct($returnCode, $output)
  {
    $this->returnCode = $returnCode;
    $this->output = $output;

  }
}
  /**
   * Class voor groepen
   */
class Group
{

  function __construct($GrID, $GrName, $GrDescr, $GrOwn)
  {
    $this->GrID = $GrID;
    $this->GrName = $GrName;
    $this->GrDescr = $GrDescr;
    $this->GrOwn = $GrOwn;
  }
}
  /**
   * Class voor invites
   */
class Invite
{

  function __construct($InvID, $SenderID, $ReceiverID, $SenderName, $ReceiverName, $GroupID, $GroupName)
  {
    $this->InvID = $InvID;
    $this->SenderID = $SenderID;
    $this->ReceiverID = $ReceiverID;
    $this->SenderName = $SenderName;
    $this->ReceiverName = $ReceiverName;
    $this->GroupID = $GroupID;
    $this->GroupName = $GroupName;
  }
}

  /**
   * Class voor vakken
   */
class Course
{

  function __construct($crID, $crName, $crDescr, $crGroupName, $crGroupID)
  {
    $this->crID = $crID;
    $this->crName = $crName;
    $this->crDescr = $crDescr;
    $this->crGroupName = $crGroupName;
    $this->crGroupID = $crGroupID;
  }
}

  /**
   * Class voor group leden
   */


class Member
{

 function __construct($userID, $firstName, $lastName, $nickName, $userRank)
 {
   $this->userID = $userID;
   $this->firstName = $firstName;
   $this->lastName = $lastName;
   $this->nickName = $nickName;
   $this->userRank = $userRank;
 }
}

/*Class voor chatMessages
*/
class chatMessage{

    function __construct($chatMessage, $chatSendtime , $Nickname, $UserID)
    {
      $this->chatMessage = $chatMessage;
      $this->chatSendtime = $chatSendtime;
      $this->nickname = $Nickname;
      $this->userID = $UserID;
    }

}

class forumAnswer{

    function __construct($FPostAnswerMessage, $FPostAnswerTime, $Nickname)
    {
      $this->FPostAnswerMessage = $FPostAnswerMessage;
      $this->FPostAnswerTimestamp = $FPostAnswerTime;
      $this->Nickname = $Nickname;
    }

}

 ?>
