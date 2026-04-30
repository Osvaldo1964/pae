<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    // 1. Find "endulzante" or "azucar" item
    $stmt = $db->query("SELECT id, name, code FROM items WHERE name LIKE '%endulzan%' OR name LIKE '%azucar%'");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $itemId = 363; // ENDULZANTE

    // 2. Find Recipes matching "general"
    $stmt2 = $db->query("SELECT id, name FROM recipes WHERE name LIKE '%desayuno general%' OR name LIKE '%media mañana general%' OR name LIKE '%almuerzo general%' OR name LIKE '%media tarde general%' OR name LIKE '%cena general%'");
    $recipes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo "\n--- RECETAS ENCONTRADAS ---\n";
    foreach ($recipes as $r) {
        echo "- " . $r['name'] . " (ID: " . $r['id'] . ")\n";
    }

    $recipeIds = array_column($recipes, 'id');

    if (empty($recipeIds)) {
        die("No se encontraron las recetas.\n");
    }

    // 3. Find Quantities for this item in these recipes
    $placeholdersIds = implode(',', array_fill(0, count($recipeIds), '?'));
    $sql3 = "SELECT ri.recipe_id, r.name as recipe_name, ri.age_group, ri.quantity 
             FROM recipe_items ri 
             JOIN recipes r ON ri.recipe_id = r.id
             WHERE ri.item_id = ? AND ri.recipe_id IN ($placeholdersIds)";
    
    $params3 = array_merge([$itemId], $recipeIds);
    $stmt3 = $db->prepare($sql3);
    $stmt3->execute($params3);
    $quantities = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    echo "\n--- CANTIDADES POR RECETA Y GRUPO (en gramos/unidad base) ---\n";
    $totalPerDayGeneral = 0;
    foreach ($quantities as $q) {
        echo "Receta: " . $q['recipe_name'] . " | Grupo: " . $q['age_group'] . " | Cantidad: " . $q['quantity'] . "\n";
        // Assuming age_group 'GENERAL' or we average? The prompt says "minutas desayuno general..." but maybe the age_group is 'SECUNDARIA' or 'GENERAL'
        if ($q['age_group'] === 'GENERAL' || $q['age_group'] === 'SECUNDARIA') {
            $totalPerDayGeneral += $q['quantity'];
        }
    }

    echo "\n--- ANALISIS PARA 70 BENEFICIARIOS X 7 DIAS ---\n";
    echo "Asumiendo grupo etario que suma $totalPerDayGeneral g/unidad al día por beneficiario (basado en lo anterior).\n";
    
    $dias = 7;
    $beneficiarios = 70;
    $totalGramos = $totalPerDayGeneral * $beneficiarios * $dias;

    echo "Cantidad Diaria por beneficiario (todas las recetas del día): " . $totalPerDayGeneral . "\n";
    echo "Cantidad Total por día para 70 beneficiarios: " . ($totalPerDayGeneral * $beneficiarios) . "\n";
    echo "Cantidad Total para los 7 días: " . $totalGramos . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
