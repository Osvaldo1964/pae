<?php
// debug_hostinger.php - Subir a api/ y ejecutar desde navegador
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'utils/Env.php';
require 'config/Database.php';

$db = \Config\Database::getInstance()->getConnection();

echo "<h1>Diagnostico Hostinger - Explosión Insumos</h1>";

function query($db, $sql, $params = [])
{
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "<div style='color:red'>ERROR SQL: $sql <br> " . $e->getMessage() . "</div>";
        return [];
    }
}

// 1. Ver Programas Activos
echo "<h2>1. Programas Activos</h2>";
$programs = query($db, "SELECT id, name FROM pae_programs WHERE status = 'active' OR status = 'ACTIVO'");
echo "<pre>" . print_r($programs, true) . "</pre>";

// 2. Beneficiarios (Primeros 10 activos)
echo "<h2>2. Beneficiarios (Muestra)</h2>";
$bens = query($db, "SELECT id, pae_id, first_name, grade, birth_date, ration_type_id, status FROM beneficiaries WHERE status = 'ACTIVO' LIMIT 10");
echo "<pre>" . print_r($bens, true) . "</pre>";

// 3. Tipos de Ración Disponibles
echo "<h2>3. Tipos de Ración</h2>";
$rationTypes = query($db, "SELECT id, name FROM ration_types");
echo "<pre>" . print_r($rationTypes, true) . "</pre>";

// 4. Ciclos Borrador/Activos
echo "<h2>4. Ciclos (Borrador/Activo)</h2>";
$cycles = query($db, "SELECT id, name, pae_id, status, start_date, end_date FROM menu_cycles WHERE status IN ('BORRADOR', 'ACTIVO')");
echo "<pre>" . print_r($cycles, true) . "</pre>";

if (!empty($cycles)) {
    $cycleId = $cycles[0]['id'];
    echo "<h3>Detalle Ciclo ID: $cycleId</h3>";

    // Menus del ciclo
    $menus = query($db, "SELECT id, name, ration_type_id FROM menus WHERE cycle_id = $cycleId");
    echo "<h4>Menús y su Tipo de Ración</h4>";
    echo "<pre>" . print_r($menus, true) . "</pre>";

    if (!empty($menus)) {
        $menuId = $menus[0]['id'];
        echo "<h4>Recetas del Menú ID: $menuId</h4>";
        // Recetas del menú
        $menuRecipes = query($db, "SELECT mr.recipe_id, r.name, mr.ration_type_id as menu_ration_type 
                                   FROM menu_recipes mr 
                                   JOIN recipes r ON mr.recipe_id = r.id 
                                   WHERE mr.menu_id = $menuId");
        echo "<pre>" . print_r($menuRecipes, true) . "</pre>";

        if (!empty($menuRecipes)) {
            $recipeId = $menuRecipes[0]['recipe_id'];
            echo "<h4>Items de Receta ID: $recipeId (Categoría GENERAL)</h4>";
            $items = query($db, "SELECT id, item_id, age_group, quantity FROM recipe_items WHERE recipe_id = $recipeId AND age_group = 'GENERAL'");
            echo "<pre>" . print_r($items, true) . "</pre>";
        }
    }

    // Proyección (Resultados previos)
    echo "<h4>Proyección Existente (Resultados de cálculo)</h4>";
    $proj = query($db, "SELECT * FROM cycle_projections WHERE cycle_id = $cycleId LIMIT 10");
    echo "<pre>" . print_r($proj, true) . "</pre>";
}
