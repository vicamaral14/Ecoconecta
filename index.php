<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoConecta - Ibirubá</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/imask"></script>
    <style>
        :root {
            --bg-body: #f4f7f6;
            --card-bg: #ffffff;
            --text: #333333;
            --border: #dddddd;
            --input-bg: #ffffff;
            --primary: #2e7d32;
            --secondary: #1976d2;
            --accent: #ef6c00;
            --danger: #d32f2f;
        }

        body.dark-theme {
            --bg-body: #121212;
            --card-bg: #1e1e1e;
            --text: #e0e0e0;
            --border: #333333;
            --input-bg: #2d2d2d;
        }

        body { background-color: var(--bg-body); color: var(--text); transition: 0.3s; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 650px; margin: auto; }
        .hidden { display: none !important; }
        
        .card-eco { background: var(--card-bg); padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin-top: 20px; border: 1px solid var(--border); }
        
        input, select, textarea { 
            width: 100%; padding: 12px; margin: 8px 0; border-radius: 8px; box-sizing: border-box;
            border: 1px solid var(--border); background: var(--input-bg); color: var(--text); font-size: 16px;
        }

        button { width: 100%; padding: 14px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; transition: 0.2s; margin-top: 10px; color: white; background: var(--primary); }
        button:hover { opacity: 0.85; transform: translateY(-1px); }
        
        .header-titulo-logos { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .logo { height: 50px; object-fit: contain; }

        .item-categoria, .item-mural { padding: 12px; border-bottom: 1px solid var(--border); background: var(--card-bg); margin-bottom: 10px; border-radius: 8px; }
        .btn-mini { width: auto; padding: 5px 10px; margin: 0 2px; font-size: 12px; }
        
        .tab-container { display: flex; gap: 5px; margin-bottom: 10px; }
        .tab-btn { background: #eee; color: #333; padding: 8px; font-size: 12px; flex: 1; border: none; cursor: pointer; border-radius: 4px; }
        .tab-btn.active { background: var(--primary); color: white; }

        .spinner { display: none; width: 15px; height: 15px; border: 2px solid #ffffff66; border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite; margin-right: 8px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        button.loading .spinner { display: inline-block; }

        /* Estilos específicos do Mural */
        .btn-acao { display: inline-flex; align-items: center; justify-content: center; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px; margin-top: 10px; margin-right: 5px; color: white !important; }
        .btn-mapa { background-color: var(--secondary); }
        .btn-whats { background-color: var(--primary); }
    </style>
</head>
<body>

<div class="container">
    <header class="header-titulo-logos">
        <img src="images/CC.png" alt="EcoConecta" class="logo">
        <h1 style="font-size: 1.4rem; margin: 0; color: var(--primary);">EcoConecta</h1>
        <img src="images/IFRS.png" alt="IFRS" class="logo">
    </header>

    <div id="tela-login" class="card-eco">
        <div style="text-align: right;"><button onclick="toggleDarkMode()" style="width:auto; padding:5px 12px; font-size:12px; background:#666">🌓 Tema</button></div>
        <h2 style="text-align:center">Acessar Sistema</h2>
        <input type="email" id="l_email" placeholder="Seu e-mail">
        <input type="password" id="l_senha" placeholder="Sua senha">
        <button id="btn-login" onclick="fazerLogin()"><span class="spinner"></span> Entrar</button>
        <p style="text-align:center; font-size:14px;">Ainda não participa? <a href="javascript:void(0)" onclick="alternar('tela-login', 'tela-cadastro')" style="color: var(--primary); font-weight: bold;">Cadastre-se</a></p>
    </div>

    <div id="tela-cadastro" class="card-eco hidden">
        <h2 style="text-align:center">Novo Cadastro</h2>
        <input type="text" id="c_nome" placeholder="Nome Completo">
        <input type="email" id="c_email" placeholder="E-mail">
        <input type="text" id="c_doc" placeholder="CPF ou CNPJ">
        <input type="text" id="c_tel" placeholder="WhatsApp">
        <input type="text" id="c_end" placeholder="Endereço">
        <select id="c_tipo">
            <option value="Doador">Quero ser Doador</option>
            <option value="Coletor">Quero ser Coletor</option>
            <option value="Admin">Administrador do Sistema</option> 
        </select>
        <input type="password" id="c_senha" placeholder="Senha">
        <button id="btn-cadastro" onclick="executarCadastro()"><span class="spinner"></span> Finalizar Cadastro</button>
        <p style="text-align:center;"><a href="javascript:void(0)" onclick="alternar('tela-cadastro', 'tela-login')" style="color: #666;">Voltar ao Login</a></p>
    </div>

    <div id="tela-admin" class="card-eco hidden">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0; color: var(--secondary);">Painel Admin</h2>
            <button onclick="location.reload()" style="background:var(--danger); width:auto; padding:8px 15px;">Sair</button>
        </div>
        <h3>Gerenciar Categorias</h3>
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" id="adm_nome_material" placeholder="Novo material (Ex: Vidro)">
            <button onclick="salvarMaterial()" style="width: auto; padding: 0 20px;">Add</button>
        </div>
        <div id="lista-materiais-admin" style="background: var(--bg-body); border-radius: 8px; border: 1px solid var(--border);"></div>
    </div>

    <div id="tela-doador" class="card-eco hidden">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="boas-vindas" style="margin:0"></h2>
            <button onclick="togglePerfil()" style="width:auto; background:var(--secondary); padding: 8px 15px;">⚙️ Perfil</button>
        </div>

        <div id="area-perfil" class="hidden">
            <h3>Meus Dados</h3>
            <input type="text" id="p_nome" placeholder="Nome">
            <input type="email" id="p_email" placeholder="E-mail">
            <input type="text" id="p_tel" placeholder="WhatsApp">
            <input type="text" id="p_end" placeholder="Endereço">
            <button onclick="alert('Funcionalidade de salvar perfil a ser implementada')" style="background:var(--primary)">Salvar Alterações</button>
            <button onclick="togglePerfil()" style="background:#888">Voltar</button>
        </div>

        <div id="conteudo-doador">
            <h3>O que você deseja doar?</h3>
            <select id="d_mat" onchange="toggleMaterialCustom()"></select>
            <input type="text" id="d_mat_custom" placeholder="Qual material?" class="hidden">
            
            <div style="display:flex; gap:10px;">
                <input type="number" id="d_qtd" placeholder="Quantidade" style="flex:2">
                <select id="d_unidade" style="flex:1">
                    <option value="kg">kg</option>
                    <option value="un">un</option>
                </select>
            </div>

            <label style="font-weight:bold;">Local de Coleta:</label>
            <div class="tab-container">
                <button id="tab-perfil" class="tab-btn active" onclick="mudarModoEnd('perfil')">Perfil</button>
                <button id="tab-manual" class="tab-btn" onclick="mudarModoEnd('manual')">Manual</button>
                <button id="tab-gps" class="tab-btn" onclick="mudarModoEnd('gps')">GPS</button>
            </div>
            
            <div id="form-perfil"><input type="text" id="d_end_perfil" readonly style="background:rgba(0,0,0,0.05)"></div>
            <div id="form-manual" class="hidden"><input type="text" id="d_end_manual" placeholder="Rua, Número, Bairro"></div>
            <div id="form-gps" class="hidden">
                <button onclick="pegarGPS()" style="background:#444;">📍 Capturar GPS</button>
                <div id="status-gps" style="font-size:12px; margin-top:5px;">Não capturado</div>
            </div>

            <textarea id="d_obs" placeholder="Observações..."></textarea>
            <button id="btn-enviar-doacao" onclick="enviarDoacao()">Publicar Doação</button>

            <hr style="margin:20px 0; border:0; border-top:1px solid var(--border);">
            <h3>Meu Histórico</h3>
            <div id="lista-minhas-doacoes"></div>
            <button onclick="location.reload()" style="background:#888">Sair</button>
        </div>
    </div>

    <div id="tela-coletor" class="card-eco hidden">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="boas-vindas-col" style="margin:0"></h2>
            <button onclick="togglePerfil()" style="width:auto; background:var(--secondary); padding: 8px 15px;">⚙️ Perfil</button>
        </div>
        
        <div class="filtro-container" style="margin-bottom:15px; padding:10px; background:rgba(0,0,0,0.03); border-radius:8px;">
            <label style="font-size:12px; font-weight:bold;">Filtrar material:</label>
            <select id="filtro-material" onchange="carregarMural()">
                <option value="todos">Todos</option>
            </select>
        </div>

        <div id="lista-mural">
            <p style="text-align:center">Buscando doações disponíveis...</p>
        </div>
        <button onclick="location.reload()" style="background:#888; margin-top:20px;">Sair</button>
    </div>
</div>

<script>
let user = null;
let modoEndereco = 'perfil';

const maskDoc = IMask(document.getElementById('c_doc'), { mask: [{mask:'000.000.000-00'}, {mask:'00.000.000/0000-00'}] });
const maskTel = IMask(document.getElementById('c_tel'), { mask: '(00) 00000-0000' });

function toggleDarkMode() { document.body.classList.toggle('dark-theme'); }
function alternar(s, e) { document.getElementById(s).classList.add('hidden'); document.getElementById(e).classList.remove('hidden'); }

function togglePerfil() {
    document.getElementById('area-perfil').classList.toggle('hidden');
    const conteudo = user.tipo_usuario === 'Coletor' ? 'conteudo-coletor' : 'conteudo-doador';
    const contElem = document.getElementById(conteudo);
    if(contElem) contElem.classList.toggle('hidden');
}

function toggleMaterialCustom() {
    const sel = document.getElementById('d_mat');
    document.getElementById('d_mat_custom').classList.toggle('hidden', sel.value !== 'outro');
}

function mudarModoEnd(modo) {
    modoEndereco = modo;
    document.getElementById('form-perfil').classList.add('hidden');
    document.getElementById('form-manual').classList.add('hidden');
    document.getElementById('form-gps').classList.add('hidden');
    document.getElementById('form-' + modo).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + modo).classList.add('active');
}

function pegarGPS() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(p => {
            document.getElementById('status-gps').innerHTML = `✅ Lat: ${p.coords.latitude.toFixed(4)}, Lng: ${p.coords.longitude.toFixed(4)}`;
        }, () => alert("Erro ao acessar GPS"));
    }
}

function setLoading(btnId, isLoading) {
    const btn = document.getElementById(btnId);
    if(btn) {
        isLoading ? btn.classList.add('loading') : btn.classList.remove('loading');
        btn.disabled = isLoading;
    }
}

async function fazerLogin() {
    setLoading('btn-login', true);
    const fd = new FormData(); 
    fd.append('acao', 'login');
    fd.append('email', document.getElementById('l_email').value);
    fd.append('senha', document.getElementById('l_senha').value);
    
    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        const res = await r.json();
        
        if(res.id_usuario) {
            user = res;
            user.id = res.id_usuario;
            document.getElementById('tela-login').classList.add('hidden');
            
            if(user.tipo_usuario === 'Admin') {
                document.getElementById('tela-admin').classList.remove('hidden');
                carregarMateriaisAdmin();
            } else if (user.tipo_usuario === 'Coletor') {
                document.getElementById('boas-vindas-col').innerText = "Olá, " + user.nome;
                document.getElementById('tela-coletor').classList.remove('hidden');
                
                // --- ADICIONE ESTAS DUAS LINHAS ---
                carregarSelectMateriais(); 
                carregarMural();
                // ----------------------------------
                
            } else {
                document.getElementById('boas-vindas').innerText = "Olá, " + user.nome;
                document.getElementById('d_end_perfil').value = user.endereco || '';
                document.getElementById('tela-doador').classList.remove('hidden');
                carregarSelectMateriais();
                carregarMinhasDoacoes();
            }
        } else { alert(res.erro || "Dados incorretos."); }
    } catch (e) { alert("Erro de conexão."); }
    setLoading('btn-login', false);
}
async function carregarMural() {
    const mural = document.getElementById('lista-mural');
    const filtroMaterial = document.getElementById('filtro-material').value; // Pega o ID do material
    
    const fd = new FormData(); 
    // Certifique-se que no salvar.php a acao seja 'buscar_mural' ou 'listar_mural'
    fd.append('acao', 'buscar_mural'); 

    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        let dados = await r.json();
        
        // Aplica o filtro se não for "todos"
        if (filtroMaterial !== 'todos') {
            dados = dados.filter(d => d.id_material == filtroMaterial);
        }

        mural.innerHTML = dados.map(d => `
            <div class="item-mural">
                <strong>${d.nome_material || d.material_personalizado} (${d.quantidade} ${d.unidade_medida})</strong><br>
                <small>Doador: ${d.doador_nome}</small><br>
                <small>📍 ${d.endereco_manual}</small><br>
                <a href="https://wa.me/55${d.telefone.replace(/\D/g,'')}" class="btn-acao btn-whats" target="_blank">WhatsApp</a>
            </div>
        `).join('') || '<p style="text-align:center">Nenhuma doação encontrada para este filtro.</p>';
    } catch (e) {
        mural.innerHTML = '<p>Erro ao carregar o mural.</p>';
    }
}

async function carregarMinhasDoacoes() {
    const fd = new FormData(); 
    fd.append('acao', 'minhas_doacoes'); 
    fd.append('id_user', user.id);
    
    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        const dados = await r.json();
        
        const listaDiv = document.getElementById('lista-minhas-doacoes');
        
        if (dados.length === 0) {
            listaDiv.innerHTML = '<p style="color:#888; padding:10px;">Nenhuma doação feita ainda.</p>';
            return;
        }

        // Esta estrutura gera exatamente o visual do seu print
        listaDiv.innerHTML = dados.map(d => {
            // Lógica para definir o nome (se for material cadastrado ou manual)
            const nomeExibicao = d.nome_material ? d.nome_material : d.material_personalizado;
            
            return `
                <div class="item-mural" style="margin-bottom: 15px; text-align: left;">
                    <div style="font-weight: bold; font-size: 1.1rem; color: #333;">
                        ${nomeExibicao} <span style="font-weight: normal; color: #666;">(${d.quantidade}${d.unidade_medida})</span>
                    </div>
                    <div style="font-size: 0.9rem; color: #555; margin-top: 4px;">
                        Local: ${d.endereco_manual}
                    </div>
                    <div style="font-size: 0.9rem; color: #555;">
                        Status: <span style="font-weight: 500;">${d.status_doacao}</span>
                    </div>
                </div>
            `;
        }).join('');
    } catch (e) {
        console.error("Erro ao carregar histórico:", e);
    }
}
async function carregarSelectMateriais() {
    const fd = new FormData(); fd.append('acao', 'listar_categorias');
    const r = await fetch('salvar.php', { method: 'POST', body: fd });
    const dados = await r.json();
    const sel = document.getElementById('d_mat');
    const fil = document.getElementById('filtro-material');
    let html = dados.map(m => `<option value="${m.id_material}">${m.nome_material}</option>`).join('');
    if(sel) sel.innerHTML = html + '<option value="outro">Outro Material</option>';
    if(fil) fil.innerHTML = '<option value="todos">Todos</option>' + html;
    
}

// Funções Admin 
async function carregarMateriaisAdmin() {
    const fd = new FormData(); fd.append('acao', 'listar_categorias');
    const r = await fetch('salvar.php', { method: 'POST', body: fd });
    const dados = await r.json();
    document.getElementById('lista-materiais-admin').innerHTML = dados.map(m => `
        <div class="item-categoria">
            <span>${m.nome_material}</span>
            <button class="btn-mini" style="background:var(--danger)" onclick="excluirMaterial(${m.id_material})">🗑️</button>
        </div>
    `).join('');
}
// Carregar Materiais no Admin
async function carregarMateriaisAdmin() {
    const fd = new FormData(); 
    fd.append('acao', 'listar_categorias'); // Nome correto conforme seu salvar.php
    const r = await fetch('salvar.php', { method: 'POST', body: fd });
    const dados = await r.json();
    
    document.getElementById('lista-materiais-admin').innerHTML = dados.map(m => `
        <div class="item-categoria" style="display:flex; justify-content:space-between; align-items:center;">
            <span>${m.nome_material}</span>
            <button class="btn-mini" style="background:var(--danger); width:auto;" onclick="excluirMaterial(${m.id_material})">🗑️</button>
        </div>
    `).join('') || '<p style="padding:10px;">Nenhuma categoria cadastrada.</p>';
}

// Função para o Admin Excluir Material
async function excluirMaterial(id) {
    if(!confirm("Deseja excluir esta categoria?")) return;
    const fd = new FormData();
    fd.append('acao', 'excluir_material');
    fd.append('id', id);
    const r = await fetch('salvar.php', { method: 'POST', body: fd });
    const res = await r.json();
    if(res.sucesso) carregarMateriaisAdmin();
    else alert(res.erro || "Erro ao excluir");
}

async function carregarMural() {
    const mural = document.getElementById('lista-mural');
    const filtroMaterial = document.getElementById('filtro-material').value;
    
    const fd = new FormData(); 
    // Usamos 'listar_mural' que é o nome que padronizamos no seu salvar.php
    fd.append('acao', 'listar_mural'); 

    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        let dados = await r.json();
        
        // Filtra os dados no Front-end
        if (filtroMaterial !== 'todos') {
            dados = dados.filter(d => d.id_material == filtroMaterial);
        }

        if(!dados || dados.length === 0) {
            mural.innerHTML = '<p style="text-align:center">Nenhuma doação disponível.</p>';
            return;
        }

        mural.innerHTML = dados.map(d => `
            <div class="item-mural">
                <strong style="color:var(--primary)">${d.nome_material || d.material_personalizado}</strong><br>
                <span>Quantidade: ${d.quantidade} ${d.unidade_medida}</span><br>
                <small>Doador: ${d.doador_nome}</small><br>
                <small>📍 ${d.endereco_manual || 'Ver no perfil'}</small><br>
                
                <div style="display:flex; gap:5px; margin-top:10px; flex-wrap: wrap;">
                    <a href="https://wa.me/55${d.telefone.replace(/\D/g,'')}" target="_blank" class="btn-acao btn-whats" style="flex:1">WhatsApp</a>
                    <button class="btn-acao" style="background:var(--accent); flex:1" onclick="reservarMaterial(${d.id_doacao})">📦 Reservar</button>
                    <button class="btn-acao btn-mapa" style="width:100%" onclick="alert('Localização: ${d.endereco_manual}')">Ver Endereço</button>
                </div>
            </div>
        `).join('');
    } catch (e) {
        mural.innerHTML = '<p>Erro ao carregar o mural.</p>';
    }
}
async function reservarMaterial(idDoacao) {
    if(!confirm("Deseja reservar este material? Ele sairá do mural para outros coletores.")) return;

    const fd = new FormData();
    fd.append('acao', 'reservar_material');
    fd.append('id_doacao', idDoacao);
    fd.append('id_user', user.id); // ID do coletor logado

    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        const res = await r.json();
        
        if(res.sucesso) {
            alert(res.sucesso);
            carregarMural(); // Atualiza a lista para remover o item reservado
        } else {
            alert(res.erro || "Erro ao reservar.");
        }
    } catch (e) {
        alert("Erro de conexão ao processar reserva.");
    }
}

async function executarCadastro() {
    setLoading('btn-cadastro', true);
    const fd = new FormData();
    fd.append('acao', 'cadastro');
    fd.append('nome', document.getElementById('c_nome').value);
    fd.append('email', document.getElementById('c_email').value);
    fd.append('doc', document.getElementById('c_doc').value);
    fd.append('tel', document.getElementById('c_tel').value);
    fd.append('end', document.getElementById('c_end').value);
    fd.append('tipo', document.getElementById('c_tipo').value);
    fd.append('senha', document.getElementById('c_senha').value);

    const r = await fetch('salvar.php', { method: 'POST', body: fd });
    const res = await r.json();
    alert(res.sucesso || res.erro);
    if(res.sucesso) location.reload();
    setLoading('btn-cadastro', false);
}
async function salvarMaterial() {
    const nomeInput = document.getElementById('adm_nome_material');
    const nome = nomeInput.value;

    if (!nome) {
        alert("Por favor, digite o nome do material.");
        return;
    }

    const fd = new FormData();
    // Ajustado para 'adm_add_material' para bater com seu salvar.php linha 58
    fd.append('acao', 'adm_add_material'); 
    fd.append('nome', nome);

    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        const res = await r.json();
        
        if (res.sucesso) {
            alert(res.sucesso);
            nomeInput.value = ''; // Limpa o campo
            carregarMateriaisAdmin(); // Atualiza a lista na tela
        } else {
            alert("Erro: " + res.erro);
        }
    } catch (e) {
        alert("Erro de conexão ao salvar material.");
    }
}
async function enviarDoacao() {
    const material = document.getElementById('d_mat').value;
    const qtd = document.getElementById('d_qtd').value;
    
    if (!qtd || qtd <= 0) {
        alert("Informe uma quantidade válida.");
        return;
    }

    setLoading('btn-enviar-doacao', true);
    
    const fd = new FormData();
    fd.append('acao', 'doacao'); // Deve ser 'doacao' para entrar na linha 31 do seu salvar.php
    fd.append('id_user', user.id);
    fd.append('id_material', material);
    fd.append('material_personalizado', document.getElementById('d_mat_custom').value);
    fd.append('quantidade', qtd);
    fd.append('unidade_medida', document.getElementById('d_unidade').value);
    
    let enderecoFinal = "";
    if(modoEndereco === 'perfil') enderecoFinal = user.endereco;
    else if(modoEndereco === 'manual') enderecoFinal = document.getElementById('d_end_manual').value;
    else enderecoFinal = document.getElementById('status-gps').innerText;
    
    fd.append('endereco_manual', enderecoFinal);
    fd.append('observacoes', document.getElementById('d_obs').value);

    try {
        const r = await fetch('salvar.php', { method: 'POST', body: fd });
        const textoParaDebug = await r.text(); // Captura o texto puro para ver erros de PHP
        try {
            const res = JSON.parse(textoParaDebug);
            if (res.sucesso) {
                alert("✅ Doação publicada!");
                location.reload();
            } else {
                alert("Erro no Banco: " + res.erro);
            }
        } catch (e) {
            console.error("Resposta do servidor não é JSON:", textoParaDebug);
            alert("Erro crítico no servidor. Verifique o console (F12).");
        }
    } catch (e) {
        alert("Erro de conexão.");
    } finally {
        setLoading('btn-enviar-doacao', false);
    }
}

</script>
</body>
</html>