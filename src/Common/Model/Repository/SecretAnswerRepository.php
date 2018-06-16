<?php

namespace  Common\Model\Repository;


/**
 * SecretAnswerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SecretAnswerRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAnswerForAQuestion($questionId,$customerId,$role) {
        
        $answer = $this->getEntityManager()
         ->createQuery(
             'SELECT s FROM Model:SecretAnswer s where s.roleId=:cid and s.questionId= :qid and s.role=:role')
             ->setParameter('cid', $customerId)
             ->setParameter('qid', $questionId)
             ->setParameter('role', $role)
             ->getResult(); 
       
        return $answer; 
       
       
    }
    
    public function getQuestionAnswer($customerId) {
        $answer = $this->getEntityManager()
        ->createQuery(
            'SELECT s FROM Model:SecretAnswer s where s.customerId=:cid')
            ->setParameter('cid', $customerId)
            ->getResult();
            return $answer;
                        
    }
    
    
    
}
