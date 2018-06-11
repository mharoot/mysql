<?php
class Collections {
    private $_pdo;
    private $_stmt;
    private $_visited;
    private $_cycles;
    private $_tablename = "collectionsnonindexed";

    public function __construct() {
        $this->_pdo = new PDO("mysql:dbname=indexingmysql;host=localhost", "root", "");
        $this->_stmt = NULL;
        $this->_visited = array();
        $this->_cycles = 0;
    }

    public function query($q) {
        $this->_stmt = $this->_pdo->prepare($q);
        $this->_stmt->execute();
    }

    public function apply_changes($id) {
        $this->_cycles++;
        $hasNotBeenVisited = ! isset($this->_visited[$id]);
        $this->_visited[$id] = true;
        $bound = $id > -1;

        if ($hasNotBeenVisited && $bound) {
            $this->query("SELECT * FROM $this->_tablename WHERE id = $id");
            $item = $this->_stmt->fetch(PDO::FETCH_OBJ);
            if ($item->type == 0) {
                //echo "Traversed into Collection: $id</br>";
                $this->query("SELECT * FROM $this->_tablename WHERE parent = $id");
                $results = $this->_stmt->fetchAll(PDO::FETCH_OBJ);
                foreach($results as $r) {
                    if ($r->type==0) {
                        $this->apply_changes($r->id);
                    } else {
                        $this->_visited[$r->id] = true;
                        //echo "Applying Changes to product: $r->id</br>";
                    }
                }
            }
            $this->apply_changes( $item->parent );
        }
    }

    public function printCycles() {
        echo "</br></br>Cycles: $this->_cycles</br>";
        $this->_visited = array();
        $this->_cycles = 0;
    }

    public function setTablename($name) {
        $this->_tablename = $name;
    }
}

$C = new Collections();

function test($C, $num) {
    $time_start = microtime(true);
    $C->apply_changes(15);
    $C->printCycles();
    $time_end = microtime(true);
    $time_elapsed = $time_end - $time_start;
    print "</br>Elapsed Time: $time_elapsed</br></br></br>";
}


echo "<h1>nonindexedcollections table:</h1></br>";
test($C, 15);

echo "<h1>collections table:</h1></br>";
$C->setTablename("collections"); //seting to indexed version
test($C, 15);

echo "<h1>collectionspkonly table:</h1></br>";
$C->setTablename("collectionspkonly"); //seting to indexed version
test($C, 15);

echo "<h1>collectionsparentkeyonly table:</h1></br>";
$C->setTablename("collectionsparentkeyonly"); //seting to indexed version
test($C, 15);
