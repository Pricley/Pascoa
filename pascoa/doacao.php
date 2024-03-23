
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páscoa Solidária - Doação</title>
</head>
<body>
    <?php 
    // Verifica a opção escolhida
    if (isset($_GET['op'])){  // verifica se a operação foi definida no GET
        $op = $_GET['op'];
    } else {    
        if (isset($_POST['op'])){ // verifica se a operação foi definida no POST
            $op = $_POST['op'];
        } else { // se a operação não foi definida estabelece op=ld (listar doaçao) como padrão
            $op='ld';
        }
    }

    // Conexão com o banco de dados
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $database = "bd_pascoa";

    $conexao = mysqli_connect($servidor, $usuario, $senha, $database);

    // ir - Inserir criança
    if ($op=='ic'){
        ?>
        <form method="post" action="crianca.php">
            <input type="hidden" name = "op" value="sc">  <!-- operação de salvar criança -->
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome"><br>
            <label for="idade">Idade:</label><br>
            <input type="text" id="idade" name="idade"><br>
            <label for="genero">Gênero:</label><br>
            <input type="text" id="genero" name="genero"><br>

            <label for="responsavel">Responsável:</label><br>
            <select id="responsavel" name="responsavel">
            <?php // pesquisa responsável e preenche o select do formulário
            $sql = "SELECT id,nome FROM tb_responsavel";
            $resultado = mysqli_query($conexao, $sql);
            while ($responsavel = mysqli_fetch_array($resultado)){
                echo "<option value=$responsavel[id]>$responsavel[nome]</option>";
            }
            ?>
            </select><br>

            <label for="presente">Presente:</label><br>
            <select id="presente" name="presente">
            <?php // pesquisa o presente e preenche o select do formulário
            $sql = "SELECT id,nome FROM tb_presente";
            $resultado = mysqli_query($conexao, $sql);
            while ($presente = mysqli_fetch_array($resultado)){
                echo "<option value=$presente[id]>$presente[nome]</option>";
            }
            ?>
            </select><br>

            <input type="submit" value="Enviar"></p>
        </form>
        <?php
    }

    // sr - Salvar criança
    if ($op=='sc'){
        $nome = $_POST["nome"];
        $idade = $_POST["idade"];
        $genero = $_POST["genero"];
        $responsavel = $_POST["responsavel"];
        $presente = $_POST["presente"];
        
        $sql = "INSERT INTO tb_crianca (nome,idade,genero,id_responsavel,id_presente) ". 
               "VALUES ('$nome','$idade','$genero','$responsavel','$presente')";
        // echo $sql;

        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados inseridos com sucesso";
        } else {
            echo "<p>Erro ao inserir dados";
        }
    }

    // er - Editar criança
    if ($op=='ed'){
        $id = $_GET["id"];
        $sql = "SELECT id,id_presente,id_padrinho,quantidade FROM tb_doacao WHERE id=$id";
        // echo $sql;
        $resultado = mysqli_query($conexao,$sql);
        $doacao = mysqli_fetch_array($resultado);
        ?>
        <form method="post" action="doacao.php">
            <input type="hidden" name = "op" value="ad">  <!-- operação de atualizar doacao -->
            <input type="hidden" name = "id" value=<?php echo $id ?>>
            <label for="nome">ID:</label><br>
            <input type="text" id="id" name="id" value=<?php echo $doacao['id'] ?>><br>
            <label for="idade">Presente:</label><br>
            <input type="text" id="id_presente" name="presente" value=<?php echo $doacao['presente'] ?>><br>
            <label for="genero">Padrinho:</label><br>
            <input type="text" id="id_padrinho" name="padrinho" value=<?php echo $doacao['padrinho'] ?>><br>
            <label for="doacao">Quantidade:</label><br>
            <input type="text" id="quantidade" name="quantidade" value=<?php echo $doacao['quantidade'] ?>><br>
            <label for="presente">e-mail:</label><br>
            <input type="submit" value="Enviar"></p>
        </form>
        <?php        
    }

    // ar - Atualizar responsável
    if ($op=='ar'){
        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        
        $sql = "UPDATE tb_responsavel SET nome='$nome',telefone='$telefone',email='$email' WHERE id=$id";
        // echo $sql;
        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados inseridos com sucesso";
        } else {
            echo "<p>Erro ao inserir dados";
        }
    }
    
    // dd - Excluir (deletar) doação
    if ($op=='dd'){
        $id = $_GET['id'];
        $sql = "DELETE FROM tb_doacao WHERE id = $id";
        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados excluídos com sucesso";
        } else {
            echo "<p>Erro ao excluir dados";
        }
    }


    // ld - Listar doação
    if ($op=='ld'){
        $sql =  "SELECT tb_doacao.id, ".
                        "tb_padrinho.nome, ".
                        "tb_presente.nome AS presente, ".
                        "tb_doacao.quantidade ".
                "FROM tb_doacao, tb_padrinho, tb_presente ".
                "WHERE id_padrinho = tb_padrinho.id AND id_presente = tb_presente.id";
        echo $sql;

        $resultado = mysqli_query($conexao, $sql);
        
        echo "</p>doação";
        echo "<table>";
        while ($doacao = mysqli_fetch_array($resultado)){
            echo "<tr>";
            echo "<td>$doacao[id]</td>";
            echo "<td>$doacao[presente]</td>";
            echo "<td>$doacao[nome]</td>";
            echo "<td>$doacao[quantidade]</td>";
            echo "<td><a href='doacao.php?op=ed&id=$doacao[id]'>[Editar]</td>";
            echo "<td><a href='doacao.php?op=dd&id=$doacao[id]'>[Excluir]</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='crianca.php?op=ic'>Incluir uma criança</a>";
    }

    // Fecha a conexão com o banco
    mysqli_close($conexao);

    ?>
    <p><a href="index.php">Voltar para a página principal</a>
</body>
</html>
