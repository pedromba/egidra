<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';
require_once '../../../../config/rutas.php';

$id     = isset($_POST['id'])               ? (int)  $_POST['id']                : 0;
$titulo = isset($_POST['titulo'])           ? trim(  $_POST['titulo'])            : '';
$cat_id = isset($_POST['categoria_id'])     ? (int)  $_POST['categoria_id']      : null;
$cli_id = isset($_POST['cliente_id'])       ? (int)  $_POST['cliente_id']        : null;
$ubic   = isset($_POST['ubicacion'])        ? trim(  $_POST['ubicacion'])         : '';
$ano    = isset($_POST['ano_finalizacion']) ? (int)  $_POST['ano_finalizacion']   : null;
$desc   = isset($_POST['descripcion'])      ? trim(  $_POST['descripcion'])       : '';
$dest   = isset($_POST['es_destacado'])     ? (int)(bool) $_POST['es_destacado'] : 0;
$activo = isset($_POST['activo'])           ? (int)(bool) $_POST['activo']       : 1;

if ($titulo === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El título es obligatorio.']);
    exit;
}

// Imagen: archivo subido tiene prioridad sobre la ruta existente
$imagen = isset($_POST['imagen_actual']) ? trim($_POST['imagen_actual']) : '';

if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
    $file    = $_FILES['imagen_file'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $allowed)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Tipo no permitido. Use JPG, PNG o WebP.']);
        exit;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode(['estado' => false, 'mensaje' => 'La imagen no puede superar 2 MB.']);
        exit;
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = 'proy_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dir      = DIR_BASE . 'img/proyectos/';

    if (!is_dir($dir)) mkdir($dir, 0755, true);

    if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar la imagen.']);
        exit;
    }
    $imagen = 'img/proyectos/' . $filename;
}

// Tipos por parámetro:
//  titulo=s  cat=i  cli=i  ubic=s  ano=i  desc=s  imagen=s  dest=i  activo=i  [id=i para UPDATE]
// 'siisis' = titulo,cat,cli,ubic,ano,desc  |  'sii' = imagen,dest,activo  |  'i' = id (UPDATE)

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE proyectos
         SET titulo=?, categoria_id=?, cliente_id=?, ubicacion=?,
             ano_finalizacion=?, descripcion_tecnica=?, imagen=?, es_destacado=?, activo=?
         WHERE id_proyecto=?"
    );
    $stmt->bind_param('siisis' . 'siii', $titulo, $cat_id, $cli_id, $ubic, $ano, $desc, $imagen, $dest, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO proyectos
         (titulo, categoria_id, cliente_id, ubicacion, ano_finalizacion,
          descripcion_tecnica, imagen, es_destacado, activo)
         VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('siisis' . 'sii', $titulo, $cat_id, $cli_id, $ubic, $ano, $desc, $imagen, $dest, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($titulo ?? 'registro'), 'proyectos', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Proyecto guardado.', 'id' => $nuevo_id, 'imagen' => $imagen]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar: ' . $conexion->error]);
}
$stmt->close();
