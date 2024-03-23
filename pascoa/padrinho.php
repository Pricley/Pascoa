<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páscoa Solidária - Padrinho</title>
</head>
<body>
    <?php 
    // Verifica a opção escolhida
    if (isset($_GET['op'])){  // verifica se a operação foi definida no GET
        $op = $_GET['op'];
    } else {    
        if (isset($_POST['op'])){ // verifica se a operação foi definida no POST
            $op = $_POST['op'];
        } else { // se a operação não foi definida estabelece op=lp (listar padrinho) como padrão
            $op='lp';
        }
    }

    // Conexão com o banco de dados
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $database = "bd_pascoa";

    $conexao = mysqli_connect($servidor, $usuario, $senha, $database);

    // ip - Inserir padrinho
    if ($op=='ip'){
        ?>
        <form method="post" action="padrinho.php">
            <input type="hidden" name = "op" value="sp">  <!-- operação de salvar padrinho -->
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome"><br>
            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone"><br>
            <label for="email">e-mail:</label><br>
            <input type="text" id="email" name="email"><br>
            <input type="submit" value="Enviar"></p>
        </form>
        <?php
    }

    // sp - Salvar padrinho
    if ($op=='sp'){
        $nome = $_POST["nome"];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        
        $sql = "INSERT INTO tb_padrinho (nome,telefone,email) VALUES ('$nome','$telefone','$email')";
        
        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados inseridos com sucesso";
        } else {
            echo "<p>Erro ao inserir dados";
        }
    }

    // ep - Editar padrinho
    if ($op=='ep'){
        $id = $_GET["id"];
        $sql = "SELECT nome,telefone,email FROM tb_padrinho WHERE id=$id";
        // echo $sql;
        $resultado = mysqli_query($conexao,$sql);
        $padrinho = mysqli_fetch_array($resultado);
        ?>
        <form method="post" action="padrinho.php">
            <input type="hidden" name = "op" value="ap">  <!-- operação de atualizar padrinho -->
            <input type="hidden" name = "id" value=<?php echo $id ?>>
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value=<?php echo $responsavel['nome'] ?>><br>
            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" value=<?php echo $responsavel['telefone'] ?>><br>
            <label for="email">e-mail:</label><br>
            <input type="text" id="email" name="email" value=<?php echo $responsavel['email'] ?>><br>
            <input type="submit" value="Enviar"></p>
        </form>
        <?php        
    }

    // ap - Atualizar padrinho
        if ($op=='ap'){
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $telefone = $_POST["telefone"];
            $email = $_POST["email"];
            
            $sql = "UPDATE tb_padrinho SET nome='$nome',telefone='$telefone',email='$email' WHERE id=$id";
            // echo $sql;
            if(mysqli_query($conexao, $sql)){
                echo "<p>Dados inseridos com sucesso";
            } else {
                echo "<p>Erro ao inserir dados";
            }
        }
    
    // dp - Excluir (deletar) padrinho
    if ($op=='dp'){
        $id = $_GET['id'];
        $sql = "DELETE FROM tb_padrinho WHERE id = $id";
        if(mysqli_query($conexao, $sql)){
            echo "<p>Dados excluídos com sucesso";
        } else {
            echo "<p>Erro ao excluir dados";
        }
    }


    // lp - Listar padrinho
    if ($op=='lp'){
        $sql =  "SELECT id,nome,telefone,email ".
                "FROM tb_padrinho ";
        // echo $sql;

        $resultado = mysqli_query($conexao, $sql);
        
        echo "</p>Padrinho";
        echo "<table>";
        while ($padrinho = mysqli_fetch_array($resultado)){
            echo "<tr>";
            echo "<td>$padrinho[id]</td>";
            echo "<td>$padrinho[nome]</td>";
            echo "<td>$padrinho[telefone]</td>";
            echo "<td>$padrinho[email]</td>";
            echo "<td><a href='padrinho.php?op=er&id=$padrinho[id]'>[Editar]</td>";
            echo "<td><a href='padrinho.php?op=dr&id=$padrinho[id]'>[Excluir]</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='padrinho.php?op=ip'>Incluir padrinho</a>";
    }

    // Fecha a conexão com o banco
    mysqli_close($conexao);

    ?>
    <p><a href="index.php">Voltar para a página principal</a>
</body>
</html>