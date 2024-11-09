<?php
    include_once("templates/header.php");
    include_once("process/reservas.php"); // Inclui a consulta aos agendamentos
?>
<div id="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Gerenciar agendamentos:</h2>
            </div>
            <div class="col-md-12 table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"><span>Agendamento</span> #</th>
                            <th scope="col"><span>Nome</span></th>
                            <th scope="col"><span>Data</span></th>
                            <th scope="col"><span>Horário</span></th>
                            <th scope="col"><span>Serviços</span></th>
                            <th scope="col"><span>Status</span></th>
                            <th scope="col"><span>Ações</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($agendamentos)): ?>
                            <?php foreach ($agendamentos as $agendamento): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($agendamento['agendamento_id']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['nome_cliente']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['data_marcada']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['hora']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['servicos']) ?></td> <!-- Lista de serviços concatenados -->
                                    <td>
                                    <form action="process/reservas.php" method="POST" class="form-group update-form">
                                        <input type="hidden" name="type" value="update">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($agendamento['agendamento_id']) ?>">
                                        <select name="status" class="form-control status-input">
                                        <?php foreach($status as $s): ?>
                                            <option value="<?= $s['id'] ?>" <?php echo ($s['id'] == $agendamento['status_id']) ? "selected" : ""; ?>>
                                                <?= htmlspecialchars($s['tipo']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>


                                    </td>
                                    <td>
                                        <form action="process/reservas.php" method="POST">
                                            <input type="hidden" name="type" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($agendamento['agendamento_id']) ?>">
                                            <button type="submit" class="delete-btn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Nenhum agendamento encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    include_once("templates/footer.php");
?>

