<?php
//INSERT INTO `task` (`nm_task`, `cd_order_task`, `cd_collum`, `cd_type`, `cd_responsable`) VALUES  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)),  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)),  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)),  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)),  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)),  (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)) (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?)) (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?))
    class colaborador
    {
        public $serverName = '127.0.0.1';
        public $userName = 'root';
        public $database = 'projkanban';
        public $password = '';

        function testConection(){
            $conexao = new mysqli($this->serverName, $this->userName, $this->password, $this->database);
            if ($conexao->connect_error) {
                echo false;//.$conexao->connect_error;
            }
            else
                echo true;
        }
        function showColluns(){
            $conexao = new mysqli($this->serverName, $this->userName, $this->password, $this->database);
            if ($conexao->connect_error) {
                return false;
            }
            else{
                try{
                    $query = $conexao->prepare('
                    SELECT task.cd_task, task.nm_task, task.cd_order_task, type.nm_type, responsable.nm_responsable, collum.*
                    FROM task
                    RIGHT JOIN collum ON task.cd_collum = collum.cd_collum
                    LEFT OUTER JOIN type ON type.cd_type = task.cd_type
                    LEFT OUTER JOIN responsable ON responsable.cd_responsable = task.cd_responsable
                    ORDER BY collum.cd_order_collum, task.cd_order_task ASC;');
                    $query->execute();
                    $result = $query->get_result();
                    if ($result->num_rows) {
                        $index = 0;
                        $lastCollumCd = null;
                        $lastIndex = 0;
                        while($info = $result->fetch_assoc()){
                            if ($lastCollumCd != $info['cd_collum']) {
                                $index2 = 0;
                                $collum[$index]["cd_collum"] = $info['cd_collum'];
                                $collum[$index]["nm_collum"] = $info['nm_collum'];
                                $collum[$index]["cd_order_collum"] = $info['cd_order_collum'];
                            
                                $lastCollumCd = $info['cd_collum'];
                                $lastIndex = $index;
                                $index++;
                            }
                            if ($info['cd_task']){
                                $collum[$lastIndex]["task"][$index2]["cd_task"] = $info['cd_task'];
                                $collum[$lastIndex]["task"][$index2]["nm_task"] = $info['nm_task'];
                                $collum[$lastIndex]["task"][$index2]["nm_responsable"] = $info['nm_responsable'];
                                $index2++;
                            }
                        }
                        return $collum;
                    }
                    else
                        return false;
                }
                catch(Exception $e){
                    error_log($e->getMessage());
                    exit('Error on Select');
                }
            }
        }
        function saveKanban($kanban){
            $conexao = new mysqli($this->serverName, $this->userName, $this->password, $this->database);
            if ($conexao->connect_error) {
                return false;
            }
            else{
                try{
                    $query = 'DELETE FROM `task`';
                    $prepQuery = $conexao->prepare($query);
                    $prepQuery->execute();

                    $query = 'INSERT INTO `task` (`nm_task`, `cd_order_task`, `cd_collum`, `cd_type`, `cd_responsable`) VALUES (?, ?, (SELECT cd_collum FROM collum WHERE nm_collum = ?), 1, (SELECT cd_responsable FROM responsable WHERE nm_responsable = ?))';
                    $prepQuery = $conexao->prepare($query);
                    for ($index=0; $index < sizeof($kanban) ; $index++) {
                        //echo $kanban[$index]["cd_order_collum"];echo " ";
                        $nm_collum = $kanban[$index]["nm_collum"];
                        for ($index2=0; $index2 < sizeof($kanban[$index]["task"]) ; $index2++) {
                            $cd_order_task = $kanban[$index]["task"][$index2]["cd_order_task"];
                            $nm_task = $kanban[$index]["task"][$index2]["nm_task"];
                            $nm_responsable = $kanban[$index]["task"][$index2]["nm_responsable"];

                            $prepQuery->bind_param('ssss',$nm_task, $cd_order_task, $nm_collum, $nm_responsable);
                            $prepQuery->execute();
                            $result = $prepQuery->get_result();
                        }
                    }
                    return $result;
                }
                catch(Exception $e){
                    error_log($e->getMessage());
                    exit('Error on Insert');
                }
            }
        }
    }
?>