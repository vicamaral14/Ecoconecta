<?php
header('Content-Type: application/json');
include 'conexao.php';
include 'Eco.php';

$eco = new Eco($conn);
$acao = $_POST['acao'] ?? '';

if ($acao == 'login') {
    $res = $eco->login($_POST['email'], $_POST['senha']);
    echo $res ? json_encode($res) : json_encode(["erro" => "E-mail ou senha incorretos."]);
} 
elseif ($acao == 'cadastro') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $doc = preg_replace('/[^0-9]/', '', $_POST['doc']);
    $tel = preg_replace('/[^0-9]/', '', $_POST['tel']);
    $end = $conn->real_escape_string($_POST['end']);
    $tipo = $_POST['tipo']; 
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO USUARIO (nome, email, documento, telefone, endereco, tipo_usuario, senha, status, data_cadastro) 
            VALUES ('$nome', '$email', '$doc', '$tel', '$end', '$tipo', '$senha', 'Ativo', NOW())";
    
    echo $conn->query($sql) ? json_encode(["sucesso" => "✅ Cadastro realizado!"]) : json_encode(["erro" => $conn->error]);
}
elseif ($acao == 'perfil_update') {
    $res = $eco->atualizarPerfil($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['tel'], $_POST['end'], $_POST['senha']);
    echo json_encode($res ? ["sucesso" => "✅ Perfil atualizado!"] : ["erro" => "Erro ao atualizar"]);
}
elseif ($acao == 'perfil_excluir') {
    echo json_encode($eco->excluirPerfil($_POST['id']) ? ["sucesso" => "Conta excluída."] : ["erro" => "Erro"]);
}
elseif ($acao == 'doacao') {
    $id_user = (int)$_POST['id_user'];
    $id_material = ($_POST['id_material'] == 'outro') ? 'NULL' : (int)$_POST['id_material'];
    $mat_custom = $conn->real_escape_string($_POST['material_personalizado']);
    $qtd = $conn->real_escape_string($_POST['quantidade']);
    $unidade = $conn->real_escape_string($_POST['unidade_medida']);
    $end = $conn->real_escape_string($_POST['endereco_manual']);
    $obs = $conn->real_escape_string($_POST['observacoes']);

    $sql = "INSERT INTO DOACAO (id_doador, id_material, material_personalizado, quantidade, unidade_medida, endereco_manual, observacoes, status_doacao, data_publicacao) 
            VALUES ($id_user, $id_material, '$mat_custom', '$qtd', '$unidade', '$end', '$obs', 'Disponível', NOW())";

    if ($conn->query($sql)) {
        echo json_encode(["sucesso" => "✅ Doação registrada!"]);
    } else {
        echo json_encode(["erro" => "Erro no SQL: " . $conn->error]);
    }
    exit;
}
elseif ($acao == 'excluir_doacao') {
    echo json_encode($eco->excluirDoacao($_POST['id_user'], $_POST['id_doacao']) ? ["sucesso" => "Excluído!"] : ["erro" => "Erro"]);
}
elseif ($acao == 'reservar') {
    echo json_encode($eco->reservarDoacao($_POST['id_user'], $_POST['id_doacao'], $_POST['data']) ? ["sucesso" => "Reservado!"] : ["erro" => "Erro"]);
}
elseif ($acao == 'concluir') {
    echo json_encode($eco->concluirColeta($_POST['id_user'], $_POST['id_doacao']) ? ["sucesso" => "Concluído!"] : ["erro" => "Erro"]);
}
elseif ($acao == 'minhas_doacoes') {
    $id_user = (int)$_POST['id_user'];
    // Selecionando os campos corretos para o JavaScript ler
    $sql = "SELECT D.material_personalizado, D.quantidade, D.unidade_medida, 
                   D.endereco_manual, D.status_doacao, M.nome_material 
            FROM DOACAO D 
            LEFT JOIN MATERIAL_RECICLAVEL M ON D.id_material = M.id_material 
            WHERE D.id_doador = $id_user 
            ORDER BY D.id_doacao DESC";
            
    $res = $conn->query($sql);
    $dados = [];
    while($r = $res->fetch_assoc()) { 
        $dados[] = $r; 
    }
    echo json_encode($dados);
    exit;
}
elseif ($acao == 'buscar_mural' || $acao == 'listar_mural') {
    echo json_encode($eco->listarMural());
}
elseif ($acao == 'cadastrar_material') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $sql = "INSERT INTO MATERIAL_RECICLAVEL (nome_material) VALUES ('$nome')";
    echo $conn->query($sql) ? json_encode(["sucesso" => true]) : json_encode(["erro" => $conn->error]);
}
elseif ($acao == 'buscar_materiais' || $acao == 'listar_materiais') {
    $res = $conn->query("SELECT id_material as id, nome_material as nome FROM MATERIAL_RECICLAVEL");
    $dados = [];
    while ($row = $res->fetch_assoc()) {
        $dados[] = $row;
    }
    echo json_encode($dados);
}

elseif ($acao == 'adm_listar_materiais') {
    $res = $conn->query("SELECT id_material, nome_material FROM MATERIAL_RECICLAVEL ORDER BY nome_material ASC");
    $lista = [];
    while($row = $res->fetch_assoc()) { 
        $lista[] = $row; 
    }
    echo json_encode($lista);
    exit;
}

// ROTA: Adicionar Novo Material
elseif ($acao == 'adm_add_material') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $sql = "INSERT INTO MATERIAL_RECICLAVEL (nome_material) VALUES ('$nome')";
    echo $conn->query($sql) ? json_encode(["sucesso" => "✅ Material cadastrado!"]) : json_encode(["erro" => $conn->error]);
    exit;
}

// ROTA: Editar Material Existente
elseif ($acao == 'adm_editar_material') {
    $id = (int)$_POST['id'];
    $nome = $conn->real_escape_string($_POST['nome']);
    $sql = "UPDATE MATERIAL_RECICLAVEL SET nome_material = '$nome' WHERE id_material = $id";
    echo $conn->query($sql) ? json_encode(["sucesso" => "✅ Material atualizado!"]) : json_encode(["erro" => $conn->error]);
    exit;
}

// ROTA: Excluir Material
elseif ($acao == 'adm_excluir_material') {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM MATERIAL_RECICLAVEL WHERE id_material = $id";
    echo $conn->query($sql) ? json_encode(["sucesso" => "✅ Material removido!"]) : json_encode(["erro" => $conn->error]);
    exit;
}
elseif ($acao == 'listar_categorias') {
    echo json_encode($eco->listarCategorias());
}
elseif ($acao == 'salvar_material') {
    $res = $eco->salvarMaterial($_POST['nome'], $_POST['id'] ?? null);
    echo json_encode($res ? ["sucesso" => "Material salvo!"] : ["erro" => "Erro ao salvar material"]);
}
elseif ($acao == 'excluir_material') {
    echo json_encode($eco->excluirMaterial($_POST['id']) ? ["sucesso" => "Removido!"] : ["erro" => "Erro: Material pode estar em uso"]);
}
elseif ($acao == 'reservar_material') {
    $id_doacao = (int)$_POST['id_doacao'];
    $id_coletor = (int)$_POST['id_user'];

    $sql = "UPDATE DOACAO SET id_coletor = $id_coletor, status_doacao = 'Reservado' 
            WHERE id_doacao = $id_doacao AND status_doacao = 'Disponível'";
            
    if ($conn->query($sql) && $conn->affected_rows > 0) {
        echo json_encode(["sucesso" => "✅ Material reservado!"]);
    } else {
        echo json_encode(["erro" => "Material já reservado ou indisponível."]);
    }
    exit;
}
?>