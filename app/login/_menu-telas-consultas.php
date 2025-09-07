<link rel="stylesheet" href="stilo.css">

<div class="menu">
            <ul>
                <div class="usuario">
                    <?php
                        if (isset($_SESSION['nome'])) {
                            echo "<p style='padding:15px; color:#000; font-weight:bold; font-size: 20px;'>";
                                echo "Usuário: ";
                                echo $_SESSION["nome"];
                            echo "</p>";
                        }  
                    ?>
                    <!-- <a href="./cad-login.php"><li>Cadastro de Usuário</li></a>  -->
                    <?php
                        if (isset($_SESSION['nivel']) && ($_SESSION['nivel'])=="Administrador") {
                            ?>
                </div>
                    
                    <!-- <div class="menu">
                        <li id="menu-usuarios">Usuários</li>
                            <ul id="usuarios" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-login.php">Consulta de Usuários</a></li>
                            </ul>
                    </div>
                    
                    <div class="menu">
                        <li id="menu-contas">Contas</li>
                            <ul id="contas" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-contas.php">Consulta de Contas</a></li>
                                <li><a href="./app/login/cad-contas.php">Cadastrar Conta</a></li>
                            </ul>
                    </div>

                    <div class="menu">
                        <li id="menu-lancamento">Lançamentos</li>
                            <ul id="lancamento" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Consulta de Lançamentos</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Inserir novo lançamento</a></li>
                            </ul>
                    </div>

                    <div class="menu">
                        <li id="menu-categoria">Categorias de Despesa/Receita</li>
                            <ul id="categoria" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-categorias.php">Consulta de Categoria Receita</a></li>
                                <li><a href="./app/login/cad-categorias.php">Inserir Categoria Receita</a></li>
                                <li><a href="./app/login/view-categorias.php">Consulta de Categoria Despesa</a></li>
                                <li><a href="./app/login/cad-categorias.php">Inserir Categoria Despesa</a></li>
                            </ul>
                    </div> -->

                    <!-- <div class="menu">
                        <li id="menu-relatorios">Relatórios</li>
                            <ul id="relatorio" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Despesas por Categoria</a></li>
                                <li><a href="./app/login/view-lancamento.php">Despesas por Conta</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Despesas Mensais</a></li>
                                <li><a href="./app/login/view-lancamento.php">Receitas/Despesas por Período</a></li>
                            </ul>
                    </div>

                    <div class="menu">
                        <li id="menu-ajuda">Ajuda</li>
                            <ul id="ajuda" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Perguntas Frequentes</a></li>
                                <li><a href="./app/login/view-lancamento.php">Saiba Mais</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Configuração</a></li>
                            </ul>
                    </div> -->
                    
                        <?php 
                            //echo '<a href="././index.php"><li>S A I R</li></a>';
                    } 
                    else{

                        ?> 
                       
                        <!-- <div class="menu">
                            <li onmouseover="mostrarSubMenu('contas')">Contas</li>
                            <ul id="contas" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-contas.php">Consulta de Contas</a></li>
                                <li><a href="./app/login/cad-contas.php">Cadastrar Conta</a></li>
                            </ul>
                        </div>
    
                        <div class="menu">
                            <li onmouseover="mostrarSubMenu('lancamento')">Lançamentos</li>
                            <ul id="lancamento" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Consulta de Lançamentos</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Inserir novo lançamento</a></li>
                            </ul>
                        </div>
    
                        <div class="menu">
                            <li onmouseover="mostrarSubMenu('categoria')">Categorias de Despesa/Receita</li>
                            <ul id="categoria" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-categorias.php">Consulta de Categoria Receita</a></li>
                                <li><a href="./app/login/cad-categorias.php">Inserir Categoria Receita</a></li>
                                <li><a href="./app/login/view-categorias.php">Consulta de Categoria Despesa</a></li>
                                <li><a href="./app/login/cad-categorias.php">Inserir Categoria Despesa</a></li>
                            </ul>
                        </div>
    
                        <div class="menu">
                            <li onmouseover="mostrarSubMenu('relatorios')">Relatórios</li>
                            <ul id="relatorios" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Despesas por Categoria</a></li>
                                <li><a href="./app/login/view-lancamento.php">Despesas por Conta</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Despesas Mensais</a></li>
                                <li><a href="./app/login/view-lancamento.php">Receitas/Despesas por Período</a></li>
                            </ul>
                        </div>
    
                        <div class="menu">
                            <li onmouseover="mostrarSubMenu('ajuda')">Ajuda</li>
                            <ul id="ajuda" class="submenu" style="display: none;">
                                <li><a href="./app/login/view-lancamento.php">Perguntas Frequentes</a></li>
                                <li><a href="./app/login/view-lancamento.php">Saiba Mais</a></li>
                                <li><a href="./app/login/cad-lancamento.php">Configuração</a></li>
                            </ul>
                        </div> -->
                        
                            <?php 
                             //echo '<a href="././index.php"><li>S A I R</li></a>';
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

    // Adicione eventos mouseenter e mouseleave aos menus principais e submenus
    document.getElementById('menu-usuarios').addEventListener('mouseenter', function () {
        mostrarSubMenu('usuarios');
    });
    document.getElementById('menu-usuarios').addEventListener('mouseleave', function () {
        esconderSubMenu('usuarios');
    });
    document.getElementById('usuarios').addEventListener('mouseenter', function () {
        mostrarSubMenu('usuarios');
    });
    document.getElementById('usuarios').addEventListener('mouseleave', function () {
        esconderSubMenu('usuarios');
    });

    document.getElementById('menu-contas').addEventListener('mouseenter', function () {
        mostrarSubMenu('contas');
    });
    document.getElementById('menu-contas').addEventListener('mouseleave', function () {
        esconderSubMenu('contas');
    });
    document.getElementById('contas').addEventListener('mouseenter', function () {
        mostrarSubMenu('contas');
    });
    document.getElementById('contas').addEventListener('mouseleave', function () {
        esconderSubMenu('contas');
    });

    document.getElementById('menu-lancamento').addEventListener('mouseenter', function () {
        mostrarSubMenu('lancamento');
    });
    document.getElementById('menu-lancamento').addEventListener('mouseleave', function () {
        esconderSubMenu('lancamento');
    });
    document.getElementById('lancamento').addEventListener('mouseenter', function () {
        mostrarSubMenu('lancamento');
    });
    document.getElementById('lancamento').addEventListener('mouseleave', function () {
        esconderSubMenu('lancamento');
    });

    document.getElementById('menu-categoria').addEventListener('mouseenter', function () {
        mostrarSubMenu('categoria');
    });
    document.getElementById('menu-categoria').addEventListener('mouseleave', function () {
        esconderSubMenu('categoria');
    });
    document.getElementById('categoria').addEventListener('mouseenter', function () {
        mostrarSubMenu('categoria');
    });
    document.getElementById('categoria').addEventListener('mouseleave', function () {
        esconderSubMenu('categoria');
    });

    document.getElementById('menu-relatorios').addEventListener('mouseenter', function () {
        mostrarSubMenu('relatorio');
    });
    document.getElementById('menu-relatorios').addEventListener('mouseleave', function () {
        esconderSubMenu('relatorio');
    });
    document.getElementById('relatorio').addEventListener('mouseenter', function () {
        mostrarSubMenu('relatorio');
    });
    document.getElementById('relatorio').addEventListener('mouseleave', function () {
        esconderSubMenu('relatorio');
    });

    document.getElementById('menu-ajuda').addEventListener('mouseenter', function () {
        mostrarSubMenu('ajuda');
    });
    document.getElementById('menu-ajuda').addEventListener('mouseleave', function () {
        esconderSubMenu('ajuda');
    });
    document.getElementById('ajuda').addEventListener('mouseenter', function () {
        mostrarSubMenu('ajuda');
    });
    document.getElementById('ajuda').addEventListener('mouseleave', function () {
        esconderSubMenu('ajuda');
    });

    // Repita os eventos para outros menus principais e submenus
</script>