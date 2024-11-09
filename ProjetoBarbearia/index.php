<?php
    include_once("templates/header.php");
    include_once("process/servicos.php");
?>
    <div id="main-banner">
        <h1>Faça seu agendamento</h1>
    </div>
    <div id="main-container">
        <div class="container">
            <div class="row">
              <div class="col-md-12">
                <h2>Preencha as informações:</h2>
                <form action="process/servicos.php" method="POST" id="servico-form">
                    <div class="form-group">
                        <label for="nome">Digite seu nome:</label>
                        <input type="text" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="nome">Digite seu email:</label>
                        <input type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="nome">Digite seu telefone:</label>
                        <input type="tel" name="telefone">
                    </div>
                    <div class="form-group">
                        <label for="servicos">Selecione os serviços:</label>
                        <select multiple name="servicos[]" id="servicos" class="form-control">
                            <?php foreach($servicos as $servico): ?>
                            <option value="<?= $servico["id"] ?>"><?= $servico["tipo"]?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="horario">Agende seu horário:</label>
                        <input type="date" name="data" required>
                        <input type="time" name="hora" required>
                    </div>
                    <div class="form-group">
                        <input type="Submit" class="btn btn-primary" value="Fazer Reserva!">
                    </div>
                </form>
              </div>  
            </div>
        </div>
    </div>
<?php
    include_once("templates/footer.php");
?>
