<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

// Resgate dos dados, e escolher o serviço
if ($method === "GET") {

    $servicosQuery = $conn->query("SELECT * FROM servicos;");
    $servicos = $servicosQuery->fetchAll();

// Realização do agendamento
} elseif ($method === "POST") {
    $data = $_POST;

    $nome = $data["nome"];
    $email = $data["email"];
    $telefone = $data["telefone"];
    $servicos = $data["servicos"];
    $dataMarcada = $data["data"];
    $hora = $data["hora"];

    // Validação para saber se o horário está disponível:
    $query = "SELECT * FROM reservas WHERE data_marcada = :data AND hora = :hora AND status_id = '1';";
    $stmt = $conn->prepare($query);
    $stmt->execute(['data' => $dataMarcada, 'hora' => $hora]);

    if ($stmt->rowCount() > 0) {
        $_SESSION["msg"] = "Este horário já está reservado!";
        $_SESSION["status"] = "warning";
    } else {
        // Inserindo o cliente na tabela de agendamentos
        $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, email) VALUES (:nome, :telefone, :email);");
        $stmt->execute(['nome' => $nome, 'telefone' => $telefone, 'email' => $email]);
        $cliente_id = $conn->lastInsertId();

        // Inserindo os dados na tabela de reservas
        $stmt = $conn->prepare("INSERT INTO reservas (cliente_id, servico_id, data_marcada, hora, status_id) 
                               VALUES (:cliente_id, :servico_id, :data_marcada, :hora, :status_id);");

        // Definindo o status como 'agendado' (presumindo que o status_id '1' é 'agendado')
        $status_id = 1;

        // Loop para resgatar os serviços que foram escolhidos
        foreach ($servicos as $servico) {
            // Filtrando os inputs e incluindo o status_id
            $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
            $stmt->bindParam(":servico_id", $servico, PDO::PARAM_INT);
            $stmt->bindParam(":data_marcada", $dataMarcada, PDO::PARAM_STR); // Usando PDO::PARAM_STR para data
            $stmt->bindParam(":hora", $hora, PDO::PARAM_STR); // Usando PDO::PARAM_STR para hora
            $stmt->bindParam(":status_id", $status_id, PDO::PARAM_INT); // Associando o status 'agendado'

            $stmt->execute();
        }

        // Exibir mensagem de sucesso
        $_SESSION["msg"] = "Agendamento realizado com sucesso!";
        $_SESSION["status"] = "success";
    }

    // Retorna para página inicial
    header("Location: ..");
}
?>
