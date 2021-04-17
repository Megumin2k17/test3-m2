<?php 

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder {

	private $pdo, $queryFactory;

	public function __construct(PDO $pdo, QueryFactory $queryFactory) {
		$this->pdo = $pdo;

		$this->queryFactory = $queryFactory;		
	}

	public function getAll($table, $cols, $condition = null, $paging = null) {
		

		$select = $this->queryFactory->newSelect();

		$select->from($table)->cols($cols);

		if ($condition) {
			$select->where($condition);
		}

		if ($paging) {
			$select->setPaging($paging);
			$select->page($_GET['page'] ?? 1);
		}
		
		$statement = $this->pdo->prepare($select->getStatement());

		$statement->execute($select->getBindValues());
  		
		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	public function getOne($table, $cols, $condition = null) {	

		
		$select = $this->queryFactory->newSelect();
// d($select);die;
		$select->from($table)->cols($cols);

		if ($condition) {
			$select->where($condition);
		}

		$statement = $this->pdo->prepare($select->getStatement());

		$statement->execute($select->getBindValues()); 
		// d($cols[0]); die;
		if(count($cols) === 1 && $cols[0] !== '*') {
			
			$data = $statement->fetchColumn();

			return $data;
		}

		
		$data = $statement->fetch(PDO::FETCH_ASSOC);

		return $data;
	}

	
	public function insert($table, $cols_values) {
		
		$insert = $this->queryFactory->newInsert();

		$insert->into($table)->cols($cols_values);

		$statement = $this->pdo->prepare($insert->getStatement());
		// var_dump($statement); die;
		$statement->execute($insert->getBindValues());

		$name = $insert->getLastInsertIdName('id');
		return $id = $this->pdo->lastInsertId($name);
	}

	public function update($table, $cols, $condition) {

		$update = $this->queryFactory->newUpdate();

		$update->table($table)->cols($cols)->where($condition);

		$statement = $this->pdo->prepare($update->getStatement()); 
		// var_dump($update->getStatement());die;

		$statement->execute($update->getBindValues());		

	}

	public function delete ($table, $condition) {
		
		$delete = $this->queryFactory->newDelete();

		$delete->from($table)->where($condition);

		$statement = $this->pdo->prepare($delete->getStatement());

		$statement->execute($delete->getBindValues());
	}


}

?>