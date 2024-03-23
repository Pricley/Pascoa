
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páscoa Solidária - Crianças</title>
</head>
<body>
    <?php 
    // Verifica a opção escolhida
    if (isset($_GET['op'])){  // verifica se a operação foi definida no GET
        $op = $_GET['op'];
    } else {    
        if (isset($_POST['op'])){ // verifica se a operação foi definida no POST
            $op = $_POST['op'];
        } else { // se a operação não foi definida estabelece op=lc (listar crianças) como padrão
            $op='lc';
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

    // er - Editar responsável
    if ($op=='er'){
        $id = $_GET["id"];
        $sql = "SELECT nome,telefone,email FROM tb_responsavel WHERE id=$id";
        // echo $sql;
        $resultado = mysqli_query($conexao,$sql);
        $responsavel = mysqli_fetch_array($resultado);
        ?>
        <form method="post" action="responsavel.php">
            <input type="hidden" name = "op" value="ar">  <!-- operação de atualizar responsavel -->
            <input type="hidden" name = "id" value=<?php echo $id ?>>
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value=<?php echo $responsavel['nome'] ?>><br>
            <label for="idade">Telefone:</label><br>
            <input type="text" id="idade" name="idade" value=<?php echo $responsavel['idade'] ?>><br>
            <label for="genero">e-mail:</label><br>
            <input type="text" id="genero" name="genero" value=<?php echo $responsavel['genero'] ?>><br>
            <label for="responsavel">e-mail:</label><br>
            <input type="text" id="responsavel" name="responsavel" value=<?php echo $responsavel['responsavel'] ?>><br>
            <label for="presente">e-mail:</label><br>
            <input type="text" id="presente" name="presente" value=<?php echo $responsavel['presente'] ?>><br>
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
    
    // dr - Excluir (deletar) responsável
    if ($op=='dr'){
        $id = $_GET['id'];
        $sql = "DELETE FROM tb_responsavel WHERE id = $id";
        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados excluídos com sucesso";
        } else {
            echo "<p>Erro ao excluir dados";
        }
    }


    // lc - Listar crianças
    if ($op=='lc'){
        $sql =  "SELECT c.id, c.nome, c.idade, c.genero, r.nome AS responsavel, p.nome AS presente ".
                "FROM tb_crianca c, tb_responsavel r, tb_presente p ".
                "WHERE c.id_responsavel = r.id  AND c.id_presente = p.id";
        // echo $sql;

        $resultado = mysqli_query($conexao, $sql);
        
        echo "</p>Crianças";
        echo "<table>";
        while ($crianca = mysqli_fetch_array($resultado)){
            echo "<tr>";
            echo "<td>$crianca[id]</td>";
            echo "<td>$crianca[nome]</td>";
            echo "<td>$crianca[idade]</td>";
            echo "<td>$crianca[genero]</td>";
            echo "<td>$crianca[responsavel]</td>";
            echo "<td>$crianca[presente]</td>";
            echo "<td><a href='crianca.php?op=ec&id=$crianca[id]'>[Editar]</td>";
            echo "<td><a href='crianca.php?op=dc&id=$crianca[id]'>[Excluir]</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='crianca.php?op=ic'>Incluir crianca</a>";
    }

    // Fecha a conexão com o banco
    mysqli_close($conexao);

    ?>
    <p><a href="index.php">Voltar para a página principal</a>
</body>
</html>
