<?php 
    require_once('classePessoa.php');
    $p = new Pessoa("crudpdo", "localhost", "root", "");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Pessoa</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <?php 
        if (isset($_POST['nome'])) { // clicou no botao cadastrar ou editar
            // Editar
            if (isset($_GET['id_up']) && !empty($_GET['id_up'])) 
            {
                $id_update = addslashes($_GET['id_up']);
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);

                if (!empty($nome) && !empty($telefone) && !empty($email)) {
                    if (!$p->atualizarDados($id_update, $nome, $telefone, $email)){
                    ?>
                        <div class="aviso">
                            <h4>Email ja cadastrado!</h4>
                        </div>
                    
                    <?php
                    }
                } else {
                    ?>
                        <div class="aviso">
                            <img src="erro.png">
                            <h4>Preencha todos os campos!</h4>
                        </div>
              
                    <?php
                }
            } // Cadastrar
            else {
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
                if (!empty($nome) && !empty($telefone) && !empty($email)) {
                    $p->cadastrarPessoa($nome, $telefone, $email);
                    header("location: index.php");
                } else {
                ?>
                    <div class="aviso">
                        <img src="erro.png">
                        <h4>Preencha todos os campos!</h4>
                    </div>
              
                <?php
                }
            }
        }
    ?>

    <?php 
        if (isset($_GET['id_up'])) { // se o usuario clicar em editar
            $id_update = addslashes($_GET['id_up']);
            $res = $p->buscarDadosPessoa($id_update);
        }
    ?>
    <section id="esquerda">
        <form method="POST">
            <h2>Cadastrar Pessoa</h2>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome"
            value="<?php if(isset($res)){echo $res['nome'];}?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if (isset($res)){echo $res['telefone'];} ?>">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php if (isset($res)) {echo $res['email'];}?>">
            <input type="submit" value="<?php if(isset($res)){echo "Atualizar";}else{echo "Cadastrar";}?>">            
        </form>
    </section>
    <section id="direita">
        <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td>E-mail</td>
                <td>Ações</td>
            </tr>
    <?php 
        $dados = $p->buscarDados();
        if (count($dados) > 0) { // se existir dados na tabela pessoa
            for ($i=0; $i < count($dados); $i++) 
            {
                echo "<tr>"; 
                foreach ($dados[$i] as $k => $v) 
                {
                    if ($k != "id") {
                        echo "<td>".$v."</td>";
                    }
                }
                ?>
                <td>
                    <a href="index.php?id_up=<?php echo $dados[$i]['id'];?>">Editar</a>
                    <a href="index.php?id=<?php echo $dados[$i]['id'];?>">Excluir</a>
                </td>
                <?php 
                echo "</tr>";
            } 
        } else { // se não existe dados na tabela pessoa
        ?>
        </table>
            <div class="aviso">
                <h4>Não existe nenhum Cadastro!</h4>
            </div>
        <?php } ?>
    </section>
</body>
</html>

<?php 
    if (isset($_GET['id'])) {
        $id_pessoa = addslashes($_GET['id']);
        $p->excluirPessoa($id_pessoa);
        header("location: index.php");
    }
?>