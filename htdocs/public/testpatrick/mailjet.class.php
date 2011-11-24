<?php
require_once(DOL_DOCUMENT_ROOT."/comm/mailing/class/mailing.class.php");
class Mailjet extends Mailing
{
     function Mailjet($DB){  
        parent::Mailing($DB);
    }
   /* function test(){
        
        $reponse = $this->db->query('SELECT max(rowid)as maxid FROM llx_mailing_cibles');
        $obj = $this->db->fetch_object($reponse);
        print $obj->maxid;
       
    }*/
    
    
    function testrecup($data){
          $unTruc = 10;
          if($data!=null){
              $unObjet = 200;
               foreach($data as $key => $value){
                 $sql2 = "INSERT INTO testjson values ('$key','$value')";
                 $this->db->query($sql2);
             }
          }
          else {
              $sql = "INSERT INTO testjson values ('$unTruc')";
              $this->db->query($sql);
          }
          
    }
    function updateStatut($event,$idSource){
        $verif = "INSERT INTO testjson values ('$event','$idSource')";
        $this->db->query($verif);
        
                $sql = "UPDATE ".MAIN_DB_PREFIX."mailing_cibles";
		$sql.= " SET statut =".$event;
		$sql.= " WHERE source_id= ".$idSource;        
        $this->db->query($sql);
        
    }
    function event($arr){
        $event=0;
        $id=0;
        foreach($arr as $key => $value){
          if($value=='open') $event = 4;
          if($key=='mj_campaign_id') $id = $value;
        }
         $this->updateStatut($event,$id); 
          
    }
   
            //$this->api->sendRequest("messageList" /*,array("from_name" => $name*/);
                 //     $this->_response=200; 
	 //   $xmlListAll=$this->api->_response;
              //$size = (int)$xmlListAll->total_cnt;
           /* for($i=0;$i<$size;$i++){ 
                $this->api->sendRequest("messageStatistics",array("id" => $xmlListAll->result->item[$i]->id),"GET");
                $xmlResultItemById = simplexml_load_string($this->api->_response);
                $colXml[$i]=$xmlResultItemById;
            }*/
           // return $colXml
            // $xml=simplexml_load_string($this->api->_response);
           // $tt = $this->api->requestUrlBuilder("messageStatistics",array("id" => 29313056),"GET");  
            //$this->api->sendRequest("messageList");      
      //      $test = $this->api->_response; 
            //$xml=simplexml_load_string($this->api->_response);
            //return (int)$xml->result->$type;
}

?>
