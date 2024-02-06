<?php

require_once("./Config/database_settings.php");

class Manager{

    function findAllByConditionTable($table,$dataCondition=[],$order='',$type='obj'){
        $connexion=$this->connexion();
        $condition='';
        $values=[];
        foreach($dataCondition as $key=>$value){
            $condition.=(!$condition)?" $key=? " : " and $key=? ";
            $values[]=$value;
        }
        $condition=(!$condition)?"true" : $condition;
        $sql="select * from $table where $condition $order";
        // echo $sql;
        // printr($values);die;
        $requete=$connexion->prepare($sql);
        $requete->execute($values);
        $resultats=$requete->fetchAll(PDO::FETCH_ASSOC);
        if($type=='obj'){
            $class=ucfirst($table);
            // printr($resultats);die;
            $objs=[];
            foreach($resultats as $value){
                $obj=new $class($value);
                $objs[]=$obj;
            }
            return $objs;
        }else{
            return $resultats;
        }
    }
        // generation d'une requete sql pour faire une recherche suivant le contenue de $dataCondition
        function findOneByConditionTable($table,$dataCondition=[],$type='obj'){
            $connexion=$this->connexion();
            $condition='';
            $values=[];
            foreach($dataCondition as $key=>$value){
                $condition.=(!$condition)?" $key=? " : " and $key=? ";
                $values[]=$value;
            }
            $condition=(!$condition)?"true" : $condition;
            $sql="select * from $table where $condition";
            // echo $sql;
            // printr($values);die;
            $requete=$connexion->prepare($sql);
            $requete->execute($values);
            $resultat=$requete->fetch(PDO::FETCH_ASSOC);
            if($type=='obj'){
                $class=ucfirst($table);
                $obj=new $class($resultat);
                return $obj;
            }else{
                return $resultat;
            }
        }
    public function searchTable($table,$columnLikes,$mot){
        $connexion=$this->connexion();
        $condition="";
        $values=[];
        foreach($columnLikes as $value){
            $condition.=($condition=="")  ?  "$value like ? "  :  " or $value like ?";
            $values[]="%$mot%";
        }
        $sql="select * from $table where $condition";
        // --- test
        // echo $sql;
        // MyFct::sprintr($values);
        // die;
        // ---
        $requete=$connexion->prepare($sql);
        $requete->execute($values);
        $resultat=$requete->fetchAll(PDO::FETCH_ASSOC);
        return $resultat;
    }
    function updateTable($table,$data,$id){
        $connexion=$this->connexion();
        $setColumn="";
        $values=[];
        foreach($data as $key=>$value){
            if($key!='id'){
                $setColumn.=($setColumn=="")  ?  "$key=?"  :  ",$key=?"; // if ternaire
                $values[]=$value;
            }
        }
        $sql="update $table set $setColumn where id=?";
        $values[]=$id;
        // --- test
        // echo $sql;
        // MyFct::sprintr($values);
        // die;
        // ---
        $requete=$connexion->prepare($sql);
        $requete->execute($values);
    }
    function insertTable($table,$data){
        $connexion=$this->connexion();
        $column="";
        $pi=""; // point d'interrogation
        $values=[];// tableau pour la method execute
        // ----- geneeration de la requete
        foreach($data as $key=>$value){
            if($key!='id'){
                if($column==''){
                    $column.=$key;
                    $pi.="?";
                }else{
                    $column.=",$key";
                    $pi.=",?";
                }
                $values[]=$value;
            }
        }
        $sql="insert into $table ($column) values ($pi)";
        
        $requete=$connexion->prepare($sql);
        // printr($value);die("Die Manager.php ligne 119");
        $requete->execute($values);
    }

    function connexion($dbhost=HOST,$dbname=DBNAME,$dbuser=USER,$dbpass=PASSWORD){
        try{
            $connexion = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8",$dbuser,$dbpass);
      }catch(Exception $e){
            echo "<h1> $dbhost $dbname $dbuser $dbpass Connexion  ! Vérifiez les paramètres!</h1>";
            die;
      }
      return $connexion;
        }
    
    function getDescribeTable($table){
        $connexion=$this->connexion();
        $sql="desc $table";  // requete pour affichage de la structure la table collaborateur
        $requete=$connexion->prepare($sql);
        $requete->execute();
        $colonnes=$requete->fetchAll(PDO::FETCH_COLUMN);// recuperation de tous les noms de colonne de la table collaborateur
        $variables=[];
        foreach($colonnes as $valeur){
            $variables[$valeur]='';
        }  
        return $variables;  
    }
    
    function findByIdTable($nomTable,$id){
        $connexion=$this->connexion();
        $sql="select * from $nomTable where id=?";
        $requete=$connexion->prepare($sql);
        $requete->execute([$id]);
        ///$resultat=$requete->fetch(); // Mettre dans $ article l'article trouvé
        $resultat=$requete->fetch(PDO::FETCH_ASSOC);
        return $resultat;
    }
    
    function deleteByIdTable($nomTable,$id){
        $connexion=$this->connexion();
        $sql="delete from $nomTable where id=?";
        $requete=$connexion->prepare($sql);
        $requete->execute([$id]);
        return true;
    }
    
    function listTable($nomTable,$order=''){
        $sql="select * from $nomTable $order";
        $connexion=$this->connexion();
        $requete=$connexion->prepare($sql);
        $requete->execute();
        $tables=$requete->fetchAll(PDO::FETCH_ASSOC); // quand on rajoute PDO::FETCH_ASSOC les articles ne sont pas doublées
        return $tables;
    }
    
    function testDelete($id){
        $connexion=$this->connexion();
        $sql="select * from ligneCommande where article_id=?";
        $requete=$connexion->prepare($sql);
        $requete->execute([$id]);
        $resultat=$requete->fetch();
        if($resultat){
            return false;
        }else{
            return true;
        }
    }
}