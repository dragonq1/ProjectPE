<?php
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

    function __construct($InvID, $SenderID, $ReceiverID, $GroupID)
    {
      $this->InvID = $InvID;
      $this->SenderID = $SenderID;
      $this->ReceiverID = $ReceiverID;
      $this->GroupID = $GroupID;
    }
  }


 ?>
