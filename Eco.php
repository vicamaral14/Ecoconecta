<?php
class Eco {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $senha) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM USUARIO WHERE email = '$email'";
        $res = $this->conn->query($sql);
        if ($res && $u = $res->fetch_assoc()) {
            if (password_verify($senha, $u['senha'])) {
                return $u;
            }
        }
        return false;
    }
    public function buscarUsuario($id) {
    $id = (int)$id;
    $sql = "SELECT id_usuario, nome, email, documento, telefone, endereco, tipo_usuario FROM USUARIO WHERE id_usuario = $id";
    $res = $this->conn->query($sql);
    return ($res && $u = $res->fetch_assoc()) ? $u : false;
}

    public function atualizarPerfil($id, $nome, $email, $tel, $end, $senha = null) {
        $id = (int)$id;
        $nome = $this->conn->real_escape_string($nome);
        $email = $this->conn->real_escape_string($email);
        $tel = preg_replace('/[^0-9]/', '', $tel);
        $end = $this->conn->real_escape_string($end);
        
        $sql = "UPDATE USUARIO SET nome='$nome', email='$email', telefone='$tel', endereco='$end'";
        if (!empty($senha)) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql .= ", senha='$hash'";
        }
        $sql .= " WHERE id_usuario = $id";
        return $this->conn->query($sql);
    }

    public function salvarDoacao($id_user, $mat, $mat_custom, $qtd, $unidade, $end, $lat, $lng, $obs, $id_doacao = null) {
        $latV = ($lat != 'null' && $lat != '') ? "'$lat'" : "NULL";
        $lngV = ($lng != 'null' && $lng != '') ? "'$lng'" : "NULL";
        $id_mat = ($mat === 'outro') ? "NULL" : (int)$mat;
        $custom = ($mat === 'outro') ? "'" . $this->conn->real_escape_string($mat_custom) . "'" : "NULL";
        $obs = $this->conn->real_escape_string($obs);
        $end = $this->conn->real_escape_string($end);

        if ($id_doacao) {
            $sql = "UPDATE DOACAO SET id_material=$id_mat, material_personalizado=$custom, quantidade=$qtd, unidade_medida='$unidade', endereco_manual='$end', latitude=$latV, longitude=$lngV, observacoes='$obs' 
                    WHERE id_doacao = $id_doacao AND id_doador = $id_user AND status_doacao = 'Disponível'";
        } else {
            $sql = "INSERT INTO DOACAO (id_doador, id_material, material_personalizado, quantidade, unidade_medida, endereco_manual, latitude, longitude, observacoes, status_doacao, data_doacao) 
                    VALUES ($id_user, $id_mat, $custom, $qtd, '$unidade', '$end', $latV, $lngV, '$obs', 'Disponível', NOW())";
        }
        return $this->conn->query($sql);
    }

    public function listarMural() {
        $sql = "SELECT D.*, M.nome_material, U.nome as doador_nome, U.telefone 
                FROM DOACAO D 
                LEFT JOIN MATERIAL_RECICLAVEL M ON D.id_material = M.id_material 
                JOIN USUARIO U ON D.id_doador = U.id_usuario 
                WHERE D.status_doacao != 'Coletado' 
                ORDER BY D.data_doacao DESC";
        $res = $this->conn->query($sql);
        $dados = [];
        while($r = $res->fetch_assoc()) { $dados[] = $r; }
        return $dados;
    }

    public function listarMinhasDoacoes($id_user) {
        $sql = "SELECT D.*, M.nome_material FROM DOACAO D LEFT JOIN MATERIAL_RECICLAVEL M ON D.id_material = M.id_material WHERE D.id_doador = $id_user ORDER BY D.data_doacao DESC";
        $res = $this->conn->query($sql);
        $dados = [];
        while($r = $res->fetch_assoc()) { $dados[] = $r; }
        return $dados;
    }

    public function reservarDoacao($id_coletor, $id_doacao, $data_prevista) {
        $data = $this->conn->real_escape_string($data_prevista);
        $sql = "UPDATE DOACAO SET id_coletor = $id_coletor, data_coleta_prevista = '$data', status_doacao = 'Reservado' 
                WHERE id_doacao = $id_doacao AND status_doacao = 'Disponível'";
        return $this->conn->query($sql);
    }

    public function concluirColeta($id_coletor, $id_doacao) {
        $sql = "UPDATE DOACAO SET status_doacao = 'Coletado', data_conclusao_coleta = NOW() 
                WHERE id_doacao = $id_doacao AND id_coletor = $id_coletor";
        return $this->conn->query($sql);
    }

    public function excluirDoacao($id_user, $id_doacao) {
        $sql = "DELETE FROM DOACAO WHERE id_doacao = $id_doacao AND id_doador = $id_user AND status_doacao = 'Disponível'";
        return $this->conn->query($sql);
    }

    public function excluirPerfil($id) {
        $this->conn->query("DELETE FROM DOACAO WHERE id_doador = $id OR id_coletor = $id");
        return $this->conn->query("DELETE FROM USUARIO WHERE id_usuario = $id");
    }

    // --- MÉTODOS DO ADMINISTRADOR ---

    public function listarCategorias() {
        $sql = "SELECT id_material, nome_material FROM MATERIAL_RECICLAVEL ORDER BY nome_material ASC";
        $res = $this->conn->query($sql);
        $dados = [];
        while($r = $res->fetch_assoc()) { $dados[] = $r; }
        return $dados;
    }

    public function salvarMaterial($nome, $id = null) {
        $nome = $this->conn->real_escape_string($nome);
        if ($id) {
            return $this->conn->query("UPDATE MATERIAL_RECICLAVEL SET nome_material='$nome' WHERE id_material=$id");
        }
        return $this->conn->query("INSERT INTO MATERIAL_RECICLAVEL (nome_material) VALUES ('$nome')");
    }

    public function excluirMaterial($id) {
        $id = (int)$id;
        // O banco de dados pode impedir a exclusão se o material estiver em uso (Foreign Key)
        return $this->conn->query("DELETE FROM MATERIAL_RECICLAVEL WHERE id_material=$id");
    }
}
?>
