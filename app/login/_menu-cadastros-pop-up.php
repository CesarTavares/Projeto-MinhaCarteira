<div class="menu">
    <ul>
        <div class="usuario">
            <?php

            if (isset($_SESSION['nome'])) {
                echo "<p style='padding:15px; color:#000; font-weight:bold;'>";
                echo "Usuário: ";
                echo $_SESSION["nome"];
                echo "</p>";
                echo '<span style="margin-right: 40px;"></span>';
            }
            ?>
        </div>
    
        <div class="sair">
            <?php
            if (isset($_SESSION['nivel']) && ($_SESSION['nivel']) == "Administrador") {
                echo '<a href="../../tela-inicial.php"><li>Voltar</li></a>';
                echo '<span style="margin-right: 40px;"></span>';
            } else {
                echo '<a href="../../tela-inicial.php"><li>Voltar</li></a>';
            }
            ?>
        </div>
        <a href="../../tela-inicial.php"></a>
</div>

<script>
    function goBack() {
        // Navega para a página anterior no histórico do navegador
        window.history.back();
    }
</script>