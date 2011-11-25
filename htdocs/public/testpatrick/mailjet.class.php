<?php
require_once(DOL_DOCUMENT_ROOT."/comm/mailing/class/mailing.class.php");
class Mailjet extends Mailing
{
     function Mailjet($DB){  
        parent::Mailing($DB);
    }

   
    function updateStatut($id,$mail,$event){    
                $sql = "UPDATE ".MAIN_DB_PREFIX."mailing_cibles";
		$sql.= " SET statut =".$event;
		$sql.= " WHERE fk_mailing= ".$id;
                $sql.= " AND email LIKE ".'"'.$mail.'"';
                $this->db->query($sql);  
                
    }
    
    
    function event($arr){
        $event=0;
        $id=0;
        $customID=0;
        $mail;
        foreach($arr as $key => $value){
          if($value=='open') $event = 4;
          if($key=='customcampaign') $customID = $value;
          if($key=='email') $mail = $value;
        }
        $this->updateStatut($customID,$mail,$event);   
    }
   }
   

?>
