<?php
include('./inc/inc.configdb.php');
// include('./inc/inc.configdir.php');
include('./inc/inc.lib.php');
// include('./inc/inc.upload.php');
include('./inc/m2brimagem.class.php');
include('./inc/class.ValidaCpfCnpj.php');
include('./inc/phpmailer/PHPMailerAutoload.php');


if (isset($_REQUEST['act']) && !empty($_REQUEST['act'])) {
    session_start(); //SEMPRE QUE FOR USAR A SESSION
    $act = $_REQUEST['act']; //server tanto pra POST quanto pra GET

    switch ($act) {

        case 'usuarios_admin_cadastrar':
            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }
            if (empty($_POST["user"])) {
                echo "user";
                exit;
            }
            if (empty($_POST['acess_panel'])) {
                echo "acess_panel";
                exit;
            }
            if (empty($_POST['pass'])) {
                echo "senha";
                exit;
            }
            if (empty($_POST['rpt_pass'])) {
                echo "rpt_senha";
                exit;
            }
            if (strlen($_POST['pass']) < 4) {
                echo "senha_caracteres";
                exit;
            }
            if ($_POST['pass'] != $_POST['rpt_pass']) {
                echo "senha_equals";
                exit;
            }

            $nome = addslashes($_POST['nome']);
            $user = strtolower(addslashes($_POST['user']));
            $pass = addslashes($_POST['pass']);
            $acess_panel = addslashes($_POST['acess_panel']);

            $sql1 = "SELECT * FROM bejobs_usuario_admin WHERE user = '$user'";
            $query1 = $dba->query($sql1);
            $qntd1 = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "user_exists";
                exit;
            }

            $sql = "INSERT INTO bejobs_usuario_admin (name, user, pass, type, status, acess_panel) VALUES (UPPER('$nome'), '$user', MD5('$pass'), 2, 0, $acess_panel)"; //die($sql);
            $dba->query($sql);
            $id_usuario = $dba->lastid();

            $sql2 = "INSERT INTO bejobs_paginas_acesso (id_pagina, visualizar, cadastrar, editar, excluir, id_usuario) 
                                              SELECT id, 0, 0, 0, 0, $id_usuario FROM bejobs_paginas WHERE 1";
            $dba->query($sql2);

            // grava no log do sistema 
            // $id = $dba->lastid(); 
            // logs('Cadastrou novo administrador ID: '.$id);

            echo "success";
            break;

        case 'usuarios_admin_delete':
            $id = $_GET['id'];

            $sql = "DELETE FROM bejobs_usuario_admin WHERE id = $id AND type != 1";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Deletou administrador ID: '.$id);

            header('Location: ./usuarios-admin?msg=a003');
            break;

        case 'usuarios_admin_editar':
            $id = $_POST['id'];
            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }
            if (empty($_POST["user"])) {
                echo "user";
                exit;
            }
            if (empty($_POST['acess_panel'])) {
                echo "acess_panel";
                exit;
            }

            $nome = addslashes($_POST['nome']);
            $user = strtolower(addslashes($_POST['user']));
            $acess_panel = addslashes($_POST['acess_panel']);

            $pass = '';
            if (!empty($_POST['pass'])) {
                if (empty($_POST['rpt_pass'])) {
                    echo "rpt_senha";
                    exit;
                }
                if (strlen($_POST['pass']) < 4) {
                    echo "senha_caracteres";
                    exit;
                }
                if ($_POST['pass'] != $_POST['rpt_pass']) {
                    echo "senha_equals";
                    exit;
                }
                $pass = ", pass=MD5('" . addslashes($_POST['pass']) . "')";
            }

            $sql1 = "SELECT * FROM bejobs_usuario_admin WHERE user = '$user' AND id != $id";
            $query1 = $dba->query($sql1);
            $qntd1 = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "user_exists";
                exit;
            }

            $sql = "UPDATE bejobs_usuario_admin SET name = UPPER('$nome'), user = '$user', acess_panel = $acess_panel $pass WHERE id = $id"; //die($sql);
            $dba->query($sql);

            // grava no log do sistema
            // logs('Editou cadastro administrador ID: '.$id);

            echo "success_edit";
            break;

        case 'desativar_usuarios_admin':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_usuario_admin SET status = 0 WHERE id = $idn AND type != 1";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./usuarios-admin?msg=a007');
            break;

        case 'ativar_usuarios_admin':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_usuario_admin SET status = 1 WHERE id = $idn AND type != 1";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./usuarios-admin?msg=a006');
            break;

        case 'usuarios_admin_permissoes':
            $id_usuario = $_POST['id_usuario'];
            $permissoes = $_POST['permissoes']; // Array com dados de módulos e tipos de permissões

            // Deleta todos os registros do usuário aos módulos
            $sql = "DELETE FROM bejobs_paginas_acesso WHERE id_usuario=$id_usuario";
            $dba->query($sql);

            // Insere registros de usuários aos módulo setando = 0 os tipos de acesso
            $sql2 = "INSERT INTO bejobs_paginas_acesso (id_pagina, visualizar, cadastrar, editar, excluir, id_usuario) 
                                              SELECT id, 0, 0, 0, 0, $id_usuario FROM bejobs_paginas WHERE 1";
            $dba->query($sql2);

            // Verifica se existe array de permissões 
            if (isset($_POST['permissoes'])) {
                foreach ($_POST['permissoes'] as $permissoes) { // Percorre todo array
                    $temp = explode("_", $permissoes); // Separa informação idpagina_tipodeacesso
                    $id_pagina = $temp[0]; // Id da página
                    $campo = $temp[1]; // Tipos de acesso igual a nomenclatura da coluna no banco: visualizar, cadastrar, editar e exlcuir 

                    $sql = "UPDATE bejobs_paginas_acesso SET $campo=1 WHERE id_pagina=$id_pagina AND id_usuario=$id_usuario";
                    $dba->query($sql);
                }
            }

            echo "success_edit";
            break;

        case 'usuarios_editar':
            $id = $_POST['id'];

            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }

            if (empty($_POST['cpf'])) {
                echo "cpf";
                exit;
            }
            $cpf    = addslashes($_POST["cpf"]);
            $cpf    = preg_replace("/[^0-9]/", "", $cpf); // Retira formatação CPF                    
            $sql2   = "SELECT * FROM bejobs_usuarios WHERE cpf LIKE '%$cpf%' AND id != $id"; // Vverifica se cpf já está registrado na base de dados   
            $query2 = $dba->query($sql2);
            $qntd2  = $dba->rows($query2);
            if ($qntd2 > 0) {
                echo "cpf_existente";
                exit;
            }

            if (empty($_POST["email"]) || !ValidaEmail($_POST["email"])) {
                echo "email";
                exit;
            }
            $email  = addslashes($_POST['email']);
            $sql2   = "SELECT * FROM bejobs_usuarios WHERE email = '$email' AND id != $id";
            $query2 = $dba->query($sql2);
            $qntd2  = $dba->rows($query2);
            if ($qntd2 > 0) {
                echo "email_existente";
                exit;
            }

            if (empty($_POST["nascimento"]) || !validaData($_POST['nascimento'])) {
                echo "nascimento";
                exit;
            }
            if (empty($_POST["telefone_celular"])) {
                echo "telefone_celular";
                exit;
            }

            // if(empty($_POST["lojas_cnpj"])){echo "lojas_cnpj"; exit;}    
            // $lojas_cnpj = preg_replace("/[^0-9]/", "", $_POST['lojas_cnpj']);
            // $sql5   = "SELECT id, nome_fantasia FROM bejobs_lojas WHERE cnpj = '$lojas_cnpj'";
            // $query5 = $dba->query($sql5);
            // $qntd5  = $dba->rows($query5);
            // if ($qntd5 == 0) {
            //       echo "cnpj_invalido";
            //       exit;
            // } 

            // if(empty($_POST["acesso_gerente"])){echo "acesso_gerente"; exit;}    
            // if(empty($_POST["reputacao_usuario"])){echo "reputacao_usuario"; exit;}

            // Senha de 6 caracteres numerais
            $senha = '';
            if (!empty($_POST['senha'])) {
                if (empty($_POST['senha_confirma'])) {
                    echo "senha_confirma";
                    exit;
                }
                if (strlen($_POST['senha']) < 4) {
                    echo "senha_caracteres";
                    exit;
                }
                if ($_POST['senha'] != $_POST['senha_confirma']) {
                    echo "senhas_iguais";
                    exit;
                }
                $senha_tmp = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $senha = ", senha='" . $senha_tmp . "'";
            }

            $nome              = strtoupper(addslashes($_POST['nome']));
            $email             = strtolower(addslashes($_POST['email']));
            $nascimento        = addslashes($_POST['nascimento']);
            $telefone_celular  = addslashes($_POST['telefone_celular']);
            $telefone_celular = preg_replace("/[^0-9]/", "", $telefone_celular);
            // $acesso_gerente    = addslashes($_POST['acesso_gerente']);
            // $reputacao_usuario = addslashes($_POST['reputacao_usuario']);

            //transforma a data de nascimento em data para o mysql
            if (!empty($nascimento)) {
                $nascimento = dataMY($nascimento);
            }

            $memorando = addslashes($_POST["memorando"]);

            $sql = "UPDATE bejobs_usuarios SET cpf='$cpf', nome='$nome', email='$email', nascimento='$nascimento', telefone_celular='$telefone_celular', memorando='$memorando' $senha WHERE id=$id";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'desativar_usuarios':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_usuarios SET status = 0 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./usuarios?msg=u005');
            break;

        case 'ativar_usuarios':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_usuarios SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./usuarios?msg=u004');
            break;

        case 'entregadores_cadastrar':
            // $id = $_POST['id'];     

            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }
            if (empty($_POST["telefone_celular"])) {
                echo "telefone_celular";
                exit;
            }
            if (empty($_POST["email"])) {
                echo "email";
                exit;
            }

            if (empty($_POST["login"])) {
                echo "login";
                exit;
            }
            $login  = addslashes($_POST['login']);
            // $sql2   = "SELECT * FROM bejobs_entregadores WHERE login = '$login' AND id != $id";
            $sql2   = "SELECT * FROM bejobs_entregadores WHERE login = '$login'";
            $query2 = $dba->query($sql2);
            $qntd2  = $dba->rows($query2);
            if ($qntd2 > 0) {
                echo "login_existente";
                exit;
            }

            if (empty($_POST['senha'])) {
                echo "senha";
                exit;
            }
            if (empty($_POST['senha_confirma'])) {
                echo "senha_confirma";
                exit;
            }
            if (strlen($_POST['senha']) < 4) {
                echo "senha_caracteres";
                exit;
            }
            if ($_POST['senha'] != $_POST['senha_confirma']) {
                echo "senhas_iguais";
                exit;
            }

            // Senha de no mínimo 6 caracteres
            // $senha = '';
            // if (!empty($_POST['senha'])) {
            // if (empty($_POST['senha_confirma'])) {echo "senha_confirma"; exit;} 
            // if (strlen($_POST['senha']) < 4) {echo "senha_caracteres"; exit;}
            // if ($_POST['senha'] != $_POST['senha_confirma']) {echo "senhas_iguais"; exit;}   
            // $senha_tmp = password_hash($_POST['senha'], PASSWORD_DEFAULT);             
            // $senha = ", senha='".$senha_tmp."'";   
            // }

            $nome             = strtoupper(addslashes($_POST['nome']));
            $email            = strtolower(addslashes($_POST['email']));
            $telefone_celular = addslashes($_POST['telefone_celular']);
            $telefone_celular = preg_replace("/[^0-9]/", "", $telefone_celular);
            $login            = strtolower(addslashes($_POST['login']));
            $senha            = addslashes($_POST['senha']);

            // $sql = "INSERT INTO bejobs_entregadores (nome, email, telefone_celular, login, senha) VALUES () nome='$nome', email='$email', telefone_celular='$telefone_celular', login='$login', senha='$senha' WHERE id=$id";
            $sql = "INSERT INTO bejobs_entregadores (nome, email, telefone_celular, login, senha) VALUES ('$nome', '$email', '$telefone_celular', '$login', '$senha')";
            $dba->query($sql);

            echo "success";
            break;

        case 'entregadores_editar':
            $id = $_POST['id'];

            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }
            if (empty($_POST["telefone_celular"])) {
                echo "telefone_celular";
                exit;
            }
            if (empty($_POST["email"])) {
                echo "email";
                exit;
            }

            if (empty($_POST["login"])) {
                echo "login";
                exit;
            }
            $login  = addslashes($_POST['login']);
            $sql2   = "SELECT * FROM bejobs_entregadores WHERE login = '$login' AND id != $id";
            $query2 = $dba->query($sql2);
            $qntd2  = $dba->rows($query2);
            if ($qntd2 > 0) {
                echo "login_existente";
                exit;
            }

            if (empty($_POST['senha'])) {
                echo "senha";
                exit;
            }
            if (empty($_POST['senha_confirma'])) {
                echo "senha_confirma";
                exit;
            }
            if (strlen($_POST['senha']) < 4) {
                echo "senha_caracteres";
                exit;
            }
            if ($_POST['senha'] != $_POST['senha_confirma']) {
                echo "senhas_iguais";
                exit;
            }

            // Senha de no mínimo 6 caracteres
            // $senha = '';
            // if (!empty($_POST['senha'])) {
            // if (empty($_POST['senha_confirma'])) {echo "senha_confirma"; exit;} 
            // if (strlen($_POST['senha']) < 4) {echo "senha_caracteres"; exit;}
            // if ($_POST['senha'] != $_POST['senha_confirma']) {echo "senhas_iguais"; exit;}   
            // $senha_tmp = password_hash($_POST['senha'], PASSWORD_DEFAULT);             
            // $senha = ", senha='".$senha_tmp."'";   
            // }

            $nome             = strtoupper(addslashes($_POST['nome']));
            $email            = strtolower(addslashes($_POST['email']));
            $telefone_celular = addslashes($_POST['telefone_celular']);
            $telefone_celular = preg_replace("/[^0-9]/", "", $telefone_celular);
            $login            = strtolower(addslashes($_POST['login']));
            $senha            = addslashes($_POST['senha']);

            $sql = "UPDATE bejobs_entregadores SET nome='$nome', email='$email', telefone_celular='$telefone_celular', login='$login', senha='$senha' WHERE id=$id";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'desativar_entregadores':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_entregadores SET status = 0 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./entregadores?msg=e005');
            break;

        case 'ativar_entregadores':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_entregadores SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./entregadores?msg=e004');
            break;

        case 'entregadores_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM bejobs_entregadores WHERE id = $id";
            $dba->query($sql);

            header('location: ./entregadores?msg=e003');
            break;

        case 'banners_cadastrar':
            if (empty($_POST['posicao'])) {
                echo "posicao";
                exit;
            }
            $posicao   = $_POST['posicao'];
            // $descricao = $_POST['descricao'];           

            if (empty($_FILES['arq']) && $_FILES['arq']['size'] > 0) {
                echo "file";
                exit;
            }
            if ($_FILES['arq']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            if (empty($_FILES['arq_mobile']) && $_FILES['arq_mobile']['size'] > 0) {
                echo "file-m";
                exit;
            }
            if ($_FILES['arq_mobile']['size'] > 2097152) {
                echo "invalidimgsize-m";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name        = $_FILES['arq']['name'];
            $ext         = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            //Atribui uma array com os nomes dos arquivos à variável
            $name        = $_FILES['arq_mobile']['name'];
            $ext         = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg-m";
                exit;
            }

            list($width, $height) = getimagesize($_FILES['arq']['tmp_name']);
            if ($width != 1920 && $height != 250) {
                echo "invalidimgdimensoes";
                exit;
            }

            list($width_m, $height_m) = getimagesize($_FILES['arq_mobile']['tmp_name']);
            if ($width_m != 750 && $height_m != 500) {
                echo "invalidimgdimensoes-m";
                exit;
            }

            $sql = "INSERT INTO bejobs_banners (posicao) values ('$posicao')";
            $dba->query($sql);

            $ide = $dba->lastid();

            // $arq     = $_FILES['arq'];
            $destino = "../images/banners/" . $ide . ".jpg";
            // $ok = upload($arq, $destino, 1920, 520);
            $ok = move_uploaded_file($_FILES['arq']['tmp_name'], $destino);
            if (!$ok) {
                echo "file_upload";
                exit;
            }

            // $arq_mobile = $_FILES['arq_mobile'];
            $destino    = "../images/banners/" . $ide . "-m.jpg";
            $ok = move_uploaded_file($_FILES['arq_mobile']['tmp_name'], $destino);
            // $ok = upload($arq_mobile, $destino, 548, 512);
            if (!$ok) {
                echo "file_upload-m";
                exit;
            }

            echo "success";
            break;

        case 'banners_img_delete':
            $idn = $_GET['id'];

            if (file_exists('../images/banners/' . $idn . '.jpg')) {
                unlink('../images/banners/' . $idn . '.jpg');
            }


            header('location: banners-editar?id=' . $idn);
            break;

        case 'banners_img_mobile_delete':
            $idn = $_GET['id'];

            if (file_exists('../images/banners/' . $idn . '-m.jpg')) {
                unlink('../images/banners/' . $idn . '-m.jpg');
            }


            header('location: banners-editar?id=' . $idn);
            break;

        case 'banners_delete':
            $idn = $_GET['id'];

            if (file_exists('../images/banners/' . $idn . '.jpg')) {
                unlink('../images/banners/' . $idn . '.jpg');
            }

            if (file_exists('../images/banners/' . $idn . '-m.jpg')) {
                unlink('../images/banners/' . $idn . '-m.jpg');
            }

            $sql = "DELETE from bejobs_banners where id = '$idn'";
            $dba->query($sql);

            header('location: banners?msg=b03');
            break;

        case 'banners_editar':
            $ide = $_POST['id'];
            if (empty($_POST['posicao'])) {
                echo "posicao";
                exit;
            }
            $posicao   = $_POST['posicao'];
            // $descricao = $_POST['descricao'];

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            if (!file_exists('../images/banners/' . $ide . '.jpg')) {
                if (empty($_FILES['arq']) && $_FILES['arq']['size'] > 0) {
                    echo "file";
                    exit;
                }
                if ($_FILES['arq']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name        = $_FILES['arq']['name'];
                $ext         = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }

                list($width, $height) = getimagesize($_FILES['arq']['tmp_name']);
                if ($width != 1920 && $height != 250) {
                    echo "invalidimgdimensoes";
                    exit;
                }
            }

            if (!file_exists('../images/banners/' . $ide . '-m.jpg')) {
                if (empty($_FILES['arq_mobile']) && $_FILES['arq_mobile']['size'] > 0) {
                    echo "file-m";
                    exit;
                }
                if ($_FILES['arq_mobile']['size'] > 2097152) {
                    echo "invalidimgsize-m";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name        = $_FILES['arq_mobile']['name'];
                $ext         = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg-m";
                    exit;
                }

                list($width_m, $height_m) = getimagesize($_FILES['arq_mobile']['tmp_name']);
                if ($width_m != 750 && $height_m != 500) {
                    echo "invalidimgdimensoes-m";
                    exit;
                }
            }

            $sql = "UPDATE bejobs_banners SET posicao='$posicao' WHERE id = $ide";
            $dba->query($sql);

            if (isset($_FILES['arq'])) {
                // $arq     = $_FILES['arq'];
                $destino = "../images/banners/" . $ide . ".jpg";
                // $ok = upload($arq, $destino, 1920, 520);
                $ok = move_uploaded_file($_FILES['arq']['tmp_name'], $destino);
                if (!$ok) {
                    echo "file_upload";
                    exit;
                }
            }

            if (isset($_FILES['arq_mobile'])) {
                // $arq_mobile = $_FILES['arq_mobile'];
                $destino    = "../images/banners/" . $ide . "-m.jpg";
                $ok = move_uploaded_file($_FILES['arq_mobile']['tmp_name'], $destino);
                // $ok = upload($arq_mobile, $destino, 548, 512);
                if (!$ok) {
                    echo "file_upload-m";
                    exit;
                }
            }

            echo "success_edit";
            break;

        case 'ativar_banner':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_banners SET status = 1 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./banners?msg=b04');

            break;

        case 'desativar_banner':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_banners SET status = 0 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./banners?msg=b05');
            break;

        case 'promocoes_cadastrar':
            if (trim(empty($_POST['titulo']))) {
                echo "titulo";
                exit;
            }
            // if (trim(empty($_POST['descricao']))) {echo "descricao"; exit;}
            // if (trim(empty($_POST['data_inicio'])) || !validaData($_POST['data_inicio'])) {echo "data_inicio"; exit;}
            // if (trim(empty($_POST['hora_inicio'])) || !validaHora($_POST['hora_inicio'])) {echo "hora_inicio"; exit;}
            // if (trim(empty($_POST['data_fim'])) || !validaData($_POST['data_fim'])) {echo "data_fim"; exit;}   
            // if (trim(empty($_POST['hora_fim'])) || !validaHora($_POST['hora_fim'])) {echo "hora_fim"; exit;}         
            // if (strtotime(dataMY($_POST['data_inicio'])) > strtotime(dataMY($_POST['data_fim']))) { echo "datas"; exit; } 
            // if (($_POST['hora_inicio'] > $_POST['hora_fim'])) { echo "horas_invalidas"; exit; }

            // if (empty($_POST['produto'])) {echo "produto"; exit;}
            // if (empty($_POST['pontuacao'])) {echo "pontuacao"; exit;}
            // if (empty($_POST['promo_categorias'])) {echo "promo_categorias"; exit;}    
            // if (empty($_POST['promo_marcas'])) {echo "promo_marcas"; exit;}
            // if (empty($_POST['promo_produtos'])) {echo "promo_produtos"; exit;}
            // if (!isset($_POST['participantes'])) { echo "participantes"; exit; }     

            // if ($_POST['participantes'] == 2) {
            //       if (empty($_POST['id_grupo_economico'])) {echo "id_grupo_economico"; exit;}
            // }

            // if ($_POST['participantes'] == 3) {
            //       if (empty($_POST['id_loja'])) {echo "id_loja"; exit;}
            // }

            // if ($_POST['participantes'] == 4) {
            //       if (empty($_POST['id_grupo_usuarios'])) {echo "id_grupo_usuarios"; exit;}
            // }

            // if (!is_numeric($_POST['visualizacao_web'])) {echo "visualizacao_web"; exit;}


            if (!empty($_FILES['img']) || $_FILES['img']['size'] > 0) {
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo     = addslashes($_POST['titulo']);
            $descricao  = addslashes($_POST['descricao']);
            $id_produto = $_POST['produto'];

            // $data_inicio      = $_POST['data_inicio'];
            // $hora_inicio      = $_POST['hora_inicio'];
            // $data_hora_inicio = datetime_mysql($data_inicio, $hora_inicio);

            // $data_fim      = $_POST['data_fim'];            
            // $hora_fim      = $_POST['hora_fim']; 
            // $data_hora_fim = datetime_mysql($data_fim, $hora_fim);           

            // $pontuacao = $_POST['pontuacao'];
            // $pontuacao = str_replace('.', '', $pontuacao); // Primeiro tira o ponto
            // $pontuacao = str_replace(',', '.', $pontuacao); // Troca a vírgula por ponto

            // $promo_categorias = $_POST['promo_categorias'];
            // $promo_marcas     = $_POST['promo_marcas'];
            // $promo_produtos   = $_POST['promo_produtos'];
            // $participantes    = $_POST['participantes'];

            // $id_grupo_economico = ($participantes == 2) ? $_POST['id_grupo_economico'] : 0;
            // $id_loja            = ($participantes == 3) ? $_POST['id_loja'] : 0;
            // $id_grupo_usuarios  = ($participantes == 4) ? $_POST['id_grupo_usuarios'] : 0;         

            // $visualizacao_web   = $_POST['visualizacao_web']; 

            $sql = "INSERT INTO bejobs_promo (titulo, descricao, id_produto) VALUES ('$titulo', '$descricao', '$id_produto')";
            $dba->query($sql);

            $ide = $dba->lastid();

            if (!empty($_FILES['img']) || $_FILES['img']['size'] > 0) {
                $img     = $_FILES['img'];
                $destino = "../images/promo/" . $ide . ".jpg";
                $ok      = upload($img, $destino, 2048, 2048);

                $img      = "../images/promo/" . $ide . ".jpg";
                $thumb    = new m2brimagem("$img");
                $verifica = $thumb->valida();
                if ($verifica == "OK") {

                    $tipo      = "";
                    $dimensoes = getimagesize($img);
                    $largura   = $dimensoes[0];
                    $altura    = $dimensoes[1];
                    if ($largura > $altura) {
                        $tipo = "crop";
                    } elseif ($largura < $altura) {
                        $tipo = "fill";
                    } else {
                        $tipo = "crop";
                    }

                    // $thumb->redimensiona(850, 400, $tipo);
                    // $thumb->grava('../images/promo/'.$ide.'_850x350.jpg', 80);

                    // $img   = "../images/promo/".$ide.".jpg";                      
                    // $thumb = new m2brimagem("$img");

                    $thumb->redimensiona(600, 600, $tipo);
                    $thumb->grava('../images/promo/' . $ide . '_600x600.jpg', 80);
                } else {
                    die($verifica);
                }
            }

            echo "success";
            break;

        case 'promocoes_delete':
            $ide = $_REQUEST['id'];
            $sql = "DELETE FROM bejobs_promo WHERE id = '$ide'";
            $dba->query($sql);

            $img = '../images/promo/' . $ide . '.jpg';
            if (is_file($img)) {
                unlink($img);
            }

            // $thumb = '../images/promo/'.$ide.'_850x350.jpg';
            // if (is_file($thumb)){unlink($thumb);}

            $thumb = '../images/promo/' . $ide . '_600x600.jpg';
            if (is_file($thumb)) {
                unlink($thumb);
            }

            header("Location: ./promocoes?msg=p003");
            break;

        case 'promocoes_img_delete':
            $ide = $_REQUEST['id'];

            $img = '../images/promo/' . $ide . '.jpg';
            if (is_file($img)) {
                unlink($img);
            }

            // $thumb = '../images/promo/'.$ide.'_850x350.jpg';
            // if (is_file($thumb)){unlink($thumb);}

            $thumb = '../images/promo/' . $ide . '_600x600.jpg';
            if (is_file($thumb)) {
                unlink($thumb);
            }

            header("Location: ./promocoes-editar?id=$ide");
            break;

        case 'promocoes_editar':
            $id = $_POST['id'];
            if (trim(empty($_POST['titulo']))) {
                echo "titulo";
                exit;
            }
            // if (trim(empty($_POST['descricao']))) {echo "descricao"; exit;}
            // if (trim(empty($_POST['data_inicio'])) || !validaData($_POST['data_inicio'])) {echo "data_inicio"; exit;}
            // if (trim(empty($_POST['hora_inicio'])) || !validaHora($_POST['hora_inicio'])) {echo "hora_inicio"; exit;}
            // if (trim(empty($_POST['data_fim'])) || !validaData($_POST['data_fim'])) {echo "data_fim"; exit;}   
            // if (trim(empty($_POST['hora_fim'])) || !validaHora($_POST['hora_fim'])) {echo "hora_fim"; exit;}         
            // if (strtotime(dataMY($_POST['data_inicio'])) > strtotime(dataMY($_POST['data_fim']))) { echo "datas"; exit; } 
            // if (($_POST['hora_inicio'] > $_POST['hora_fim'])) { echo "horas_invalidas"; exit; }
            // if (empty($_POST['pontuacao'])) {echo "pontuacao"; exit;}
            // if (empty($_POST['promo_categorias'])) {echo "promo_categorias"; exit;}    
            // if (empty($_POST['promo_marcas'])) {echo "promo_marcas"; exit;}
            // if (empty($_POST['promo_produtos'])) {echo "promo_produtos"; exit;}
            // if (!isset($_POST['participantes'])) { echo "participantes"; exit; }     

            // if ($_POST['participantes'] == 2) {
            //       if (empty($_POST['id_grupo_economico'])) {echo "id_grupo_economico"; exit;}
            // }

            // if ($_POST['participantes'] == 3) {
            //       if (empty($_POST['id_loja'])) {echo "id_loja"; exit;}
            // }

            // if ($_POST['participantes'] == 4) {
            //       if (empty($_POST['id_grupo_usuarios'])) {echo "id_grupo_usuarios"; exit;}
            // }

            // if (!is_numeric($_POST['visualizacao_web'])) {echo "visualizacao_web"; exit;}


            if (!empty($_FILES['img']) || $_FILES['img']['size'] > 0) {
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo     = addslashes($_POST['titulo']);
            $descricao  = addslashes($_POST['descricao']);
            $id_produto = $_POST['produto'];

            // $data_inicio = $_POST['data_inicio'];
            // $hora_inicio = $_POST['hora_inicio'];
            // $data_hora_inicio = datetime_mysql($data_inicio, $hora_inicio);

            // $data_fim = $_POST['data_fim'];            
            // $hora_fim = $_POST['hora_fim']; 
            // $data_hora_fim = datetime_mysql($data_fim, $hora_fim);

            // $pontuacao = $_POST['pontuacao'];
            // $pontuacao = str_replace('.', '', $pontuacao); // Primeiro tira o ponto
            // $pontuacao = str_replace(',', '.', $pontuacao); // Troca a vírgula por ponto

            // $promo_categorias = $_POST['promo_categorias'];
            // $promo_marcas     = $_POST['promo_marcas'];
            // $promo_produtos   = $_POST['promo_produtos'];
            // $participantes    = $_POST['participantes'];

            // $id_grupo_economico = ($participantes == 2) ? $_POST['id_grupo_economico'] : 0;
            // $id_loja            = ($participantes == 3) ? $_POST['id_loja'] : 0;
            // $id_grupo_usuarios  = ($participantes == 4) ? $_POST['id_grupo_usuarios'] : 0;  

            // $visualizacao_web   = $_POST['visualizacao_web'];

            $sql = "UPDATE bejobs_promo SET titulo='$titulo', descricao='$descricao', id_produto='$id_produto' WHERE id='$id'";
            $res = $dba->query($sql);

            if (!empty($_FILES['img']) || $_FILES['img']['size'] > 0) {
                $img     = $_FILES['img'];
                $destino = "../images/promo/" . $id . ".jpg";
                $ok      = upload($img, $destino, 2048, 2048);

                $img      = "../images/promo/" . $id . ".jpg";
                $thumb    = new m2brimagem("$img");
                $verifica = $thumb->valida();
                if ($verifica == "OK") {

                    $tipo      = "";
                    $dimensoes = getimagesize($img);
                    $largura   = $dimensoes[0];
                    $altura    = $dimensoes[1];
                    if ($largura > $altura) {
                        $tipo = "crop";
                    } elseif ($largura < $altura) {
                        $tipo = "fill";
                    } else {
                        $tipo = "crop";
                    }

                    // $thumb->redimensiona(850, 400, $tipo);
                    // $thumb->grava('../images/promo/'.$id.'_850x350.jpg', 80);

                    // $img   = "../images/promo/".$id.".jpg";                      
                    // $thumb = new m2brimagem("$img");

                    $thumb->redimensiona(600, 600, $tipo);
                    $thumb->grava('../images/promo/' . $id . '_600x600.jpg', 80);
                } else {
                    die($verifica);
                }
            }

            echo "success_edit";
            break;

        case 'ativar_promocao':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_promo SET status = 1 WHERE id = $id";
            $res = $dba->query($sql);

            header('Location: ./promocoes?msg=p004');

            break;

        case 'desativar_promocao':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_promo SET status = 0 WHERE id = $id";
            $res = $dba->query($sql);

            header('Location: ./promocoes?msg=p005');
            break;

            // case 'pontuacao_manual_cadastrar':
            //       if (empty($_POST['cpf'])) {echo "cpf"; exit;} 
            //       $cpfcnpj = new ValidaCPFCNPJ($_POST["cpf"]); 
            //       if (!$cpfcnpj->valida()) {echo "cpfcnpj_invalido"; exit; }

            //       if (empty($_POST['operacao'])) {echo "operacao"; exit;} 
            //       if (empty($_POST['pontos'])) {echo "pontos"; exit;}

            //       /*
            //        * 
            //        * Tipos de Operação: 
            //        * 1 = Adicionar Pontuação
            //        * 2 = Subtrair Pontuação
            //        *
            //       */

            //       $cpfcnpj          = $_POST['cpf'];
            //       $cpfcnpj          = preg_replace("/[^0-9]/", "", $cpfcnpj); // Retira formatação do CPF ou CNPJ
            //       $operacao         = $_POST['operacao'];
            //       $pontos           = $_POST['pontos'];
            //       $observacao       = addslashes($_POST['observacao']);
            //       $data_registro    = date('Y-m-d H:i:s');
            //       $ip_registro      = getIp();
            //       $usuario_registro = $_SESSION['app_user_id'];

            //       // Verifica se colaborador existe
            //       $sql = "SELECT id FROM bejobs_usuarios WHERE cpf='$cpfcnpj'";
            //       $query = $dba->query($sql);
            //       $qntd = $dba->rows($query);
            //       if ($qntd != 0) {
            //             $vet = $dba->fetch($query);
            //             $id_usuario = $vet[0];

            //             // Verifica o tipo de operação
            //             if ($operacao == 1) { // Adiciona pontuação
            //             $sql2 = "UPDATE bejobs_usuarios SET app_pontos = app_pontos+$pontos WHERE id = $id_usuario"; 
            //             $dba->query($sql2);

            //             $sql3 = "INSERT INTO bejobs_pontuacao_manual (operacao, id_usuario, app_pontos, observacao, data_registro, ip_registro, usuario_registro) VALUES ($operacao, $id_usuario, $pontos, '$observacao', '$data_registro', '$ip_registro', $usuario_registro)"; 
            //             $dba->query($sql3);
            //             $protocolo = $dba->lastid();

            //             $sql4 = "INSERT INTO bejobs_usuarios_extrato (tipo_transacao, app_pontos, id_usuario, data, protocolo) VALUES (3, $pontos, $id_usuario, '$data_registro', $protocolo)"; 
            //             $dba->query($sql4);

            //             } elseif ($operacao == 2) { // Subtrai pontuação
            //                   $sql2 = "UPDATE bejobs_usuarios SET app_pontos = app_pontos-$pontos WHERE id = $id_usuario"; 
            //                   $dba->query($sql2);

            //                   $sql3 = "INSERT INTO bejobs_pontuacao_manual (operacao, id_usuario, app_pontos, observacao, data_registro, ip_registro, usuario_registro) VALUES ($operacao, $id_usuario, $pontos, '$observacao', '$data_registro', '$ip_registro', $usuario_registro)"; 
            //                   $dba->query($sql3);
            //                   $protocolo = $dba->lastid();

            //                   $sql4 = "INSERT INTO bejobs_usuarios_extrato (tipo_transacao, app_pontos, id_usuario, data, protocolo) VALUES (3, -$pontos, $id_usuario, '$data_registro', $protocolo)"; 
            //                   $dba->query($sql4);

            //             } else {
            //                   echo "operacao"; exit;
            //             }                

            //       } else {
            //             echo "usuario_invalido"; exit;
            //       }

            //       echo 'success';
            //       break;

        case 'termos_privacidade_editar':
            // $id = $_POST['id'];
            if (empty($_POST["texto"])) {
                echo "texto";
                exit;
            }

            $texto = addslashes($_POST['texto']);

            $sql = "UPDATE bejobs_termos_privacidade SET texto = '$texto' WHERE id = 1"; //die($sql);
            $dba->query($sql);

            // grava no log do sistema
            // logs('Editou cadastro administrador ID: '.$id);

            echo "success_edit";
            break;

        case 'regulamento_editar':
            // $id = $_POST['id'];
            if (empty($_POST["texto"])) {
                echo "texto";
                exit;
            }

            $texto = addslashes($_POST['texto']);

            $sql = "UPDATE bejobs_regulamento SET texto = '$texto' WHERE id = 1"; //die($sql);
            $dba->query($sql);

            // grava no log do sistema
            // logs('Editou cadastro administrador ID: '.$id);

            echo "success_edit";
            break;

        case 'aditivos_regulamento_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['texto'])) {
                echo "texto";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $data_registro = date('Y-m-d H:i:s');
            $ip_registro = getIp();
            $usuario_registro = $_SESSION['app_user_id'];

            $sql = "INSERT INTO bejobs_aditivos_regulamento (titulo, texto, data_registro, ip_registro, usuario_registro) VALUES ('$titulo', '$texto', '$data_registro', '$ip_registro', $usuario_registro)";
            $dba->query($sql);

            echo "success";
            break;

        case 'aditivos_regulamento_editar':
            $idn = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['texto'])) {
                echo "texto";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);

            $sql = "UPDATE bejobs_aditivos_regulamento SET titulo='$titulo', texto='$texto' WHERE id=$idn";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'aditivos_regulamento_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_aditivos_regulamento WHERE id = $idn";
            $dba->query($sql);

            header('Location: ./aditivos-regulamento?msg=ar03');
            break;

        case 'aditivos_regulamento_desativar':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_aditivos_regulamento SET status = 0 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./aditivos-regulamento?msg=ar04');
            break;

        case 'aditivos_regulamento_ativar':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_aditivos_regulamento SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./aditivos-regulamento?msg=ar05');
            break;

        case 'chamados_categorias_cadastrar':
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "INSERT INTO bejobs_chamados_categorias (titulo) VALUES ('$titulo')";
            $dba->query($sql);

            echo "success";
            break;

        case 'chamados_categorias_editar':
            $id = $_POST['id'];
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "UPDATE bejobs_chamados_categorias SET titulo='$titulo' WHERE id = $id";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'chamados_categorias_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_chamados_categorias WHERE id = $idn";
            $dba->query($sql);

            header('Location: ./chamados-categorias?msg=cc03');
            break;

            // case 'produtos_cadastrar':         
            //       if (empty($_FILES['file']) || $_FILES['file']['size'] == 0) {echo "file"; exit;}
            //        //array de extensões permitidas 
            //       $allowedExts = array(".zip", ".ZIP");
            //       //Atribui uma array com os nomes dos arquivos à variável
            //       $name = $_FILES['file']['name'];
            //       $ext = strtolower(substr($name,-4));
            //       //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            //       if(!in_array($ext, $allowedExts)) {echo "file_zip"; exit;} 

            //       move_uploaded_file($_FILES['file']['tmp_name'], "./uploads/tmp/".$name);

            //       $z = new ZipArchive();
            //       $z->open("./uploads/tmp/".$name); // Abrindo arquivo para leitura/escrita

            //       $extract = $z->extractTo('./uploads/tmp/', array('produtos_ded.csv')); // Extraindo apenas o arquivo estoque.csv

            //       if ($extract === true) { // Verifica se foi extraído o arquivo estoque.csv
            //             if (($handle = fopen("./uploads/tmp/produtos_ded.csv", "r")) !== FALSE) { // Abre arquivo csv
            //                   while (($data = fgetcsv($handle, 0, "\n")) !== FALSE) { // Verifica enquanto houver registro do csv 
            //                         $dados = $data[0]; // Linha do arquivo atual
            //                         $array = explode(";", $dados); // Separa colunas por ;
            //                         $id_produto = addslashes(trim($array[0])); // Código do produto
            //                         if (is_numeric($id_produto)) {                                    
            //                               $titulo = addslashes(trim($array[1])); // Título do produto
            //                               $id_grupo = addslashes(trim($array[2])); // Id grupo
            //                               $grupo = addslashes(trim($array[3])); // Título do grupo    
            //                               $id_subgrupo = addslashes(trim($array[4])); // Id subgrupo
            //                               $subgrupo = addslashes(trim($array[5])); // Título do subgrupo    

            //                               // Verifica se já existe registro do produto no bd
            //                               $sql = "SELECT id FROM app_produtos2 WHERE id = $id_produto";
            //                               $query = $dba->query($sql);
            //                               $qntd = $dba->rows($query);
            //                               if ($qntd == 0) { 
            //                                     // Grava registro no bd
            //                                     $sql2 = "INSERT INTO app_produtos2 (id, titulo, id_grupo, grupo, id_subgrupo, subgrupo) VALUES ($id_produto, '$titulo', $id_grupo, '$grupo', $id_subgrupo, '$subgrupo')"; //print_r($sql2);
            //                                     $dba->query($sql2);

            //                               } else {
            //                                     // Atualiza registro no bd
            //                                     $sql2 = "UPDATE app_produtos2 SET titulo='$titulo', id_grupo=$id_grupo, grupo='$grupo', id_subgrupo=$id_subgrupo, subgrupo='$subgrupo' WHERE id=$id_produto"; //print_r($sql2);
            //                                     $dba->query($sql2);
            //                               }
            //                         }
            //                   }                  
            //             } else {
            //                   echo "read_csv";
            //                   exit;
            //             }

            //             $z->close(); // fecha o arquivo .zip
            //             fclose($handle); // fecha arquivo csv

            //       } else {
            //           echo "file_csv";
            //           exit;
            //       }

            //       unlink("./uploads/tmp/".$name);
            //       unlink("./uploads/tmp/produtos_ded.csv");

            //       echo "success"; 
            //       break;
        case 'grupo_taxas_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            // if (empty($_POST['status_bonificacao']) || $_POST['status_bonificacao']=="") {echo "status_bonificacao"; exit;}   

            $titulo = addslashes($_POST['titulo']);
            $observacoes = addslashes($_POST['observacoes']);
            // $status_bonificacao = $_POST['status_bonificacao'];
            $usuario_registro = $_SESSION['app_user_id'];

            $sql = "INSERT INTO bejobs_grupos_taxas (titulo, observacoes, usuario_registro, data_registro) VALUES ('$titulo', '$observacoes', $usuario_registro, NOW())";
            $dba->query($sql);

            echo "success";
            break;

        case 'grupo_taxas_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            $titulo = addslashes($_POST['titulo']);
            $observacoes = addslashes($_POST['observacoes']);
            $sql = "UPDATE bejobs_grupos_taxas SET titulo='$titulo', observacoes='$observacoes' WHERE id = $id";
            $dba->query($sql);
            echo "success_edit";
            break;

        case 'grupos_taxas_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_grupos_taxas WHERE id = $idn";
            $dba->query($sql);

            $sql = "DELETE FROM bejobs_grupos_taxas WHERE id = $idn";
            $dba->query($sql);

            header('Location: ./grupos-taxas?msg=g003');
            break;


        case 'form-grupos-taxas-vincular-individual':
            if (empty($_POST['estado'])) {
                echo "estado";
                exit;
            }
            if (empty($_POST['cidade']) && !isset($_POST['cidade'])) {
                echo "cidade";
                exit;
            }

            $id_grupo = $_POST['grupo_usuarios']; 

            $sql1   = "SELECT id FROM bejobs_usuarios WHERE cpf = '$cpf'";
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                $vet1 = $dba->fetch($query1);
                $id_usuario = $vet1[0];
            } else {
                echo "cpf_invalido";
                exit;
            }

            // Verifica se usuário está vinculado a algum grupo de usuários
            $sql1 = "SELECT * FROM bejobs_grupos_usuarios WHERE id_usuarios = $id_usuario AND id_grupos = $id_grupo";
            $query1 = $dba->query($sql1);
            $qntd1 = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "grupo_usuarios_exists";
                exit;
            }

            $sql2 = "INSERT INTO bejobs_grupos_usuarios (id_usuarios, id_grupos) VALUES ($id_usuario, $id_grupo)";
            $dba->query($sql2);

            echo "success";
            break;
        case 'grupo_usuarios_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            // if (empty($_POST['status_bonificacao']) || $_POST['status_bonificacao']=="") {echo "status_bonificacao"; exit;}   

            $titulo = addslashes($_POST['titulo']);
            $observacoes = addslashes($_POST['observacoes']);
            // $status_bonificacao = $_POST['status_bonificacao'];
            $usuario_registro = $_SESSION['app_user_id'];

            $sql = "INSERT INTO bejobs_grupos (titulo, observacoes, usuario_registro, data_registro) VALUES ('$titulo', '$observacoes', $usuario_registro, NOW())";
            $dba->query($sql);

            echo "success";
            break;

        case 'grupo_usuarios_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            // if (empty($_POST['status_bonificacao']) || $_POST['status_bonificacao']=="") {echo "status_bonificacao"; exit;}   

            $titulo = addslashes($_POST['titulo']);
            $observacoes = addslashes($_POST['observacoes']);
            // $status_bonificacao = $_POST['status_bonificacao'];

            $sql = "UPDATE bejobs_grupos SET titulo='$titulo', observacoes='$observacoes' WHERE id = $id";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'grupos_usuarios_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_grupos WHERE id = $idn";
            $dba->query($sql);

            $sql = "DELETE FROM bejobs_grupos_usuarios WHERE id_grupos = $idn";
            $dba->query($sql);

            header('Location: ./grupos?msg=g003');
            break;

        case 'grupo_usuarios_vincular_individual':
            if (empty($_POST['grupo_usuarios'])) {
                echo "grupo_usuarios";
                exit;
            }
            if (empty($_POST['cpf']) && !isset($_POST['cpf'])) {
                echo "cpf";
                exit;
            }

            $id_grupo = $_POST['grupo_usuarios'];
            $cpf      = preg_replace("/[^0-9]/", "", $_POST['cpf']); // Retira formatação do CPF ou CNPJ

            $sql1   = "SELECT id FROM bejobs_usuarios WHERE cpf = '$cpf'";
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                $vet1 = $dba->fetch($query1);
                $id_usuario = $vet1[0];
            } else {
                echo "cpf_invalido";
                exit;
            }

            // Verifica se usuário está vinculado a algum grupo de usuários
            $sql1 = "SELECT * FROM bejobs_grupos_usuarios WHERE id_usuarios = $id_usuario AND id_grupos = $id_grupo";
            $query1 = $dba->query($sql1);
            $qntd1 = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "grupo_usuarios_exists";
                exit;
            }

            $sql2 = "INSERT INTO bejobs_grupos_usuarios (id_usuarios, id_grupos) VALUES ($id_usuario, $id_grupo)";
            $dba->query($sql2);

            echo "success";
            break;

        case 'usuarios_grupos_delete':
            $id_grupo = $_POST['id_grupo'];
            $usuarios = $_POST['usuarios'];
            $max = sizeof($usuarios);

            for ($i = 0; $i < $max; $i++) {
                $sql = "DELETE FROM bejobs_grupos_usuarios where id = " . $usuarios[$i] . "";
                $dba->query($sql);
            }

            header('Location: ./grupos-usuarios?id=' . $id_grupo . '&msg=g004');
            break;

        case 'grupos_usuarios_export':

            if (empty($_POST["grupo_usuarios"]) || empty($_POST["formato"])) {
                header('Location: ./grupos');
            }

            if ($_POST['formato'] == 'xls') {
                $grupo_usuarios = $_POST['grupo_usuarios'];

                $header = "Nome" . "\t" . "Email" . "\t" . "CPF" . "\t" . "Telefone Celular" . "\t";
                $data   = "";
                $sql    = "SELECT * FROM bejobs_grupos_usuarios WHERE id_grupos = $grupo_usuarios";
                $query  = $dba->query($sql);
                $qntd   = $dba->rows($query);
                if ($qntd > 0) {
                    for ($i = 0; $i < $qntd; $i++) {
                        $vet = $dba->fetch($query);
                        $id_usuario = $vet['id_usuarios'];

                        $sql5   = "SELECT nome, cpf, email, telefone_celular FROM bejobs_usuarios WHERE id = $id_usuario";
                        $query5 = $dba->query($sql5);
                        $vet5   = $dba->fetch($query5);
                        $nome   = $vet5[0];
                        $cpf    = $vet5[1];
                        $email  = $vet5[2];
                        $telefone_celular = $vet5[3];

                        $data .= '"' . $nome . '"' . "\t";
                        $data .= '"' . $email . '"' . "\t";
                        $data .= '"' . $cpf . '"' . "\t";
                        $data .= '"' . $telefone_celular . '"' . "\t";
                        $data .= "\n";
                    }
                }

                $sql2   = "SELECT titulo FROM bejobs_grupos WHERE id = $grupo_usuarios";
                $query2 = $dba->query($sql2);
                $vet2   = $dba->fetch($query2);
                $titulo_grupo = $vet2[0];

                header("Content-Type: application/x-msexcel; charset=UTF-8");
                header("Content-Disposition: attachment; filename=" . urlMaker($titulo_grupo) . "-" . date('d-m-Y') . ".xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo utf8_decode($header) . "\n" . utf8_decode($data) . "\n";
            } else {
                header('Location: ./grupos');
            }
            break;

        case 'usuarios_grupos_importacao':
            if (empty($_POST['grupo_usuarios']) || $_POST['grupo_usuarios'] == "") {
                echo "grupo_usuarios";
                exit;
            }
            $id_grupo = $_POST['grupo_usuarios'];

            if (empty($_FILES['file']) || $_FILES['file']['size'] == 0) {
                echo "file";
                exit;
            }
            //array de extensões permitidas 
            $allowedExts = array(".zip", ".ZIP");
            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['file']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "file_zip";
                exit;
            }

            move_uploaded_file($_FILES['file']['tmp_name'], "./uploads/tmp/" . $name);

            $z = new ZipArchive();
            $z->open("./uploads/tmp/" . $name); // Abrindo arquivo para leitura/escrita

            $extract = $z->extractTo('./uploads/tmp/', array('usuarios_grupo.csv')); // Extraindo apenas o arquivo usuarios_feito.csv

            if ($extract === true) { // Verifica se foi extraído o arquivo usuarios_feito.csv
                if (($handle = fopen("./uploads/tmp/usuarios_grupo.csv", "r")) !== FALSE) { // Abre arquivo csv
                    while (($data = fgetcsv($handle, 0, "\n")) !== FALSE) { // Verifica enquanto houver registro do csv 
                        $dados = $data[0]; // Linha do arquivo atual
                        $array = explode(";", $dados); // Separa colunas por ;

                        $cpf = preg_replace("/[^0-9]/", "", $array[0]); // Limpa formatação CPF/CNPJ

                        $sql1   = "SELECT id FROM bejobs_usuarios WHERE cpf = '$cpf'";
                        $query1 = $dba->query($sql1);
                        $qntd1  = $dba->rows($query1);
                        if ($qntd1 > 0) {
                            $vet1 = $dba->fetch($query1);
                            $id_usuario = $vet1[0];

                            // Verifica se usuário já está vinculado ao grupo de usuários
                            $sql1   = "SELECT * FROM bejobs_grupos_usuarios WHERE id_usuarios = $id_usuario AND id_grupos = $id_grupo";
                            $query1 = $dba->query($sql1);
                            $qntd1  = $dba->rows($query1);
                            if ($qntd1 == 0) {
                                $sql2 = "INSERT INTO bejobs_grupos_usuarios (id_usuarios, id_grupos) VALUES ($id_usuario, $id_grupo)";
                                $dba->query($sql2);
                            }
                        }
                    }
                } else {
                    echo "read_csv";
                    exit;
                }

                $z->close(); // fecha o arquivo .zip
                fclose($handle); // fecha arquivo csv

            } else {
                echo "file_csv";
                exit;
            }

            unlink("./uploads/tmp/" . $name);
            unlink("./uploads/tmp/usuarios_grupo.csv");

            echo "success";
            break;

        case 'chamados_cadastrar':
            // if (empty($_POST["tipo_de_usuario"]) || $_POST["tipo_de_usuario"] == "" || !is_numeric($_POST["tipo_de_usuario"])) { echo "tipo_de_usuario"; exit; }
            if (empty($_POST["cpfcnpj"])) {
                echo "cpfcnpj";
                exit;
            }

            // $tipo_usuario = $_POST["tipo_de_usuario"];
            $cpfcnpj = $_POST["cpfcnpj"];
            $cpfcnpj = preg_replace("/[^0-9]/", "", $cpfcnpj);

            $sql4 = "SELECT id FROM bejobs_usuarios WHERE cpf = '$cpfcnpj'";
            $query4 = $dba->query($sql4);
            $qntd4 = $dba->rows($query4);
            if ($qntd4 > 0) {
                $vet4 = $dba->fetch($query4);
                $id_usuario = $vet4[0];
            } else {
                echo "usuario_invalido";
                exit;
            }

            if (empty($_POST["assunto"])) {
                echo "assunto";
                exit;
            }
            if (empty($_POST["categoria"]) || $_POST["categoria"] == "" || !is_numeric($_POST["categoria"])) {
                echo "categoria";
                exit;
            }
            if (empty($_POST["mensagem"])) {
                echo "mensagem";
                exit;
            }

            $anexo = "";
            // Verifica se foi enviado algum arquivo
            if (!empty($_FILES['anexo'])) {
                $arquivo = $_FILES['anexo']['name'];
                $extensoes_invalidas = array("exe");
                // Verifica se extensão do arquivo está presente no array de extenções inválidas
                if (in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $extensoes_invalidas)) {
                    echo "arquivo_extensao";
                    exit;
                }
                $diretorio_upload = "../files/chamados/" . date("Y") . "/" . date("m") . "/" . date("d") . "/"; // Cria diretório com data atual

                if (!is_dir($diretorio_upload)) { // Verifica se já existe o diretório
                    mkdir($diretorio_upload, 0775, true); // Cria o diretório
                    chmod($diretorio_upload, 0775);
                }

                // Move arquivo para pasta selecionada
                move_uploaded_file($_FILES["anexo"]["tmp_name"], $diretorio_upload . $arquivo);
                // Caminho do arquivo anexo
                $anexo = str_replace("../", "", $diretorio_upload) . $arquivo;
            }

            $assunto = addslashes(trim($_POST["assunto"]));
            $categoria = $_POST["categoria"];
            $mensagem = addslashes(trim($_POST["mensagem"]));
            $telefone = addslashes(trim($_POST["telefone"]));
            $ip_registro  = getIp();

            $sql = "INSERT INTO bejobs_chamados (assunto, mensagem, id_categoria, telefone, id_usuario, status, data_registro, ip_registro, anexo) VALUES ('$assunto', '$mensagem', $categoria, '$telefone', $id_usuario, 1, NOW(), '$ip_registro', '$anexo')"; //die($sql);
            $dba->query($sql);

            echo "success";
            break;

        case 'chamados_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_chamados WHERE id = $idn";
            $dba->query($sql);

            $sql = "DELETE FROM bejobs_chamados_interacao WHERE id_chamado = $idn";
            $dba->query($sql);

            header('Location: ./chamados?msg=ch003');
            break;

        case 'chamados_interacao_cadastrar':
            $tipo_usuario = 4;
            $id_usuario   = $_SESSION['app_user_id'];
            $ip_registro  = getIp();

            if (empty($_POST["status_chamado"])) {
                echo "status_chamado";
                exit;
            }
            if (empty($_POST["id_chamado"]) || !is_numeric($_POST["id_chamado"])) {
                echo "error_";
                exit;
            }
            if (empty($_POST["mensagem"])) {
                echo "mensagem";
                exit;
            }

            $anexo = "";
            // Verifica se foi enviado algum arquivo
            if (!empty($_FILES['anexo'])) {
                $arquivo = $_FILES['anexo']['name'];
                $extensoes_invalidas = array("exe");
                // Verifica se extensão do arquivo está presente no array de extenções inválidas
                if (in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $extensoes_invalidas)) {
                    echo "arquivo_extensao";
                    exit;
                }
                $diretorio_upload = "../files/interacao/" . date("Y") . "/" . date("m") . "/" . date("d") . "/"; // Cria diretório com data atual

                if (!is_dir($diretorio_upload)) { // Verifica se já existe o diretório
                    mkdir($diretorio_upload, 0775, true); // Cria o diretório
                    chmod($diretorio_upload, 0775);
                }

                // Move arquivo para pasta selecionada
                move_uploaded_file($_FILES["anexo"]["tmp_name"], $diretorio_upload . $arquivo);
                // Caminho do arquivo anexo
                $anexo = str_replace("../", "", $diretorio_upload) . $arquivo;
            }

            $id_chamado = $_POST["id_chamado"];
            $mensagem = addslashes(trim($_POST["mensagem"]));
            $status_chamado = $_POST["status_chamado"];

            $sql = "INSERT INTO bejobs_chamados_interacao (mensagem, tipo_usuario, id_usuario, data_registro, ip_registro, anexo, id_chamado) VALUES ('$mensagem', $tipo_usuario, $id_usuario, NOW(), '$ip_registro', '$anexo', $id_chamado)"; //die($sql);
            $dba->query($sql);

            $sql2 = "UPDATE bejobs_chamados SET status = $status_chamado WHERE id = $id_chamado";
            $dba->query($sql2);

            $sql3 = "SELECT id_usuario, assunto FROM bejobs_chamados WHERE id = $id_chamado";
            // print_r($sql3);
            $query3 = $dba->query($sql3);
            $qntd3 = $dba->rows($query3);
            if ($qntd3 > 0) {
                $vet3 = $dba->fetch($query3);
                // $tipo_usuario = $vet3[0];
                $id_usuario = $vet3[0];
                $assunto = $vet3[1];

                $sql4 = "SELECT nome, email FROM bejobs_usuarios WHERE id = $id_usuario";
                $query4 = $dba->query($sql4);
                $qntd4 = $dba->rows($query4);
                if ($qntd4 > 0) {
                    $vet4 = $dba->fetch($query4);
                    $nome_usuario = addslashes($vet4[0]);
                    $email_usuario = addslashes($vet4[1]);
                }

                // Defini informações da notificação 

                // Grava nova notificação
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_usuario) VALUES (NOW(), '$notificacoes_titulo', '$notificacoes_texto', 1, $id_usuario)";
                $dba->query($sql2);
            }

            echo "success";
            break;

        case 'marcar_notificacao_lida':
            $id = $_GET['id'];

            $sql2 = "UPDATE bejobs_notificacoes_admin SET status = 2 WHERE id = $id";
            $dba->query($sql2);

            header('Location: ./notificacoes-visualizar?id=' . $id . '&msg=ntf001');
            break;

        case 'marcar_mensagem_lida':
            $id = $_GET['id'];

            $sql2 = "UPDATE bejobs_mensagens_faleconosco SET status = 2 WHERE id = $id";
            $dba->query($sql2);

            header('Location: ./mensagens-visualizar?id=' . $id . '&msg=msg001');
            break;

        case 'mensagens_usuarios_cadastrar':
            if (trim(empty($_POST['titulo']))) {
                echo "titulo";
                exit;
            }
            if (trim(empty($_POST['texto']))) {
                echo "texto";
                exit;
            }

            if (!isset($_POST['participantes'])) {
                echo "participantes";
                exit;
            }

            $participantes = $_POST['participantes'];

            $usuarios_proclube   = 0;
            $grupos_economicos   = 0;
            $lojas               = 0;
            $grupos_usuarios     = 0;
            $usuarios_individual = 0;
            $usuarios_reputacao  = 0;

            if ($participantes == 1) { // Todos usuários ProClube
                $usuarios_proclube = 1;
            } elseif ($participantes == 2) { // Grupos Econômicos
                if (!isset($_POST['grupos_economicos'])) {
                    echo "grupos_economicos";
                    exit;
                }

                $grupos_economicos  = 1;
                $grupos_economicos_ = $_POST['grupos_economicos'];
            } elseif ($participantes == 3) { // Lojas
                if (!isset($_POST['lojas'])) {
                    echo "lojas";
                    exit;
                }

                $lojas  = 1;
                $lojas_ = $_POST['lojas'];
            } elseif ($participantes == 4) { // Grupos de Usuários
                if (empty($_POST['id_grupos_usuarios'])) {
                    echo "grupo_usuarios";
                    exit;
                }

                $grupos_usuarios   = 1;
                $id_grupos_usuarios = $_POST['id_grupos_usuarios'];
            } elseif ($participantes == 5) { // Usuários Individual
                if (empty($_POST['id_usuario'])) {
                    echo "usuario_individual";
                    exit;
                }

                $usuarios_individual = 1;
                $id_usuario         = $_POST['id_usuario'];
            } elseif ($participantes == 6) { // Usuários Reputação
                if (empty($_POST['usuarios_reputacao'])) {
                    echo "usuarios_reputacao";
                    exit;
                }

                $usuarios_reputacao = 1;
                $usuarios_reputacao_ = $_POST['usuarios_reputacao'];
            }


            $titulo           = addslashes($_POST['titulo']);
            $texto            = addslashes($_POST['texto']);
            $data_registro    = date('Y-m-d H:i:s');
            $ip_registro      = getIp();
            $usuario_registro = $_SESSION['app_user_id'];

            $sql = "INSERT INTO bejobs_mensagens_usuarios (titulo, texto, usuarios_proclube, grupos_economicos, lojas, grupos_usuarios, usuarios_individual, usuarios_reputacao, data_registro, ip_registro, usuario_registro) 
                    VALUES ('$titulo', '$texto', $usuarios_proclube, $grupos_economicos, $lojas, $grupos_usuarios, $usuarios_individual, $usuarios_reputacao, '$data_registro', '$ip_registro', $usuario_registro)";
            $dba->query($sql);

            $id_mensagens_usuarios = $dba->lastid();


            if ($participantes == 4) {
                $max = sizeof($id_grupos_usuarios);

                $sql_grupos_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_mensagens_usuarios_grupos_usuarios (id_grupos_usuarios, id_mensagens_usuarios) VALUES (" . $id_grupos_usuarios[$i] . ", $id_mensagens_usuarios)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_grupos_usuarios .= "gu.id_grupos = " . $id_grupos_usuarios[$i];
                    } else {
                        $sql_grupos_usuarios .= " || gu.id_grupos = " . $id_grupos_usuarios[$i];
                    }
                }
            }

            if ($participantes == 5) {
                $max = sizeof($id_usuario);

                $sql_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_mensagens_usuarios_individual (id_usuarios, id_mensagens_usuarios) VALUES (" . $id_usuario[$i] . ", $id_mensagens_usuarios)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_usuarios .= "u.id = " . $id_usuario[$i];
                    } else {
                        $sql_usuarios .= " || u.id = " . $id_usuario[$i];
                    }
                }
            }

            if ($participantes == 1) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                           SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, id FROM bejobs_usuarios"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 2) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                            SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, u.id 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_lojas AS l
                            WHERE u.lojas_cnpj = l.cnpj 
                            AND ($sql_grupos_economicos)"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 3) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                            SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, u.id 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_lojas AS l
                            WHERE u.lojas_cnpj = l.cnpj 
                            AND ($sql_lojas)"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 4) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                            SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, u.id 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_grupos_usuarios AS gu
                            WHERE u.id = gu.id_usuarios 
                            AND ($sql_grupos_usuarios)
                            GROUP BY gu.id_usuarios";
                // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 5) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                            SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, u.id 
                            FROM bejobs_usuarios AS u
                            WHERE $sql_usuarios"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 6) {
                $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_mensagens_usuarios, id_usuario) 
                            SELECT '$data_registro', '$titulo', '$texto', 1, $id_mensagens_usuarios, u.id 
                            FROM bejobs_usuarios AS u
                            WHERE $sql_usuarios_reputacao"; // print_r($sql2);
                $dba->query($sql2);
            }

            if (!empty($_FILES['img'])) {
                // $ide = $dba->lastid();
                $img = $_FILES['img'];
                $destino = "../images/mensagens/" . $id_mensagens_usuarios . ".jpg";
                //upload($img, $destino, 1920, 1280);
                move_uploaded_file($_FILES['img']['tmp_name'], $destino); //Fazer upload do arquivo

                $img = "../images/mensagens/" . $id_mensagens_usuarios . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $tipo = "";
                    $dimensoes = getimagesize($img);
                    $largura = $dimensoes[0];
                    $altura = $dimensoes[1];

                    if ($largura > $altura) {
                        $tipo = "crop";
                    } elseif ($largura < $altura) {
                        $tipo = "fill";
                    } else {
                        $tipo = "crop";
                    }

                    $thumb->redimensiona(780, 520, $tipo);
                    $thumb->grava('../images/mensagens/' . $id_mensagens_usuarios . '_780x520.jpg', 100);

                    $img = "../images/mensagens/" . $id_mensagens_usuarios . ".jpg";
                    $thumb = new m2brimagem("$img");

                    $thumb->redimensiona(500, 500, $tipo);
                    $thumb->grava('../images/mensagens/' . $id_mensagens_usuarios . '_500x500.jpg', 100);
                } else {
                    die($verifica);
                }
            }

            echo "success";
            break;

        case 'faq_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['texto'])) {
                echo "texto";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);
            $texto  = addslashes($_POST['texto']);

            $sql = "INSERT INTO bejobs_faq (titulo, texto) VALUES ('$titulo', '$texto') "; //die($sql);          
            $dba->query($sql);

            echo "success";
            break;

        case 'faq_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_faq where id = '$idn'";
            $dba->query($sql);

            header('location: faq?msg=f003');
            break;
        case 'anuncio_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_anuncios where id = '$idn'";
            $dba->query($sql);

            header('location: anuncios?msg=anun001');
            break;
        case 'faq_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['texto'])) {
                echo "texto";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);

            $sql = "UPDATE bejobs_faq SET titulo='$titulo', texto='$texto' WHERE id = $id"; //die($sql);           
            $dba->query($sql);

            echo "success_edit";
            break;

            // Blog
        case 'news_cadastrar':
            if (empty($_POST['data']) || !validaData($_POST['data'])) {
                echo "data";
                exit;
            }
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            //if (empty($_POST['categoria']) || $_POST['categoria']=='') {echo "categoria";exit;}
            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if (!empty($_FILES['img'])) {
                // if ($_FILES['img']['size'] > 2097152) {echo "invalidimgsize";exit;} 
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            if (!empty($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img1']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            if (!empty($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img2']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $data = dataMY($_POST['data']);
            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            //$categoria = $_POST['categoria'];
            $categoria = 0;
            $tags = addslashes($_POST['tags']);
            $link = addslashes($_POST['link']);

            $sql = "INSERT INTO bejobs_news (data, titulo, texto, link, tags, id_categorias) values ('$data', '$titulo', '$texto', '$link', '$tags', $categoria)"; //die($sql);          
            $res = $dba->query($sql);

            if (!empty($_FILES['img'])) {
                $ide = $dba->lastid();
                $img = $_FILES['img'];
                $destino = "../images/news/" . $ide . ".jpg";
                //upload($img, $destino, 1920, 1280);
                move_uploaded_file($_FILES['img']['tmp_name'], $destino); //Fazer upload do arquivo

                $img = "../images/news/" . $ide . ".jpg";
                $thumb = new m2brimagem("$img");
                //AQUI ESTA O ERRO! 
                $verifica = $thumb->valida();
                if ($verifica == "OK") {
                    $tipo = "";
                    $dimensoes = getimagesize($img);
                    $largura = $dimensoes[0];
                    $altura = $dimensoes[1];

                    if ($largura > $altura) {
                        $tipo = "crop";
                    } elseif ($largura < $altura) {
                        $tipo = "fill";
                    } else {
                        $tipo = "crop";
                    }

                    $thumb->redimensiona(780, 520, $tipo);
                    $thumb->grava('../images/news/' . $ide . '_780x520.jpg', 100);

                    $img = "../images/news/" . $ide . ".jpg";
                    $thumb = new m2brimagem("$img");

                    $thumb->redimensiona(500, 500, $tipo);
                    $thumb->grava('../images/news/' . $ide . '_500x500.jpg', 100);
                } else {
                    die($verifica);
                }
            }
            echo "success";
            break;
        case 'news_delete_img':
            $id = $_REQUEST['id'];
            $imagem = '../images/news/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_780x520.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_500x500.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("location: news-editar?id=$id");
            break;



        case 'news_editar':
            $ide = $_POST['id'];
            if (empty($_POST['data']) || !validaData($_POST['data'])) {
                echo "data";
                exit;
            }
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            //if (empty($_POST['categoria']) || $_POST['categoria']=='') {echo "categoria";exit;}
            if (!file_exists('../images/news/' . $ide . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                if (!empty($_FILES['img'])) {
                    // if ($_FILES['img']['size'] > 2097152) {echo "invalidimgsize";exit;} 
                    //array de extensões permitidas 
                    $allowedExts = array(".jpg", ".JPG");
                    //Atribui uma array com os nomes dos arquivos à variável
                    $name = $_FILES['img']['name'];
                    $ext = strtolower(substr($name, -4));
                    //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                    if (!in_array($ext, $allowedExts)) {
                        echo "invalidimg";
                        exit;
                    }
                }
            }

            if (!empty($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img1']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            if (!empty($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img2']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $data = dataMY($_POST['data']);
            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            //$categoria = $_POST['categoria'];
            $categoria = 0;
            $tags = addslashes($_POST['tags']);
            $link = addslashes($_POST['link']);

            $sql = "UPDATE bejobs_news SET data='$data', texto='$texto', titulo='$titulo', link='$link', tags='$tags', id_categorias='$categoria' WHERE id =$ide"; //die($sql);          
            $dba->query($sql);

            if (!file_exists('../images/news/' . $ide . '.jpg')) {
                if (!empty($_FILES['img'])) {
                    $img = $_FILES['img'];
                    $destino = "../images/news/" . $ide . ".jpg";
                    //upload($img, $destino, 1920, 1280);
                    move_uploaded_file($_FILES['img']['tmp_name'], $destino); //Fazer upload do arquivo

                    $img = "../images/news/" . $ide . ".jpg";
                    $thumb = new m2brimagem("$img");
                    $verifica = $thumb->valida();

                    if ($verifica == "OK") {
                        $tipo = "";
                        $dimensoes = getimagesize($img);
                        $largura = $dimensoes[0];
                        $altura = $dimensoes[1];

                        if ($largura > $altura) {
                            $tipo = "crop";
                        } elseif ($largura < $altura) {
                            $tipo = "fill";
                        } else {
                            $tipo = "crop";
                        }

                        $thumb->redimensiona(780, 520, $tipo);
                        $thumb->grava('../images/news/' . $ide . '_780x520.jpg', 100);

                        $img = "../images/news/" . $ide . ".jpg";
                        $thumb = new m2brimagem("$img");

                        $thumb->redimensiona(500, 500, $tipo);
                        $thumb->grava('../images/news/' . $ide . '_500x500.jpg', 100);
                    } else {
                        die($verifica);
                    }
                }
            }
            echo "success_edit";
            break;

        case 'news_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM bejobs_news WHERE id = $id";
            $dba->query($sql);

            $imagem = '../images/news/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_780x520.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_500x500.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_1.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/news/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('location: news?msg=n003');
            break;

        case 'ativar_news':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_news SET status = 1 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./news?msg=n004');

            break;

        case 'desativar_news':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_news SET status = 0 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./news?msg=n005');
            break;

            // case 'reputacao_usuarios_editar':  
            //       $id = $_POST['id'];           
            //       if (empty($_POST['pontuacao'])) {echo "pontuacao"; exit;}            
            //       $pontuacao = $_POST['pontuacao']; 
            //       $pontuacao = str_replace('.', '', $pontuacao); // Primeiro tira o ponto
            //       $pontuacao = str_replace(',', '.', $pontuacao); // Troca a vírgula por ponto

            //       $sql = "UPDATE bejobs_usuarios_reputacao SET pontuacao='$pontuacao' WHERE id = $id";
            //       $dba->query($sql);

            //       echo "success";                            
            //       break;

            // case 'segmentacao_lojista_editar':  
            //       $id_segmentacao = $_POST['id_segmentacao'];  

            //       if (empty($_POST['pontuacao'])) {echo "pontuacao"; exit;}            
            //       $pontuacao = $_POST['pontuacao']; 
            //       $pontuacao = str_replace('.', '', $pontuacao); // Primeiro tira o ponto
            //       $pontuacao = str_replace(',', '.', $pontuacao); // Troca a vírgula por ponto

            //       $sql   = "SELECT id, id_segmentacao, pontuacao FROM bejobs_lojista_segmentacao WHERE id_segmentacao = $id_segmentacao";
            //       $query = $dba->query($sql);
            //       $qntd  = $dba->rows($query);
            //       if ($qntd > 0) {

            //             $sql = "UPDATE bejobs_lojista_segmentacao SET pontuacao='$pontuacao' WHERE id_segmentacao = $id_segmentacao";
            //             $dba->query($sql);

            //       } else {

            //             $sql = "INSERT INTO bejobs_lojista_segmentacao (pontuacao, id_segmentacao) VALUES ('$pontuacao', $id_segmentacao)";
            //             $dba->query($sql);
            //       }

            //       echo "success";                            
            //       break;

            // case 'usuarios_recargas_resgate':  
            //       $id = 1; 
            //       if (empty($_POST['valor'])) {echo "valor"; exit;} 
            //       if (empty($_POST['pontuacao']) && is_int($_POST['pontuacao'])) {echo "pontuacao"; exit;}     

            //       $valor = $_POST['valor']; 
            //       $valor = str_replace('.', '', $valor); // Primeiro tira o ponto
            //       $valor = str_replace(',', '.', $valor); // Troca a vírgula por ponto
            //       $pontuacao = $_POST['pontuacao'];            

            //       $sql = "UPDATE bejobs_recargas_resgate SET valor='$valor', pontuacao='$pontuacao' WHERE id = $id";
            //       $dba->query($sql);

            //       echo "success";                            
            //       break;

        case 'valor_minimo_pediddo':
            $id = 1;
            if (empty($_POST['valor'])) {
                echo "valor";
                exit;
            }
            // if (empty($_POST['pontuacao']) && is_int($_POST['pontuacao'])) {echo "pontuacao"; exit;}     

            $valor = $_POST['valor'];
            $valor = str_replace('.', '', $valor); // Primeiro tira o ponto
            $valor = str_replace(',', '.', $valor); // Troca a vírgula por ponto
            // $pontuacao = $_POST['pontuacao'];            

            $sql = "UPDATE bejobs_valor_minimo_pedido SET valor='$valor' WHERE id = $id";
            $dba->query($sql);

            echo "success";
            break;

        case 'sms_cadastrar':
            if (trim(empty($_POST['titulo']))) {
                echo "titulo";
                exit;
            }
            if (trim(empty($_POST['texto']))) {
                echo "texto";
                exit;
            }

            if (!isset($_POST['participantes'])) {
                echo "participantes";
                exit;
            }

            $participantes = $_POST['participantes'];

            $usuarios_proclube   = 0;
            $grupos_economicos   = 0;
            $lojas               = 0;
            $grupos_usuarios     = 0;
            $usuarios_individual = 0;
            $usuarios_reputacao  = 0;

            if ($participantes == 1) { // Todos usuários ProClube
                $usuarios_proclube = 1;
            } elseif ($participantes == 2) { // Grupos Econômicos
                if (empty($_POST['grupos_economicos'][0])) {
                    echo "grupos_economicos";
                    exit;
                }

                $grupos_economicos  = 1;
                $grupos_economicos_ = $_POST['grupos_economicos'];
            } elseif ($participantes == 3) { // Lojas
                if (empty($_POST['lojas'][0])) {
                    echo "lojas";
                    exit;
                }

                $lojas  = 1;
                $lojas_ = $_POST['lojas'];
            } elseif ($participantes == 4) { // Grupos de Usuários
                if (empty($_POST['id_grupos_usuarios'][0])) {
                    echo "grupo_usuarios";
                    exit;
                }

                $grupos_usuarios   = 1;
                $id_grupos_usuarios = $_POST['id_grupos_usuarios'];
            } elseif ($participantes == 5) { // Usuários Individual
                if (empty($_POST['id_usuario'][0])) {
                    echo "usuario_individual";
                    exit;
                }

                $usuarios_individual = 1;
                $id_usuario         = $_POST['id_usuario'];
            } elseif ($participantes == 6) { // Usuários Reputação
                if (empty($_POST['usuarios_reputacao'])) {
                    echo "usuarios_reputacao";
                    exit;
                }

                $usuarios_reputacao = 1;
                $usuarios_reputacao_ = $_POST['usuarios_reputacao'];
                // print_r($usuarios_reputacao_);
                // exit;
            }

            $titulo           = addslashes($_POST['titulo']);
            $texto            = addslashes($_POST['texto']);
            $data_registro    = date('Y-m-d H:i:s');
            $ip_registro      = getIp();
            $usuario_registro = $_SESSION['app_user_id'];

            $sql = "INSERT INTO bejobs_sms (titulo, texto, usuarios_proclube, grupos_economicos, lojas, grupos_usuarios, usuarios_individual, usuarios_reputacao, data_registro, ip_registro, usuario_registro) VALUES ('$titulo', '$texto', $usuarios_proclube, $grupos_economicos, $lojas, $grupos_usuarios, $usuarios_individual, $usuarios_reputacao, '$data_registro', '$ip_registro', $usuario_registro)";
            $dba->query($sql);

            $id_sms   = $dba->lastid();
            $mensagem = urlencode($texto);

            if ($participantes == 2) {
                $max = sizeof($grupos_economicos_);

                $sql_grupos_economicos = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_sms_grupos_economicos (id_grupos_economicos, id_sms) VALUES (" . $grupos_economicos_[$i] . ", $id_sms)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_grupos_economicos .= "l.id_grupo = " . $grupos_economicos_[$i];
                    } else {
                        $sql_grupos_economicos .= " || l.id_grupo = " . $grupos_economicos_[$i];
                    }
                }
            }

            if ($participantes == 3) {
                $max = sizeof($lojas_);

                $sql_lojas = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_sms_lojas (id_loja, id_sms) VALUES (" . $lojas_[$i] . ", $id_sms)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_lojas .= "l.id = " . $lojas_[$i];
                    } else {
                        $sql_lojas .= " || l.id = " . $lojas_[$i];
                    }
                }
            }

            if ($participantes == 4) {
                $max = sizeof($id_grupos_usuarios);

                $sql_grupos_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_sms_grupos_usuarios (id_grupos_usuarios, id_sms) VALUES (" . $id_grupos_usuarios[$i] . ", $id_sms)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_grupos_usuarios .= "gu.id_grupos = " . $id_grupos_usuarios[$i];
                    } else {
                        $sql_grupos_usuarios .= " || gu.id_grupos = " . $id_grupos_usuarios[$i];
                    }
                }
            }

            if ($participantes == 5) {
                $max = sizeof($id_usuario);

                $sql_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_sms_usuarios_individual (id_usuarios, id_sms) VALUES (" . $id_usuario[$i] . ", $id_sms)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_usuarios .= "u.id = " . $id_usuario[$i];
                    } else {
                        $sql_usuarios .= " || u.id = " . $id_usuario[$i];
                    }
                }
            }

            if ($participantes == 6) {
                $max = sizeof($usuarios_reputacao_);

                $sql_usuarios_reputacao = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_sms_usuarios_reputacao (id_usuarios_reputacao, id_sms) VALUES (" . $usuarios_reputacao_[$i] . ", $id_sms)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_usuarios_reputacao .= "u.id_reputacao = " . $usuarios_reputacao_[$i];
                    } else {
                        $sql_usuarios_reputacao .= " || u.id_reputacao = " . $usuarios_reputacao_[$i];
                    }
                }
            }

            if ($participantes == 1) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                           SELECT '$data_registro', $id_sms, id, telefone_celular FROM bejobs_usuarios WHERE status = 1"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 2) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                            SELECT '$data_registro', $id_sms, u.id, u.telefone_celular 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_lojas AS l
                            WHERE u.lojas_cnpj = l.cnpj 
                            AND ($sql_grupos_economicos)"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 3) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                            SELECT '$data_registro', $id_sms, u.id, u.telefone_celular 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_lojas AS l
                            WHERE u.lojas_cnpj = l.cnpj 
                            AND ($sql_lojas)"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 4) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                            SELECT '$data_registro', $id_sms, u.id, u.telefone_celular 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_grupos_usuarios AS gu
                            WHERE u.id = gu.id_usuarios 
                            AND u.status = 1
                            AND ($sql_grupos_usuarios)
                            GROUP BY gu.id_usuarios";
                // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 5) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                            SELECT '$data_registro', $id_sms, u.id, u.telefone_celular 
                            FROM bejobs_usuarios AS u
                            WHERE u.status = 1 AND $sql_usuarios"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 6) {
                $sql2 = "INSERT INTO bejobs_sms_usuarios (data_registro, id_sms, id_usuarios, telefone) 
                            SELECT '$data_registro', $id_sms, u.id, u.telefone_celular
                            FROM bejobs_usuarios AS u
                            WHERE $sql_usuarios_reputacao"; // print_r($sql2);
                $dba->query($sql2);
            }

            $sql9   = "SELECT id, telefone FROM bejobs_sms_usuarios WHERE id_sms=$id_sms";
            $query9 = $dba->query($sql9);
            $qntd9  = $dba->rows($query9);
            if ($qntd9 > 0) {
                for ($i = 0; $i < $qntd9; $i++) {
                    $vet9 = $dba->fetch($query9);
                    $id_sms_usuarios  = $vet9[0];
                    $telefone_celular = $vet9[1];


                    $url_api  = "https://www.iagentesms.com.br/webservices/http.php?metodo=envio&usuario=anderson@dedstudio.com.br&senha=991128320&celular=$telefone_celular&mensagem=$mensagem&codigosms=$id_sms_usuarios";
                    $api_http = file_get_contents($url_api);

                    $sql11 = "UPDATE bejobs_sms_usuarios SET status_requisicao='$api_http' WHERE id=$id_sms_usuarios";
                    $dba->query($sql11);
                }
            }

            echo "success";
            break;

            // Categorias
        case 'produtos_categorias_cadastrar':
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "INSERT INTO bejobs_produtos_categorias (titulo) VALUES ('$titulo')";
            $dba->query($sql);

            echo "success";
            break;

        case 'produtos_categorias_editar':
            $id = $_POST['id'];
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "UPDATE bejobs_produtos_categorias SET titulo='$titulo' WHERE id = $id";
            $dba->query($sql);

            echo "success";
            break;

        case 'produtos_categorias_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_produtos_categorias WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./produtos-categorias?msg=ec03');
            break;

            // Marcas
        case 'produtos_marcas_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if (!empty($_FILES['img'])) {
                // if ($_FILES['img']['size'] > 2097152) {echo "invalidimgsize";exit;} 
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);

            $sql = "INSERT INTO bejobs_produtos_marcas (titulo) VALUES ('$titulo')";
            $dba->query($sql);

            if (!empty($_FILES['img'])) {
                $ide = $dba->lastid();
                $img = $_FILES['img'];

                $destino = "../images/marcas/" . $ide . ".jpg";
                move_uploaded_file($_FILES['img']['tmp_name'], $destino);

                $img = "../images/marcas/" . $ide . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(500, 500, "crop");
                    $thumb->grava('../images/marcas/' . $ide . '_500x500.jpg', 100);
                } else {
                    die($verifica);
                }
            }

            echo "success";
            break;

        case 'produtos_marcas_editar':
            $ide = $_POST['id'];

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            if (!file_exists('../images/marcas/' . $ide . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                if (!empty($_FILES['img'])) {
                    // if ($_FILES['img']['size'] > 2097152) {echo "invalidimgsize";exit;} 
                    //array de extensões permitidas 
                    $allowedExts = array(".jpg", ".JPG");
                    //Atribui uma array com os nomes dos arquivos à variável
                    $name = $_FILES['img']['name'];
                    $ext = strtolower(substr($name, -4));
                    //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                    if (!in_array($ext, $allowedExts)) {
                        echo "invalidimg";
                        exit;
                    }
                }
            }

            $titulo = addslashes($_POST['titulo']);

            $sql = "UPDATE bejobs_produtos_marcas SET titulo='$titulo' WHERE id = $ide";
            $dba->query($sql);

            if (!empty($_FILES['img'])) {
                $img = $_FILES['img'];

                $destino = "../images/marcas/" . $ide . ".jpg";
                move_uploaded_file($_FILES['img']['tmp_name'], $destino);

                $img = "../images/marcas/" . $ide . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(500, 500, "crop");
                    $thumb->grava('../images/marcas/' . $ide . '_500x500.jpg', 100);
                } else {
                    die($verifica);
                }
            }

            echo "success_edit";
            break;

        case 'produtos_marcas_delete_img':
            $id = $_REQUEST['id'];
            $imagem = '../images/marcas/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/marcas/' . $id . '_500x500.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("location: produtos-marcas-editar?id=$id");
            break;

        case 'produtos_marcas_delete':
            $idn = $_GET['id'];

            $imagem = '../images/marcas/' . $idn . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/marcas/' . $idn . '_500x500.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $sql = "DELETE FROM bejobs_produtos_marcas WHERE id = '$idn'";
            $dba->query($sql);

            header('location: produtos-marcas?msg=pm003');
            break;

        case 'desativar_produtos_marcas':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos_marcas SET status = 0 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./produtos-marcas?msg=pm005');
            break;

        case 'ativar_produtos_marcas':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos_marcas SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./produtos-marcas?msg=pm004');
            break;

            // Produtos
        case 'produtos_cadastrar':
            if (empty($_POST['codigo'])) {
                echo "codigo";
                exit;
            }

            $codigo = addslashes($_POST['codigo']);
            $sql1   = "SELECT * FROM bejobs_produtos WHERE codigo = $codigo"; // print_r($sql);
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "codigo_exists";
                exit;
            }

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['preco'])) {
                echo "preco";
                exit;
            }
            if (empty($_POST['marca'])) {
                echo "marca";
                exit;
            }
            if (empty($_POST['categ'])) {
                echo "categ";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }
            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");
            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            if (!empty($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img2']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $codigo = addslashes($_POST['codigo']);
            $titulo = addslashes($_POST['titulo']);
            $texto  = addslashes($_POST['texto']);
            // $preco       = numero($_POST['preco']);
            $preco  = $_POST['preco'];
            $preco  = str_replace('.', '', $preco);
            $preco  = str_replace(',', '.', $preco);

            $preco_promo = 0;
            if (!empty($_POST['preco_promo'])) {
                // $preco_promo = numero($_POST['preco_promo']);
                $preco_promo  = $_POST['preco_promo'];
                $preco_promo  = str_replace('.', '', $preco_promo);
                $preco_promo  = str_replace(',', '.', $preco_promo);
            }

            $tags        = addslashes($_POST['tags']);
            $marca       = addslashes($_POST['marca']);
            $categ       = addslashes($_POST['categ']);

            $codigo_ncm                   = addslashes($_POST['codigo_ncm']);
            $cfop                         = addslashes($_POST['cfop']);
            $icms_origem                  = addslashes($_POST['icms_origem']);
            $icms_situacao_tributaria     = addslashes($_POST['icms_situacao_tributaria']);
            $icms_aliquota                = addslashes($_POST['icms_aliquota']);
            // $icms_base_calculo            = addslashes($_POST['icms_base_calculo']);
            $icms_modalidade_base_calculo = addslashes($_POST['icms_modalidade_base_calculo']);

            $sql = "INSERT INTO bejobs_produtos (codigo, titulo, texto, preco, preco_promo, id_categoria, id_marca, tags, codigo_ncm, cfop, icms_origem, icms_situacao_tributaria, icms_aliquota, icms_modalidade_base_calculo) VALUES ('$codigo', '$titulo', '$texto', '$preco', '$preco_promo', '$categ', '$marca', '$tags', '$codigo_ncm', '$cfop', '$icms_origem', '$icms_situacao_tributaria', '$icms_aliquota', '$icms_modalidade_base_calculo')";  // print_r($sql);     
            $dba->query($sql);

            $ide = $dba->lastid(); //Busca último produto registrado no BD

            // Imagem Principal
            $img      = $_FILES['img'];
            $destino  = "../images/produtos/" . $ide . ".jpg";
            $ok       = upload($img, $destino, 1920, 1280);

            $img      = "../images/produtos/" . $ide . ".jpg";
            $thumb    = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->rgb(255, 255, 255); // background branco
                $thumb->redimensiona(800, 600, "fill");
                $thumb->grava('../images/produtos/' . $ide . '_800x600.jpg', 70);
            } else {
                die($verifica);
            }

            // Imagem 2
            if (!empty($_FILES['img2'])) {
                $img2    = $_FILES['img2'];
                $destino = "../images/produtos/" . $ide . "_2.jpg";
                $ok      = upload($img2, $destino, 1920, 1280);

                $img2     = "../images/produtos/" . $ide . "_2.jpg";
                $thumb    = new m2brimagem("$img2");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->rgb(255, 255, 255); // background branco
                    $thumb->redimensiona(800, 600, "fill");
                    $thumb->grava('../images/produtos/' . $ide . '_2_800x600.jpg', 70);
                } else {
                    die($verifica);
                }
            }

            echo "success";
            break;

        case 'produtos_editar':
            $ide = $_POST['id'];
            if (empty($_POST['codigo'])) {
                echo "codigo";
                exit;
            }

            $codigo = addslashes($_POST['codigo']);
            $sql1   = "SELECT * FROM bejobs_produtos WHERE codigo = $codigo AND id != $ide"; // print_r($sql);
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "codigo_exists";
                exit;
            }

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['preco'])) {
                echo "preco";
                exit;
            }
            if (empty($_POST['marca'])) {
                echo "marca";
                exit;
            }
            if (empty($_POST['categ'])) {
                echo "categ";
                exit;
            }

            if (!file_exists('../images/produtos/' . $ide . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            if (!empty($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img2']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            // if (!empty($_FILES['img3'])) {
            //     if($_FILES['img3']['size'] > 2097152) {echo "invalidimgsize";exit;} 
            //     //array de extensões permitidas 
            //     $allowedExts = array(".jpg", ".JPG");
            //     //Atribui uma array com os nomes dos arquivos à variável
            //     $name = $_FILES['img3']['name'];
            //     $ext = strtolower(substr($name,-4));
            //     //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            //     if(!in_array($ext, $allowedExts)){echo "invalidimg";exit;}
            // }

            // if (!empty($_FILES['img4'])) {
            //     if($_FILES['img4']['size'] > 2097152) {echo "invalidimgsize";exit;} 
            //     //array de extensões permitidas 
            //     $allowedExts = array(".jpg", ".JPG");
            //     //Atribui uma array com os nomes dos arquivos à variável
            //     $name = $_FILES['img4']['name'];
            //     $ext = strtolower(substr($name,-4));
            //     //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            //     if(!in_array($ext, $allowedExts)){echo "invalidimg";exit;}
            // }

            $codigo      = addslashes($_POST['codigo']);
            $titulo      = addslashes($_POST['titulo']);
            $texto       = addslashes($_POST['texto']);
            // $preco       = numero($_POST['preco']);
            $preco  = $_POST['preco'];
            $preco  = str_replace('.', '', $preco);
            $preco  = str_replace(',', '.', $preco);

            $preco_promo = 0;
            if (!empty($_POST['preco_promo'])) {
                // $preco_promo = numero($_POST['preco_promo']);
                $preco_promo  = $_POST['preco_promo'];
                $preco_promo  = str_replace('.', '', $preco_promo);
                $preco_promo  = str_replace(',', '.', $preco_promo);
            }

            $tags        = addslashes($_POST['tags']);
            $marca       = addslashes($_POST['marca']);
            $categ       = addslashes($_POST['categ']);

            $codigo_ncm                   = addslashes($_POST['codigo_ncm']);
            $cfop                         = addslashes($_POST['cfop']);
            $icms_origem                  = addslashes($_POST['icms_origem']);
            $icms_situacao_tributaria     = addslashes($_POST['icms_situacao_tributaria']);
            $icms_aliquota                = addslashes($_POST['icms_aliquota']);
            // $icms_base_calculo            = addslashes($_POST['icms_base_calculo']);
            $icms_modalidade_base_calculo = addslashes($_POST['icms_modalidade_base_calculo']);

            $sql = "UPDATE bejobs_produtos SET codigo='$codigo', titulo='$titulo', texto='$texto', preco='$preco', preco_promo='$preco_promo', id_categoria='$categ', id_marca='$marca', tags='$tags', codigo_ncm='$codigo_ncm', cfop='$cfop', icms_origem='$icms_origem', icms_situacao_tributaria='$icms_situacao_tributaria', icms_aliquota='$icms_aliquota', icms_modalidade_base_calculo='$icms_modalidade_base_calculo' WHERE id=$ide"; // print_r($sql);          
            $dba->query($sql);

            if (!file_exists('../images/produtos/' . $ide . '.jpg')) {
                $img     = $_FILES['img'];
                $destino = "../images/produtos/" . $ide . ".jpg";
                $ok      = upload($img, $destino, 1920, 1280);

                $img      = "../images/produtos/" . $ide . ".jpg";
                $thumb    = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->rgb(255, 255, 255); // background branco
                    $thumb->redimensiona(800, 600, "fill");
                    $thumb->grava('../images/produtos/' . $ide . '_800x600.jpg', 70);
                } else {
                    die($verifica);
                }
            }

            // Imagem 2
            if (!empty($_FILES['img2'])) {
                $img2    = $_FILES['img2'];
                $destino = "../images/produtos/" . $ide . "_2.jpg";
                $ok      = upload($img2, $destino, 1920, 1280);

                $img2     = "../images/produtos/" . $ide . "_2.jpg";
                $thumb    = new m2brimagem("$img2");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->rgb(255, 255, 255); // background branco
                    $thumb->redimensiona(800, 600, "fill");
                    $thumb->grava('../images/produtos/' . $ide . '_2_800x600.jpg', 70);
                } else {
                    die($verifica);
                }
            }

            // // Imagem 3
            // if (!empty($_FILES['img3'])) {
            //     $img3    = $_FILES['img3'];
            //     $destino = "../images/produtos/".$ide."_3.jpg";    
            //     $ok      = upload($img3, $destino, 1920, 1280);

            //     $img3     = "../images/produtos/".$ide."_3.jpg";                      
            //     $thumb    = new m2brimagem("$img3");
            //     $verifica = $thumb->valida();

            //     if ($verifica == "OK") {
            //         $thumb->rgb( 255, 255, 255 ); // background branco
            //         $thumb->redimensiona(800, 600, "fill");
            //         $thumb->grava('../images/produtos/'.$ide.'_3_800x600.jpg', 70);
            //     } else {  die($verifica); }
            // }

            // // Imagem 4
            // if (!empty($_FILES['img4'])) {
            //     $img4    = $_FILES['img4'];
            //     $destino = "../images/produtos/".$ide."_4.jpg";    
            //     $ok      = upload($img4, $destino, 1920, 1280);

            //     $img4     = "../images/produtos/".$ide."_4.jpg";                      
            //     $thumb    = new m2brimagem("$img4");
            //     $verifica = $thumb->valida();

            //     if ($verifica == "OK") {
            //         $thumb->rgb( 255, 255, 255 ); // background branco
            //         $thumb->redimensiona(800, 600, "fill");
            //         $thumb->grava('../images/produtos/'.$ide.'_4_800x600.jpg', 70);
            //     } else {  die($verifica); }
            // }

            echo "success_edit";
            break;

        case 'produtos_delete':
            $id = $_GET['id'];

            $sql = "DELETE FROM bejobs_produtos WHERE id = $id";
            $dba->query($sql);

            $imagem = '../images/produtos/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $imagem = '../images/produtos/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_2_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('Location: ./produtos?msg=pd03');
            break;

        case 'produtos_delete_img':
            $id = $_REQUEST['id'];

            $imagem = '../images/produtos/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("Location: ./produtos-editar?id=$id");

            break;

        case 'produtos_delete_img_2':
            $id = $_REQUEST['id'];

            $imagem = '../images/produtos/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_2_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("Location: ./produtos-editar?id=$id");
            break;

        case 'produtos_delete_img_3':
            $id = $_REQUEST['id'];

            $imagem = '../images/produtos/' . $id . '_3.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_3_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("Location: ./produtos-editar?id=$id");
            break;

        case 'produtos_delete_img_4':
            $id = $_REQUEST['id'];

            $imagem = '../images/produtos/' . $id . '_4.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../images/produtos/' . $id . '_4_800x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("Location: ./produtos-editar?id=$id");
            break;

        case 'desativar_produto':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos SET status = 0 where id = $idn";
            $dba->query($sql);

            header('Location: ./produtos?msg=p005');
            break;

        case 'ativar_produto':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos SET status = 1 where id = $idn";
            $dba->query($sql);

            header('Location: produtos?msg=p004');
            break;

        case 'produtos_destaque_sim':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos SET destaque = 1 WHERE id = $idn";
            $dba->query($sql);

            header('Location: produtos?msg=p006');

            break;

        case 'produtos_destaque_nao':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_produtos SET destaque = 0 WHERE id = $idn";
            $dba->query($sql);

            header('Location: ./produtos?msg=p007');
            break;

            // Categorias
        case 'bairros_cadastrar':
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "INSERT INTO bejobs_bairros (titulo, status) VALUES ('$titulo', 1)";
            $dba->query($sql);

            echo "success";
            break;

        case 'bairros_editar':
            $id     = $_POST['id'];
            $titulo = addslashes($_POST['titulo']);
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            $sql = "UPDATE bejobs_bairros SET titulo='$titulo' WHERE id = $id";
            $dba->query($sql);

            echo "success";
            break;

        case 'bairros_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_bairros WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./bairros?msg=b003');
            break;

        case 'desativar_bairro':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_bairros SET status = 0 where id = $idn";
            $dba->query($sql);

            header('Location: ./bairros?msg=b005');
            break;

        case 'ativar_bairro':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_bairros SET status = 1 where id = $idn";
            $dba->query($sql);

            header('Location: bairros?msg=b004');
            break;

            // Horários Funcionamento
        case 'horarios_funcionamento_cadastrar':
            if (trim(empty($_POST['data_inicio'])) || !validaData($_POST['data_inicio'])) {
                echo "data_inicio";
                exit;
            }
            if (trim(empty($_POST['hora_inicio'])) || !validaHora($_POST['hora_inicio'])) {
                echo "hora_inicio";
                exit;
            }
            if (trim(empty($_POST['data_fim'])) || !validaData($_POST['data_fim'])) {
                echo "data_fim";
                exit;
            }
            if (trim(empty($_POST['hora_fim'])) || !validaHora($_POST['hora_fim'])) {
                echo "hora_fim";
                exit;
            }
            if (strtotime(dataMY($_POST['data_inicio'])) > strtotime(dataMY($_POST['data_fim']))) {
                echo "datas";
                exit;
            }
            // if (($_POST['hora_inicio'] > $_POST['hora_fim'])) { echo "horas_invalidas"; exit; }

            $data_inicio = $_POST['data_inicio'];
            $hora_inicio = $_POST['hora_inicio'];
            $data_hora_inicio = datetime_mysql($data_inicio, $hora_inicio . ':00');

            $data_fim = $_POST['data_fim'];
            $hora_fim = $_POST['hora_fim'];
            $data_hora_fim = datetime_mysql($data_fim, $hora_fim . ':59');

            $sql = "INSERT INTO bejobs_horarios_funcionamento (data_hora_ini, data_hora_fim, status) VALUES ('$data_hora_inicio', '$data_hora_fim', 1)";
            $dba->query($sql);

            echo "success";
            break;

        case 'horarios_funcionamento_editar':
            $id     = $_POST['id'];
            if (trim(empty($_POST['data_inicio'])) || !validaData($_POST['data_inicio'])) {
                echo "data_inicio";
                exit;
            }
            if (trim(empty($_POST['hora_inicio'])) || !validaHora($_POST['hora_inicio'])) {
                echo "hora_inicio";
                exit;
            }
            if (trim(empty($_POST['data_fim'])) || !validaData($_POST['data_fim'])) {
                echo "data_fim";
                exit;
            }
            if (trim(empty($_POST['hora_fim'])) || !validaHora($_POST['hora_fim'])) {
                echo "hora_fim";
                exit;
            }
            if (strtotime(dataMY($_POST['data_inicio'])) > strtotime(dataMY($_POST['data_fim']))) {
                echo "datas";
                exit;
            }
            // if (($_POST['hora_inicio'] > $_POST['hora_fim'])) { echo "horas_invalidas"; exit; }

            $data_inicio = $_POST['data_inicio'];
            $hora_inicio = $_POST['hora_inicio'];
            $data_hora_inicio = datetime_mysql($data_inicio, $hora_inicio . ':00');

            $data_fim = $_POST['data_fim'];
            $hora_fim = $_POST['hora_fim'];
            $data_hora_fim = datetime_mysql($data_fim, $hora_fim . ':59');

            $sql = "UPDATE bejobs_horarios_funcionamento SET data_hora_ini='$data_hora_inicio', data_hora_fim='$data_hora_fim' WHERE id = $id";
            $dba->query($sql);

            echo "success";
            break;

        case 'horarios_funcionamento_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_horarios_funcionamento WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./horarios-funcionamento?msg=hf003');
            break;

        case 'desativar_horario_funcionamento':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_horarios_funcionamento SET status = 0 where id = $idn";
            $dba->query($sql);

            header('Location: ./horarios-funcionamento?msg=hf005');
            break;

        case 'ativar_horario_funcionamento':
            $idn = $_GET['id'];

            $sql = "UPDATE bejobs_horarios_funcionamento SET status = 1 where id = $idn";
            $dba->query($sql);

            header('Location: ./horarios-funcionamento?msg=hf004');
            break;

        case 'compras_cadastrar':
            // if (empty($_POST['entregador'])) {echo "entregador"; exit;}    
            if (!isset($_POST['chave_acesso']) || empty($_POST['chave_acesso'])) {
                echo "chave_acesso";
                exit;
            }
            if (!isset($_POST['numero']) || empty($_POST['numero'])) {
                echo "numero";
                exit;
            }
            if (!isset($_POST['data_emissao']) || empty($_POST['data_emissao'])) {
                echo "data_emissao";
                exit;
            }
            if (!isset($_POST['hora_emissao']) || empty($_POST['hora_emissao'])) {
                echo "hora_emissao";
                exit;
            }
            // if (!isset($_FILES['xml']) && empty($_FILES['xml']['tmp_name'])) { echo "xml"; exit; }

            // $entregador = $_POST['entregador'];

            $chave_acesso  = $_POST['chave_acesso'];
            $serie         = $_POST['serie'];
            $nNF           = $_POST['numero'];

            $dhEmi = "";
            if (isset($_POST['data_emissao']) && isset($_POST['hora_emissao']) && !empty($_POST['data_emissao']) && !empty($_POST['hora_emissao'])) {
                $dhEmi = dataMY($_POST['data_emissao']) . ' ' . $_POST['hora_emissao'];
            }

            // <emit></emit> Dados Emitente = Posto
            $emit_CNPJ     = $_POST['emit_cnpj']; // CNPJ do posto        
            $emit_xNome    = addslashes($_POST['emit_nome']); // NOME do posto
            $emit_IE       = $_POST['emit_ie']; //<IE>9014134104</IE> Emiten

            // <dest></dest> Destinatário Usuário comprador
            $dest_cnpj     = $_POST['dest_cnpj_cpf']; // <CNPJ></CNPJ>
            // $dest_cpf      = $xml->NFe->infNFe->dest->CPF; // <CPF></CPF>
            $dest_xNome    = addslashes($_POST['dest_nome']); // <xNome></xNome>

            $protocolo_aut = $_POST['protocolo']; // Protoco de Autorização
            $inf_cpl       = $_POST['inf_cpl']; // Informações Complementares

            $total_nfce    = ""; // Total NFC-e
            if (isset($_POST['total_nfce']) && !empty($_POST['total_nfce'])) {
                $total_nfce  = $_POST['total_nfce'];
                $total_nfce  = str_replace('.', '', $total_nfce);
                $total_nfce  = str_replace(',', '.', $total_nfce);
            }

            // Verifica se XML já foi enviado
            $sql1   = "SELECT id FROM bejobs_compras WHERE chave_acesso='$chave_acesso'";
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "registro_compra";
                exit;
            }

            if (isset($_POST['produtos'])) {

                $produtos = $_POST['produtos'];
                $qntd     = $_POST['qntd'];
                $preco    = $_POST['preco'];

                $max = sizeof($produtos);
                for ($i = 0; $i < $max; $i++) {

                    $codigo = $produtos[$i]; // Id do produtos

                    $sql1   = "SELECT titulo FROM bejobs_produtos WHERE codigo='$codigo'";
                    $query1 = $dba->query($sql1);
                    $vet1   = $dba->fetch($query1);
                    $xProd  = $vet1[0]; // Nome do produtos

                    $qCom   = $qntd[$i]; // Qntd do produto consumido

                    $vUnCom = $preco[$i]; // Valor R$ unitários do produto
                    $vUnCom = str_replace('.', '', $vUnCom);
                    $vUnCom = str_replace(',', '.', $vUnCom);

                    $vProd  = $vUnCom * $qCom; // Valor R$ total do produto consumido
                    $vProd = str_replace('.', '', $vProd);
                    $vProd = str_replace(',', '.', $vProd);

                    $sql9 = "INSERT INTO bejobs_compras_produtos (nfce_chave_acesso, id_produto, titulo, qtde, vl_unit, vl_total) VALUES ('$chave_acesso', '$codigo', '$xProd', '$qCom', '$vUnCom', '$vProd')";
                    $dba->query($sql9);
                }
            }

            $sql = "INSERT INTO bejobs_compras (chave_acesso, protocolo_aut, n_nfce, serie, data_emissao, emit_nome, emit_cnpj, emit_ie, dest_cnpj_cpf, dest_nome, inf_cpl, total_nfce) VALUES ('$chave_acesso', '$protocolo_aut', '$nNF', '$serie', '$dhEmi', '$emit_xNome', '$emit_CNPJ', '$emit_IE', '$dest_cnpj', '$dest_xNome', '$inf_cpl', '$total_nfce')";
            $dba->query($sql);

            echo "success";
            exit;

        case 'compras_editar':
            $ide = $_POST['id'];
            // if (empty($_POST['entregador'])) {echo "entregador"; exit;}    
            if (!isset($_POST['chave_acesso']) || empty($_POST['chave_acesso'])) {
                echo "chave_acesso";
                exit;
            }
            if (!isset($_POST['numero']) || empty($_POST['numero'])) {
                echo "numero";
                exit;
            }
            if (!isset($_POST['data_emissao']) || empty($_POST['data_emissao'])) {
                echo "data_emissao";
                exit;
            }
            if (!isset($_POST['hora_emissao']) || empty($_POST['hora_emissao'])) {
                echo "hora_emissao";
                exit;
            }
            // if (!isset($_FILES['xml']) && empty($_FILES['xml']['tmp_name'])) { echo "xml"; exit; }

            // $entregador = $_POST['entregador'];

            $chave_acesso  = $_POST['chave_acesso'];
            $serie         = $_POST['serie'];
            $nNF           = $_POST['numero'];

            $dhEmi = "";
            if (isset($_POST['data_emissao']) && isset($_POST['hora_emissao']) && !empty($_POST['data_emissao']) && !empty($_POST['hora_emissao'])) {
                $dhEmi = dataMY($_POST['data_emissao']) . ' ' . $_POST['hora_emissao'];
            }

            // <emit></emit> Dados Emitente = Posto
            $emit_CNPJ     = $_POST['emit_cnpj']; // CNPJ do posto        
            $emit_xNome    = addslashes($_POST['emit_nome']); // NOME do posto
            $emit_IE       = $_POST['emit_ie']; //<IE>9014134104</IE> Emiten

            // <dest></dest> Destinatário Usuário comprador
            $dest_cnpj     = $_POST['dest_cnpj_cpf']; // <CNPJ></CNPJ>
            // $dest_cpf      = $xml->NFe->infNFe->dest->CPF; // <CPF></CPF>
            $dest_xNome    = addslashes($_POST['dest_nome']); // <xNome></xNome>

            $protocolo_aut = $_POST['protocolo']; // Protoco de Autorização
            $inf_cpl       = $_POST['inf_cpl']; // Informações Complementares

            $total_nfce    = ""; // Total NFC-e
            if (isset($_POST['total_nfce']) && !empty($_POST['total_nfce'])) {
                $total_nfce  = $_POST['total_nfce'];
                $total_nfce  = str_replace('.', '', $total_nfce);
                $total_nfce  = str_replace(',', '.', $total_nfce);
            }

            // Verifica se XML já foi enviado
            $sql1   = "SELECT id FROM bejobs_compras WHERE chave_acesso='$chave_acesso' AND id != $ide";
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "registro_compra";
                exit;
            }

            $sql3 = "DELETE FROM bejobs_compras_produtos WHERE nfce_chave_acesso='$chave_acesso'";
            $dba->query($sql3);

            if (isset($_POST['produtos'])) {

                $produtos = $_POST['produtos'];
                $qntd     = $_POST['qntd'];
                $preco    = $_POST['preco'];

                $max = sizeof($produtos);
                for ($i = 0; $i < $max; $i++) {

                    $codigo = $produtos[$i]; // Id do produtos

                    $sql1   = "SELECT titulo FROM bejobs_produtos WHERE codigo='$codigo'";
                    $query1 = $dba->query($sql1);
                    $vet1   = $dba->fetch($query1);
                    $xProd  = $vet1[0]; // Nome do produtos

                    $qCom   = $qntd[$i]; // Qntd do produto consumido

                    $vUnCom = $preco[$i]; // Valor R$ unitários do produto
                    $vUnCom = str_replace('.', '', $vUnCom);
                    $vUnCom = str_replace(',', '.', $vUnCom);

                    $vProd  = $vUnCom * $qCom; // Valor R$ total do produto consumido
                    $vProd = str_replace('.', '', $vProd);
                    $vProd = str_replace(',', '.', $vProd);

                    $sql9 = "INSERT INTO bejobs_compras_produtos (nfce_chave_acesso, id_produto, titulo, qtde, vl_unit, vl_total) VALUES ('$chave_acesso', '$codigo', '$xProd', '$qCom', '$vUnCom', '$vProd')";
                    $dba->query($sql9);
                }
            }

            // $sql = "INSERT INTO bejobs_compras (chave_acesso, protocolo_aut, n_nfce, serie, data_emissao, emit_nome, emit_cnpj, emit_ie, dest_cnpj_cpf, dest_nome, inf_cpl, total_nfce) VALUES ('$chave_acesso', '$protocolo_aut', '$nNF', '$serie', '$dhEmi', '$emit_xNome', '$emit_CNPJ', '$emit_IE', '$dest_cnpj', '$dest_xNome', '$inf_cpl', '$total_nfce')";
            $sql = "UPDATE bejobs_compras SET chave_acesso='$chave_acesso', protocolo_aut='$protocolo_aut', n_nfce='$nNF', serie='$serie', data_emissao='$dhEmi', emit_nome='$emit_xNome', emit_cnpj='$emit_CNPJ', emit_ie='$emit_IE', dest_cnpj_cpf='$dest_cnpj', dest_nome='$dest_xNome', inf_cpl='$inf_cpl', total_nfce='$total_nfce' WHERE id = $ide";
            $dba->query($sql);

            echo "success";
            exit;

        case 'compras_cadastrar_xml':
            // if (empty($_POST['entregador'])) {echo "entregador"; exit;}    
            if (!isset($_FILES['xml']) && empty($_FILES['xml']['tmp_name'])) {
                echo "xml";
                exit;
            }

            // $entregador = $_POST['entregador'];

            $xml = $_FILES['xml']['tmp_name']; // Arquivo XML
            $xml = simplexml_load_file($xml); // Interpreta arquivo XML e transforma em objeto

            if (!isset($xml->NFe->infNFe->attributes()->Id)) {
                echo "xml_invalid";
                exit;
            }

            $xml_full      = $xml;
            $chave_acesso  = $xml->NFe->infNFe->attributes()->Id;
            $chave_acesso  = strtr(strtoupper($chave_acesso), array("NFE" => NULL)); // Chave de Acesso NFC-e
            $serie         = $xml->NFe->infNFe->ide->serie; // <serie>2</serie> 
            $nNF           = $xml->NFe->infNFe->ide->nNF; // <nNF>183516</nNF> Número da Nota Fiscal

            $dhEmi         = $xml->NFe->infNFe->ide->dhEmi; // <dhEmi>2017-03-31T00:16:32-03:00<dhEmi> Data de emissão da Nota Fiscal
            $dhEmi         = date_create($dhEmi);
            $dhEmi         = date_format($dhEmi, 'Y-m-d H:i:s'); // Formata data NFC-e MySQL

            // <emit></emit> Dados Emitente = Posto
            $emit_CNPJ     = $xml->NFe->infNFe->emit->CNPJ; // CNPJ do posto        
            $emit_xNome    = addslashes($xml->NFe->infNFe->emit->xNome); // NOME do posto
            $emit_IE       = $xml->NFe->infNFe->emit->IE; //<IE>9014134104</IE> Emiten

            // <dest></dest> Destinatário Usuário comprador
            $dest_cnpj     = $xml->NFe->infNFe->dest->CNPJ; // <CNPJ></CNPJ>
            $dest_cpf      = $xml->NFe->infNFe->dest->CPF; // <CPF></CPF>
            $dest_xNome    = addslashes($xml->NFe->infNFe->dest->xNome); // <xNome></xNome>

            $protocolo_aut = $xml->protNFe->infProt->nProt; // Protoco de Autorização

            if (!empty($xml->NFe->infNFe->infAdic->infCpl)) {
                $inf_cpl = addslashes($xml->NFe->infNFe->infAdic->infCpl); // Informações Complementares

            } else {
                $inf_cpl = $xml->NFe->infNFe->infAdic->infCpl; // Informações Complementares
            }

            $total_nfce = $xml->NFe->infNFe->total->ICMSTot->vNF; // Total NFC-e

            // Verifica se XML já foi enviado
            $sql1   = "SELECT id FROM bejobs_compras WHERE chave_acesso='$chave_acesso'";
            $query1 = $dba->query($sql1);
            $qntd1  = $dba->rows($query1);
            if ($qntd1 > 0) {
                echo "xml_exists";
                exit;
            }

            $k = 0;
            foreach ($xml->NFe->infNFe->det as $item) { // Percorre todos produtos da NFC-e
                $k++;
                // Infos do Produto
                $codigo = $item->prod->cProd; // Id do produtos
                $xProd = addslashes($item->prod->xProd); // Nome do produtos
                $qCom = $item->prod->qCom; // Qntd do produto consumido
                $vUnCom = $item->prod->vUnCom; // Valor R$ unitários do produto
                $vProd = $item->prod->vProd; // Valor R$ total do produto consumido

                $sql9 = "INSERT INTO bejobs_compras_produtos (nfce_chave_acesso, id_produto, titulo, qtde, vl_unit, vl_total) VALUES ('$chave_acesso', '$codigo', '$xProd', '$qCom', '$vUnCom', '$vProd')";
                $dba->query($sql9);
            }

            $sql = "INSERT INTO bejobs_compras (chave_acesso, protocolo_aut, n_nfce, serie, data_emissao, emit_nome, emit_cnpj, emit_ie, dest_cnpj_cpf, dest_nome, inf_cpl, total_nfce, xml_full) VALUES ('$chave_acesso','$protocolo_aut','$nNF','$serie','$dhEmi','$emit_xNome','$emit_CNPJ','$emit_IE','$dest_cnpj$dest_cpf','$dest_xNome','$inf_cpl','$total_nfce','$xml_full')";
            $dba->query($sql);

            echo "success";
            break;

        case 'compras_delete':
            $idn = $_GET['id'];

            $sql1   = "SELECT chave_acesso FROM bejobs_compras WHERE id='$idn'";
            $query1 = $dba->query($sql1);
            $vet1   = $dba->fetch($query1);
            $chave_acesso = $vet1[0];

            $sql = "DELETE FROM bejobs_compras WHERE chave_acesso = '$chave_acesso'";
            $dba->query($sql);

            $sql = "DELETE FROM bejobs_compras_produtos WHERE nfce_chave_acesso = '$chave_acesso'";
            $dba->query($sql);

            header('Location: ./compras?msg=c003');
            break;

        case 'entregadores_estoque_cadastrar':
            if (trim(empty($_POST['data'])) || !validaData($_POST['data'])) {
                echo "data";
                exit;
            }
            if (empty($_POST['entregador'])) {
                echo "entregador";
                exit;
            }
            if (!isset($_POST['produtos']) && !isset($_POST['qntd'])) {
                echo "produto_qntd";
                exit;
            }

            $data       = dataMY($_POST['data']);
            $entregador = $_POST['entregador'];
            $produtos   = $_POST['produtos'];
            $qntd       = $_POST['qntd'];

            $max = sizeof($produtos);
            for ($i = 0; $i < $max; $i++) {

                $id_produto = $produtos[$i]; // Id do produtos
                $qtde       = $qntd[$i]; // Qntd produtos

                $sql9 = "INSERT INTO bejobs_entregadores_estoque (id_produto, qtde, id_entregadores, data_registro) VALUES ('$id_produto', '$qtde', '$entregador', '$data')";
                $dba->query($sql9);
            }

            echo "success";
            exit;

        case 'entregadores_estoque_transferencia_cadastrar':
            if (trim(empty($_POST['data'])) || !validaData($_POST['data'])) {
                echo "data";
                exit;
            }
            if (empty($_POST['entregador_saida'])) {
                echo "entregador_saida";
                exit;
            }
            if (empty($_POST['entregador_entrada'])) {
                echo "entregador_entrada";
                exit;
            }
            if (!isset($_POST['produtos']) && !isset($_POST['qntd'])) {
                echo "produto_qntd";
                exit;
            }

            $data       = dataMY($_POST['data']);
            $entregador_saida   = $_POST['entregador_saida'];
            $entregador_entrada = $_POST['entregador_entrada'];
            $produtos   = $_POST['produtos'];
            $qntd       = $_POST['qntd'];

            $max = sizeof($produtos);
            for ($i = 0; $i < $max; $i++) {

                $id_produto = $produtos[$i]; // Id do produtos
                $qtde       = $qntd[$i]; // Qntd produtos

                $sql9 = "INSERT INTO bejobs_entregadores_estoque (id_produto, qtde, id_entregadores, data_registro) VALUES ('$id_produto', '-$qtde', '$entregador_saida', '$data')";
                $dba->query($sql9);

                $sql9 = "INSERT INTO bejobs_entregadores_estoque (id_produto, qtde, id_entregadores, data_registro) VALUES ('$id_produto', '$qtde', '$entregador_entrada', '$data')";
                $dba->query($sql9);
            }

            echo "success";
            exit;

        case 'entregadores_estoque_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_entregadores_estoque WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./entregadores-estoque?msg=ee003');
            break;

        case 'baixa_de_produtos_cadastrar':
            if (trim(empty($_POST['data'])) || !validaData($_POST['data'])) {
                echo "data";
                exit;
            }
            // if (empty($_POST['observacoes'])) {echo "observacoes"; exit;}  
            if (!isset($_POST['produtos']) && !isset($_POST['qntd'])) {
                echo "produto_qntd";
                exit;
            }

            $data        = dataMY($_POST['data']);
            $observacoes = addslashes($_POST['observacoes']);
            $produtos    = $_POST['produtos'];
            $entregador  = $_POST['entregador'];
            $qntd        = $_POST['qntd'];

            $max = sizeof($produtos);
            for ($i = 0; $i < $max; $i++) {

                $id_produto = $produtos[$i]; // Id do produtos
                $qtde       = $qntd[$i]; // Qntd produtos

                $sql9 = "INSERT INTO bejobs_estoque_baixas (id_produto, qtde, observacoes, data_registro, id_entregadores) VALUES ('$id_produto', '$qtde', '$observacoes', '$data', '$entregador')";
                $dba->query($sql9);
            }

            echo "success";
            exit;

        case 'baixa_de_produtos_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_estoque_baixas WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./baixa-de-produtos?msg=bp003');
            break;

        case 'push_notification_cadastrar':
            if (trim(empty($_POST['titulo']))) {
                echo "titulo";
                exit;
            }
            if (trim(empty($_POST['texto']))) {
                echo "texto";
                exit;
            }
            if (trim(empty($_POST['status_notificacao_interna'])) || !is_numeric($_POST['status_notificacao_interna'])) {
                echo "status_notificacao_interna";
                exit;
            }

            if (!isset($_POST['participantes'])) {
                echo "participantes";
                exit;
            }

            $participantes = $_POST['participantes'];

            $usuarios_bejobsapp     = 0;
            $grupos_usuarios     = 0;
            $usuarios_individual = 0;

            if ($participantes == 1) { // Todos usuários bejobsAPP
                $usuarios_bejobsapp = 1;
            } elseif ($participantes == 2) { // Grupos de Usuários
                if (empty($_POST['id_grupos_usuarios'][0])) {
                    echo "grupo_usuarios";
                    exit;
                }

                $grupos_usuarios   = 1;
                $id_grupos_usuarios = $_POST['id_grupos_usuarios'];
            } elseif ($participantes == 3) { // Usuários Individual
                if (empty($_POST['id_usuario'][0])) {
                    echo "usuario_individual";
                    exit;
                }

                $usuarios_individual = 1;
                $id_usuario         = $_POST['id_usuario'];
            }

            $titulo           = addslashes($_POST['titulo']);
            $texto            = addslashes($_POST['texto']);
            $data_registro    = date('Y-m-d H:i:s');
            $ip_registro      = getIp();
            $usuario_registro = $_SESSION['app_user_id'];
            $status_notificacao_interna = addslashes($_POST['status_notificacao_interna']);


            $sql = "INSERT INTO bejobs_push_notification (titulo, texto, usuarios_bejobsapp, grupos_usuarios, usuarios_individual, data_registro, ip_registro, usuario_registro, status_notificacao_interna) VALUES ('$titulo', '$texto', $usuarios_bejobsapp, $grupos_usuarios, $usuarios_individual, '$data_registro', '$ip_registro', $usuario_registro, '$status_notificacao_interna')";
            $dba->query($sql);

            $id_push_notification = $dba->lastid();

            if ($participantes == 2) {
                $max = sizeof($id_grupos_usuarios);

                $sql_grupos_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_push_notification_grupos_usuarios (id_grupos_usuarios, id_push_notification) VALUES (" . $id_grupos_usuarios[$i] . ", $id_push_notification)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_grupos_usuarios .= "gu.id_grupos = " . $id_grupos_usuarios[$i];
                    } else {
                        $sql_grupos_usuarios .= " || gu.id_grupos = " . $id_grupos_usuarios[$i];
                    }
                }
            }

            if ($participantes == 3) {
                $max = sizeof($id_usuario);

                $sql_usuarios = "";
                for ($i = 0; $i < $max; $i++) {
                    $sql = "INSERT INTO bejobs_push_notification_usuarios_individual (id_usuarios, id_push_notification) VALUES (" . $id_usuario[$i] . ", $id_push_notification)";
                    $dba->query($sql);

                    if ($i === 0) {
                        $sql_usuarios .= "u.id = " . $id_usuario[$i];
                    } else {
                        $sql_usuarios .= " || u.id = " . $id_usuario[$i];
                    }
                }
            }

            if ($participantes == 1) {
                $sql2 = "INSERT INTO bejobs_push_notification_usuarios (data_registro, id_push_notification, id_usuarios, id_onesignal) 
                           SELECT '$data_registro', $id_push_notification, id, id_onesignal FROM bejobs_usuarios WHERE status = 1 AND status_onesignal = 1"; // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 2) {
                $sql2 = "INSERT INTO bejobs_push_notification_usuarios (data_registro, id_push_notification, id_usuarios, id_onesignal) 
                            SELECT '$data_registro', $id_push_notification, u.id, u.id_onesignal 
                            FROM bejobs_usuarios AS u
                            INNER JOIN bejobs_grupos_usuarios AS gu
                            WHERE u.id = gu.id_usuarios 
                            AND u.status = 1
                            AND u.status_onesignal = 1
                            AND ($sql_grupos_usuarios)
                            GROUP BY gu.id_usuarios";
                // print_r($sql2);
                $dba->query($sql2);
            }

            if ($participantes == 3) {
                $sql2 = "INSERT INTO bejobs_push_notification_usuarios (data_registro, id_push_notification, id_usuarios, id_onesignal) 
                            SELECT '$data_registro', $id_push_notification, u.id, u.id_onesignal 
                            FROM bejobs_usuarios AS u
                            WHERE u.status = 1 AND u.status_onesignal = 1 AND $sql_usuarios"; // print_r($sql2);
                $dba->query($sql2);
            }

            $registros = 2000;
            $pagina    = 1;
            $inicio    = ($registros * $pagina) - $registros;

            $sql3      = "SELECT id, id_onesignal FROM bejobs_push_notification_usuarios WHERE id_push_notification=$id_push_notification";
            $query3    = $dba->query($sql3);
            $total_reg = $dba->rows($query3);

            $total_pag = ceil($total_reg / $registros);

            while ($pagina <= $total_pag) {

                $sql9   = "SELECT id, id_onesignal, id_usuarios
                           FROM bejobs_push_notification_usuarios 
                           WHERE id_push_notification=$id_push_notification
                           LIMIT $inicio, $registros";
                $query9 = $dba->query($sql9);
                $qntd9  = $dba->rows($query9);
                if ($qntd9 > 0) {

                    $array_ids = array();

                    for ($i = 0; $i < $qntd9; $i++) {
                        $vet9 = $dba->fetch($query9);
                        $id_push_notification_usuarios = $vet9[0];
                        $id_onesignal                  = $vet9[1];

                        $array_ids[] = $id_onesignal;

                        if ($status_notificacao_interna == 1) {
                            $id_usuario   = $vet9[2];

                            $sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_usuario) VALUES (NOW(), '$titulo', '$texto', 1, '$id_usuario')";
                            $dba->query($sql2);
                        }
                    }

                    $content  = array("en" => stripslashes($texto));
                    $headings = array("en" => stripslashes($titulo));

                    //dados da dedstudio
                    $fields = array(
                        'app_id'         => "3b08a3d6-6ece-4c9f-9510-9c4c07551ab8",
                        'include_player_ids' => $array_ids,
                        'data'           => array("foo" => "bar"),
                        'contents'       => $content,
                        'headings'       => $headings,
                        'ios_badgeType'  => 'Increase',
                        'ios_badllevoount' => 1
                    );

                    $fields = json_encode($fields);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json; charset=utf-8',
                        'Authorization: Basic MWZmNGNjMzctMjk2ZC00NmY5LWJiOTEtN2U0ZWI0YzY3ODlk'
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $sql = "INSERT INTO bejobs_push_notification_envios (result_onesignal, id_push_notification) VALUES ('$response', $id_push_notification)";
                    $dba->query($sql);
                }

                $pagina = $pagina + 1;
                $inicio = ($registros * $pagina) - $registros;
            }

            echo "success";
            break;



        case 'preco_regiao_cadastrar':
            $estado = $_POST["estados"];
            $cidade = $_POST["cidades"];
            $preco = $_POST["value"];


            if (empty($estado)) {
                echo "estado";
                exit;
            }
            if (empty($cidade)) {
                echo "cidade";
                exit;
            }
            if (empty($preco)) {
                echo "preco";
                exit;
            }

            $sql = "INSERT INTO bejobs_regiao_preco(estado,cidade,preco) VALUES('$estado','$cidade','$preco')";
            $dba->query($sql);

            echo "success";
            exit;

        case 'preco_regiao_editar':
            $id = $_POST['id'];
            $estado = $_POST["estados"];
            $cidade = $_POST["cidades"];
            $preco = $_POST["preco"];

            if (empty($estado)) {
                echo "estado";
                exit;
            }
            if (empty($cidade)) {
                echo "cidade";
                exit;
            }
            if (empty($preco)) {
                echo "preco";
                exit;
            }

            $sql = "UPDATE bejobs_regiao_preco SET estado='$estado',cidade='$cidade',preco='$preco' WHERE id=$id";
            $dba->query($sql);

            echo "success_edit";
            exit;



        case 'preco_regiao_deletar':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_regiao_preco WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./registro-preco-regiao?msg=rpr003');
            break;

        case 'cuppom_cadastrar':
            // $id = $_POST['id'];     

            if (empty($_POST["cuppom"])) {
                echo "cuppom";
                exit;
            }
            if (trim(empty($_POST['data_inicio'])) || !validaData($_POST['data_inicio'])) {
                echo "data_inicio";
                exit;
            }
            if (trim(empty($_POST['data_fim'])) || !validaData($_POST['data_fim'])) {
                echo "data_fim";
                exit;
            }

            if (empty($_POST["desconto"])) {
                echo "desconto";
                exit;
            }





            $cuppom               = strtolower(addslashes($_POST['cuppom']));
            $data_registro        = date('Y-m-d H:i:s');
            $data_inicio = $_POST['data_inicio'];
            $hora_inicio = $_POST['hora_inicio'];
            $data_hora_inicio = datetime_mysql($data_inicio, $hora_inicio . ':00');
            $data_fim = $_POST['data_fim'];
            $hora_fim = $_POST['hora_fim'];
            $data_hora_fim = datetime_mysql($data_fim, $hora_fim . ' 23:59:59');
            $desconto             = strtolower(addslashes($_POST['desconto']));
            $status = 1;

            $sql = "INSERT INTO bejobs_cuppons (cupom, data_registro, data_ini, data_fim, desconto,status) VALUES ('$cuppom', '$data_registro', '$data_hora_inicio', '$data_hora_fim', '$desconto','$status')";
            $dba->query($sql);

            echo "success";
            header('Location: ./cupom?msg=cup01');
            break;

        case 'cuppom_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_cuppons WHERE id = '$idn'";
            $dba->query($sql);

            header('Location: ./cupom?msg=cup03');
            break;
        case 'cuppom_editar':
            $id = $_POST['id'];

            if (empty($_POST["nome"])) {
                echo "nome";
                exit;
            }
            if (empty($_POST["telefone_celular"])) {
                echo "telefone_celular";
                exit;
            }
            if (empty($_POST["email"])) {
                echo "email";
                exit;
            }

            if (empty($_POST["login"])) {
                echo "login";
                exit;
            }
            $login  = addslashes($_POST['login']);
            $sql2   = "SELECT * FROM bejobs_cuppons WHERE login = '$login' AND id != $id";
            $query2 = $dba->query($sql2);
            $qntd2  = $dba->rows($query2);
            if ($qntd2 > 0) {
                echo "login_existente";
                exit;
            }

            if (empty($_POST['senha'])) {
                echo "senha";
                exit;
            }
            if (empty($_POST['senha_confirma'])) {
                echo "senha_confirma";
                exit;
            }
            if (strlen($_POST['senha']) < 4) {
                echo "senha_caracteres";
                exit;
            }
            if ($_POST['senha'] != $_POST['senha_confirma']) {
                echo "senhas_iguais";
                exit;
            }

            // Senha de no mínimo 6 caracteres
            // $senha = '';
            // if (!empty($_POST['senha'])) {
            // if (empty($_POST['senha_confirma'])) {echo "senha_confirma"; exit;} 
            // if (strlen($_POST['senha']) < 4) {echo "senha_caracteres"; exit;}
            // if ($_POST['senha'] != $_POST['senha_confirma']) {echo "senhas_iguais"; exit;}   
            // $senha_tmp = password_hash($_POST['senha'], PASSWORD_DEFAULT);             
            // $senha = ", senha='".$senha_tmp."'";   
            // }

            $nome             = strtoupper(addslashes($_POST['nome']));
            $email            = strtolower(addslashes($_POST['email']));
            $telefone_celular = addslashes($_POST['telefone_celular']);
            $telefone_celular = preg_replace("/[^0-9]/", "", $telefone_celular);
            $login            = strtolower(addslashes($_POST['login']));
            $senha            = addslashes($_POST['senha']);

            $sql = "UPDATE bejobs_entregadores SET nome='$nome', email='$email', telefone_celular='$telefone_celular', login='$login', senha='$senha' WHERE id=$id";
            $dba->query($sql);

            echo "success_edit";
            break;

        case 'desativar_cuppom':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_cuppons SET status = 0 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./cupom?msg=cup05');
            break;

        case 'ativar_cuppom':
            $idn = $_GET['id'];
            $sql = "UPDATE bejobs_cuppons SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./cupom?msg=cup04');
            break;


        case 'ativar_anuncio':
            $id = $_GET['id'];
            $data = date('Y-m-d H:i:s', strtotime('+ 30 days'));
            $sql = "UPDATE bejobs_anuncios SET status = 1 ,vigencia='$data' WHERE id = $id";
            $dba->query($sql);

            header('Location: ./anuncios');
            break;

        case 'desativar_anuncio':
            $id = $_GET['id'];

            $sql = "UPDATE bejobs_anuncios SET status = 0 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./anuncios');
            break;
        case 'ativar_vaga':
            $id = $_GET['id'];
            $sql = "UPDATE bejobs_vagas SET status = 1  WHERE id = $id";
            $dba->query($sql);

            header('Location: ./lista-vagas');
            break;

        case 'desativar_vaga':
            $id = $_GET['id'];

            $sql = "UPDATE bejobs_vagas SET status = 0 WHERE id = $id";
            $dba->query($sql);

            header('Location: ./lista-vagas');
            break;


        case 'vaga_delete':
            $idn = $_GET['id'];

            $sql = "DELETE FROM bejobs_vagas where id = '$idn'";
            $dba->query($sql);

            header('location: ./lista-vagas?msg=v003');
            break; 
        default:
            header('Location: ./painel');
    }
    // fim do switch
} else {
    header('Location: ./painel');
}
