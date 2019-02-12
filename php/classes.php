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


 ?>
