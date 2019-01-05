<?php

/*$j = '[{"nm_collum":"A Fazer","cd_order_collum":1,"task":[{"cd_order_task":1,"nm_task":"Tarefa Teste","nm_responsable":"Pessoa1"},{"cd_order_task":2,"nm_task":"Tarefa Teste2","nm_responsable":"Pessoa1"},{"cd_order_task":3,"nm_task":"Tarefa Teste2","nm_responsable":"Pessoa1"},{"cd_order_task":4,"nm_task":"Tarefa Teste2","nm_responsable":"Pessoa1"},{"cd_order_task":5,"nm_task":"Tarefa Teste2","nm_responsable":"Pessoa1"}]},{"nm_collum":"Fazendo","cd_order_collum":2,"task":[]},{"nm_collum":"Feito","cd_order_collum":3,"task":[]},{"nm_collum":"Pronto","cd_order_collum":4,"task":[]}]';
require_once('model.php');
$kanban = json_decode($j, true);
$colab = new colaborador();
$result = $colab->saveKanban($kanban);
echo $result;
echo "<pre>";
for ($index=0; $index < sizeof($kanban) ; $index++) {
    echo $kanban[$index]["cd_order_collum"];
    echo " ";
    echo $kanban[$index]["nm_collum"];
    echo "\n";
    for ($index2=0; $index2 < sizeof($kanban[$index]["task"]) ; $index2++) {
        echo "   ";
        echo $kanban[$index]["task"][$index2]["cd_order_task"];
        echo ", ";
        echo $kanban[$index]["task"][$index2]["nm_task"];
        echo ", ";
        echo $kanban[$index]["task"][$index2]["nm_responsable"];
        echo "\n";
    }
}//*/

//var_dump($kanban);
//echo $result;

    if(isset($_POST['action']) && !empty($_POST['action'])){
        $function = $_POST['action'];
        require_once('model.php');
        
        if ($function == "testConnection") {
            $colab = new colaborador();
            $result = $colab->testConection();
            echo $result;
        }
        if ($function == "showColluns") {
            $colab = new colaborador();
            $result = $colab->showColluns();
            echo json_encode($result);
        }
        if ($function == "saveKanban") {
            $kanban = json_decode($_POST['kanban'], true);
            $colab = new colaborador();
            $result = $colab->saveKanban($kanban);
            echo $result;
        }

        /*if ($function == "new") {
            $char = new character();
            $chars = $char->showChars();
            echo json_encode($chars);
        }*/

    }
    
?>