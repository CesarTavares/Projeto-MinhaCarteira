<div class="menu">
    <ul>
        <?php
        if (isset($_SESSION['nome'])) {
            echo "<p style='padding:15px; color:#000; font-weight:bold; font-size: 20px;'>";
            echo "Usuário: ";
            echo $_SESSION["nome"];
            echo "</p>";
        }
        ?>

        <?php
        if (isset($_SESSION['nivel']) && ($_SESSION['nivel']) == "Administrador") {
        ?>

            <div class="menu">
                <li id="menu-cadastros">Cadastros</li>
                <ul id="cadastros" class="submenu" style="display: none;">
                    <li><a href="./app/login/cad-login.php">Cadastrar Novo Usuário</a></li>
                    <li><a href="./app/login/cad-contas.php">Cadastrar Nova Carteira</a></li>
                    <li><a href="./app/login/cad-tipo-contas.php">Cadastrar Novo Tipo de Carteira</a></li>
                    <li><a href="./app/login/cad-categorias.php">Cadastrar Nova Categoria</a></li>
                </ul>
            </div>

            <div class="menu">
                <li id="menu-lancamentos">Lançamentos</li>
                <ul id="lancamentos" class="submenu" style="display: none;">
                    <li><a href="./app/login/cad-lancamento.php">Despesas</a></li>
                    <li><a href="./app/login/cad-lancamento-receita.php">Receitas</a></li>
                </ul>
            </div>

            <div class="menu">
                <li id="menu-consultas">Consultas</li>
                <ul id="consultas" class="submenu" style="display: none;">
                    <li><a href="./app/login/view-login.php">Consulta de Usuários</a></li>
                    <li><a href="./app/login/view-categorias.php">Consulta de Categorias</a></li>
                    <li><a href="./app/login/view-tipo-contas.php">Consulta Tipo de Carteiras</a></li>
                    <li><a href="./app/login/view-contas.php">Consulta de Carteiras</a></li>
                    <li><a href="./app/login/view-lancamento.php">Consulta de Lançamentos</a></li>

                </ul>
            </div>

            <div class="menu">
                <li id="menu-relatorios">Relatórios</li>
                
                <ul id="relatorios" class="submenu" style="display: none;">
                    <li id="menu-relatorios-despesas">Relatórios de Despesas</li>
                    <ul id="relatorios-despesas" class="submenu" style="display: none;">
                        <li><a href="./app/login/report-lancamentos.php">Relatório de Lançamentos de Despesas por Datas</a></li>
                        <li><a href="./app/login/report-lancamentos-categorias.php">Relatório de Lançamentos por Categorias</a></li>
                        <li><a href="./app/login/report-lancamentos-carteiras.php">Relatório de Lançamentos por Carteiras</a></li>
                    </ul>
                    <li id="menu-relatorios-receitas">Relatórios de Receitas</li>
                    <ul id="relatorios-receitas" class="submenu" style="display: none;">
                        <li><a href="./app/login/report-lancamentos-receitas.php">Relatório de Lançamentos de Receitas por Datas</a></li>
                        <li><a href="./app/login/report-lancamentos-receitas-categorias.php">Relatório de Lançamentos de Receitas por Categorias</a></li>
                        <li><a href="./app/login/report-lancamentos-receitas-carteiras.php">Relatório de Lançamentos de Receitas por Carteiras</a></li>
                    </ul>
                </ul>              
            </div>

            <div class="menu">
                <li id="menu-ajuda">Ajuda</li>
                <ul id="ajuda" class="submenu" style="display: none;">
                    <li><a href="./app/login/saiba-mais.php">Perguntas Frequentes</a></li>
                </ul>
            </div>

        <?php
            echo '<a href="././index.php"><li>S A I R</li></a>';
        } else {

        ?>

            <div class="menu">
                <li id="menu-cadastros">Cadastros</li>
                <ul id="cadastros" class="submenu" style="display: none;">
                    <li><a href="./app/login/cad-contas.php">Cadastrar Nova Carteira</a></li>
                    <li><a href="./app/login/cad-tipo-contas.php">Cadastrar Novo Tipo de Carteira</a></li>
                    <li><a href="./app/login/cad-categorias.php">Cadastrar Nova Categoria</a></li>
                </ul>
            </div>

            <div class="menu">
                <li id="menu-lancamentos">Lançamentos</li>
                <ul id="lancamentos" class="submenu" style="display: none;">
                    <li><a href="./app/login/cad-lancamento.php">Despesas</a></li>
                    <li><a href="./app/login/cad-lancamento-receita.php">Receitas</a></li>
                </ul>
            </div>

            <div class="menu">
                <li id="menu-consultas">Consultas</li>
                <ul id="consultas" class="submenu" style="display: none;">
                    <li><a href="./app/login/view-categorias.php">Consulta de Categorias</a></li>
                    <li><a href="./app/login/view-tipo-contas.php">Consulta Tipo de Carteiras</a></li>
                    <li><a href="./app/login/view-contas.php">Consulta de Carteiras</a></li>
                    <li><a href="./app/login/view-lancamento.php">Consulta de Lançamentos</a></li>

                </ul>
            </div>

            <div class="menu">
                <li id="menu-relatorios">Relatórios</li>
                <ul id="relatorios" class="submenu" style="display: none;">                   
                    <li><a href="./app/login/report-lancamentos.php">Relatório de Lançamentos por Datas</a></li>
                    <li><a href="./app/login/report-lancamentos-categorias.php">Relatório de Lançamentos por Categorias</a></li>
                    <li><a href="./app/login/report-lancamentos-carteiras.php">Relatório de Lançamentos por Carteiras</a></li>                    
                </ul>
            </div>

            <div class="menu">
                <li id="menu-ajuda">Ajuda</li>
                <ul id="ajuda" class="submenu" style="display: none;">
                    <li><a href="./app/login/view-lancamento.php">Perguntas Frequentes</a></li>
                    <li><a href="./app/login/view-lancamento.php">Saiba Mais</a></li>
                    <li><a href="./app/login/cad-lancamento.php">Configuração</a></li>
                </ul>
            </div>

        <?php
            echo '<a href="././index.php"><li>S A I R</li></a>';
        }
        ?>
</div>


<script>
    // Função para mostrar o submenu quando o mouse entra no menu
    function mostrarSubMenu(idSubMenu) {
        var submenu = document.getElementById(idSubMenu);
        submenu.style.display = 'block';
    }

    // Função para esconder o submenu quando o mouse sai do menu principal
    function esconderSubMenu(idSubMenu) {
        var submenu = document.getElementById(idSubMenu);
        submenu.style.display = 'none';
    }

    function toggleSubMenu(id) {        
        var submenu = document.getElementById(id);
        if (submenu.style.display === 'none' || submenu.style.display === '') {
            submenu.style.display = 'block';
        } else {
            submenu.style.display = 'none';
        }
    }

    // Adicione eventos mouseenter e mouseleave aos menus principais e submenus
    document.getElementById('menu-cadastros').addEventListener('mouseenter', function() {
        mostrarSubMenu('cadastros');
    });
    document.getElementById('menu-cadastros').addEventListener('mouseleave', function() {
        esconderSubMenu('cadastros');
    })
    document.getElementById('cadastros').addEventListener('mouseenter', function() {
        mostrarSubMenu('cadastros');
        l
    });
    document.getElementById('cadastros').addEventListener('mouseleave', function() {
        esconderSubMenu('cadastros');
    });

    document.getElementById('menu-lancamentos').addEventListener('mouseenter', function() {
        mostrarSubMenu('lancamentos');
    });
    document.getElementById('menu-lancamentos').addEventListener('mouseleave', function() {
        esconderSubMenu('lancamentos');
    });
    document.getElementById('lancamentos').addEventListener('mouseenter', function() {
        mostrarSubMenu('lancamentos');
    });
    document.getElementById('lancamentos').addEventListener('mouseleave', function() {
        esconderSubMenu('lancamentos');
    });

    document.getElementById('menu-consultas').addEventListener('mouseenter', function() {
        mostrarSubMenu('consultas');
    });
    document.getElementById('menu-consultas').addEventListener('mouseleave', function() {
        esconderSubMenu('consultas');
    });
    document.getElementById('consultas').addEventListener('mouseenter', function() {
        mostrarSubMenu('consultas');
    });
    document.getElementById('consultas').addEventListener('mouseleave', function() {
        esconderSubMenu('consultas');
    });

    document.getElementById('menu-relatorios').addEventListener('click', function() {
        toggleSubMenu('relatorios');
    });
   
    document.getElementById('menu-relatorios-despesas').addEventListener('click', function(event) {
        event.stopPropagation();
        toggleSubMenu('relatorios-despesas');
    });    

    document.getElementById('relatorios-despesas').addEventListener('click', function() {
    toggleSubMenu('relatorios-despesas');
    });

    document.getElementById('menu-relatorios-receitas').addEventListener('click', function(event) {
        event.stopPropagation();
        toggleSubMenu('relatorios-receitas');
    });

    document.getElementById('relatorios-receitas').addEventListener('click', function() {
        toggleSubMenu('relatorios-receitas');
    });

    document.getElementById('menu-ajuda').addEventListener('mouseenter', function() {
        mostrarSubMenu('ajuda');
    });
    document.getElementById('menu-ajuda').addEventListener('mouseleave', function() {
        esconderSubMenu('ajuda');
    });
    document.getElementById('ajuda').addEventListener('mouseenter', function() {
        mostrarSubMenu('ajuda');
    });
    document.getElementById('ajuda').addEventListener('mouseleave', function() {
        esconderSubMenu('ajuda');
    });    

</script>