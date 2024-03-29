<?php
class PrixStockManager extends Manager{
    public function findAllByCondition($dataCondition = [],$order='',$type = 'obj'){
        return $this->findAllByConditionTable('prixstock',$dataCondition,$order,$type);
    }
    public function findOneByCondition($dataCondition = [], $type = 'obj'){
        return $this->findOneByConditionTable('prixstock',$dataCondition,$type);
    }
    
    public function search($columnLikes,$mot){
        return $this->searchTable('prixstock',$columnLikes,$mot);
    }
    public function update($data,$id){
        $this->updateTable('prixstock',$data,$id);
    }
    public function insert($data){
        $this->insertTable('prixstock',$data);
    }
    public function getDescribe(){
        $resultat=$this->getDescribeTable('prixstock');
        return $resultat;
    }
    public function findById($id,$type="obj"){
        $resultat=$this->findByIdTable('prixstock',$id);
        if($type=="obj"){
            $objet=new PrixStock($resultat);
            return $objet;
        }else{
            return $resultat;
        }
    }
    public function deleteById($id){
        $this->deleteByIdTable('prixstock',$id);
    }
    public function findAll(){
        $resultat=$this->listTable('prixstock');
        return $resultat;
    }
    
}