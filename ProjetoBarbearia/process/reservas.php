<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {

    // Resgatar todas as reservas
    $reservasQuery = $conn->query("SELECT * FROM reservas;");
    $reservas = $reservasQuery->fetchAll(PDO::FETCH_ASSOC);

    // Irá armazenar os serviços a serem realizados
    $servicosRealizados = [];

    foreach ($reservas as $reserva) {

        $servicoRealizar = [];
        $servicoRealizar["id"] = $reserva["servico_id"];

        // Resgatando o nome do serviço diretamente da tabela 'servicos'
        $servicoQuery = $conn->prepare("SELECT tipo FROM servicos WHERE id = :servico_id;");
        $servicoQuery->bindParam(":servico_id", $reserva["servico_id"], PDO::PARAM_INT);
        $servicoQuery->execute();
        $servico = $servicoQuery->fetch(PDO::FETCH_ASSOC);
        
        if ($servico) {
            $servicoRealizar["tipo_servico"] = $servico["tipo"];
        }

        // Resgatando o nome do status diretamente da tabela 'status'
        $statusQuery = $conn->prepare("SELECT tipo FROM status WHERE id = :status_id;");
        $statusQuery->bindParam(":status_id", $reserva["status_id"], PDO::PARAM_INT);
        $statusQuery->execute();
        $status = $statusQuery->fetch(PDO::FETCH_ASSOC);
        
        if ($status) {
            $servicoRealizar["status"] = $status["tipo"];
        }

        // Adiciona o serviço realizado ao array
        $servicosRealizados[] = $servicoRealizar;
    }

    // Resgatar todos os status para exibição no formulário de atualização
    $statusQuery = $conn->query("SELECT * FROM status;");
    $status = $statusQuery->fetchAll(PDO::FETCH_ASSOC);

    try {
        // Consulta para obter agendamentos com todos os serviços e status de um cliente em uma linha
        $agendamentosQuery = $conn->query("
            SELECT a.id AS agendamento_id, a.data_marcada, a.hora, a.status_id,
                   st.tipo AS status_nome, 
                   c.nome AS nome_cliente,
                   GROUP_CONCAT(s.tipo ORDER BY s.tipo SEPARATOR ', ') AS servicos
            FROM reservas a
            JOIN clientes c ON a.cliente_id = c.id
            JOIN servicos s ON a.servico_id = s.id
            JOIN status st ON a.status_id = st.id  
            GROUP BY a.id, c.nome, a.data_marcada, a.hora, st.tipo;  
        ");
        $agendamentos = $agendamentosQuery->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Erro ao buscar agendamentos: " . $e->getMessage();
    }

} elseif ($method === "POST") {

    // Verificando o tipo de POST
    $type = $_POST["type"];

    // Deletar pedido
    if ($type === "delete") {

        $reservaId = $_POST["id"];

        $deleteQuery = $conn->prepare("DELETE FROM reservas WHERE id = :id;");
        $deleteQuery->bindParam(":id", $reservaId, PDO::PARAM_INT);
        $deleteQuery->execute();

        $_SESSION["msg"] = "Agendamento removido com sucesso!";
        $_SESSION["status"] = "success";

    // Atualizar status do pedido
    } elseif ($type === "update") {

        $reservaId = $_POST["id"];
        $statusId = $_POST["status"];

        $updateQuery = $conn->prepare("UPDATE reservas SET status_id = :status_id WHERE id = :id");
        $updateQuery->bindParam(":id", $reservaId, PDO::PARAM_INT);
        $updateQuery->bindParam(":status_id", $statusId, PDO::PARAM_INT);
        $updateQuery->execute();

        $_SESSION["msg"] = "Agendamento atualizado com sucesso!";
        $_SESSION["status"] = "success";
    }

    // Retorna o usuário para o dashboard
    header("Location: ../dashboard.php");
}
?>
