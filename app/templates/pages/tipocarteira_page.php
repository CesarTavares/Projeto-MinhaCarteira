
<div class="centralizar-v">

    <div class="centralizar-h">

        <h3>Adicionar Novo Tipo de Carteira</h3>
        <br>
        <form action="tipo_carteira-cad-bd.php" method="post">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required>
            <input type="hidden" name="codigo_usuario" value="<?= htmlspecialchars($codigo); ?>">
            <br><br>
            <button type="submit" class="btn">Adicionar Tipo de Carteira</button>
        </form>

        <?php if (count($tipos) === 0): ?>

            <h3>Nenhum tipo de carteira encontrado.</h3>
            <br>

        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tipos as $tipo): ?>
                        <tr>
                            <td><?= htmlspecialchars($tipo['codigo']) ?></td>
                            <td><?= htmlspecialchars($tipo['descricao']) ?></td>
                            <td>
                                <a href="./editar.php?codigo=<?= htmlspecialchars($tipo['codigo']) ?>">Editar</a>
                                <a href="./excluir.php?codigo=<?= htmlspecialchars($tipo['codigo']) ?>">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>