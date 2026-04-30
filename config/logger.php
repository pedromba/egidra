<?php
/**
 * Inserta un registro en logs_actividad.
 *
 * @param mysqli $conexion
 * @param int|null $usuario_id
 * @param string $accion       LOGIN | CREAR | EDITAR | ELIMINAR | SISTEMA
 * @param string $descripcion
 * @param string|null $tabla   Tabla afectada
 * @param int|null $registro_id ID del registro afectado
 */
function registrar_log($conexion, $usuario_id, $accion, $descripcion, $tabla = null, $registro_id = null) {
    $ip   = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = $conexion->prepare(
        "INSERT INTO logs_actividad (usuario_id, accion, descripcion_cambio, tabla_afectada, registro_id, direccion_ip)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    if (!$stmt) return;
    $stmt->bind_param('isssis', $usuario_id, $accion, $descripcion, $tabla, $registro_id, $ip);
    $stmt->execute();
}
