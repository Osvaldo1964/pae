<?php
require_once 'api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, ration_type, shift, modality FROM beneficiaries WHERE id = 31");
$stmt->execute();
$data = $stmt->fetch();
header('Content-Type: text/plain');
echo "ID: " . $data['id'] . "\n";
echo "Ration Type: [" . $data['ration_type'] . "]\n";
echo "Ration Type Hex: " . bin2hex($data['ration_type']) . "\n";
echo "Shift: [" . $data['shift'] . "]\n";
echo "Modality: [" . $data['modality'] . "]\n";
?>2026-02-04 15:55:28 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 15:55:28 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 15:55:28 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 15:55:28 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 15:55:30 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: menu-cycles, Act: 103
2026-02-04 15:55:30 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 15:55:30 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 15:55:30 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 15:55:35 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 15:55:35 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 15:55:35 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 15:55:35 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:00:14 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 16:00:14 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:00:14 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:00:14 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:00:17 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: menu-cycles, Act: 103
2026-02-04 16:00:17 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:00:17 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:00:17 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:03:28 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 16:03:28 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:03:28 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:03:28 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:03:31 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: menu-cycles, Act: 103
2026-02-04 16:03:31 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:03:31 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:03:31 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:03:36 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
, Act: 
2026-02-04 16:03:36 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:03:39 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 16:03:39 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:03:39 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:03:41 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: menu-cycles, Act: 103
2026-02-04 16:03:41 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:03:41 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:03:41 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:04:27 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:04:27 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:04:27 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:07:07 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 16:07:07 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:07:07 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:07:07 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:07:10 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: menu-cycles, Act: 103
2026-02-04 16:07:10 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 16:07:10 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:07:10 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:07:14 - ROUTER: GET /pae/api/auth/menu -> Res: auth, Act: menu
2026-02-04 16:07:14 - ROUTER: GET /pae/api/cycle-templates -> Res: cycle-templates, Act: 
2026-02-04 16:07:14 - ROUTER: GET /pae/api/recipes -> Res: recipes, Act: 
2026-02-04 16:07:14 - ROUTER: GET /pae/api/menu-cycles -> Res: menu-cycles, Act: 
2026-02-04 20:07:34 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:07:36 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-04 20:07:37 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-04 20:07:37 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:07:46 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:07:46 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:07:46 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:07:48 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: [menu-cycles] (11), Act: 103
2026-02-04 20:07:48 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:07:48 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:07:48 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:07:51 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:07:51 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:07:51 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:07:51 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:24:09 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:24:09 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:24:09 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:24:09 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:24:12 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: [menu-cycles] (11), Act: 103
2026-02-04 20:24:12 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:24:12 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:24:12 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:28:20 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:28:20 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:28:20 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:28:20 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:28:23 - ROUTER: DELETE /pae/api/menu-cycles/103 -> Res: [menu-cycles] (11), Act: 103
2026-02-04 20:28:23 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:28:23 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:28:23 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:28:43 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:28:43 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:28:43 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:28:43 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:29:03 - ROUTER: POST /pae/api/menu-cycles/generate -> Res: [menu-cycles] (11), Act: generate
2026-02-04 20:29:03 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
, Act: 
2026-02-04 20:29:03 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:29:05 - ROUTER: GET /pae/api/menu-cycles/104 -> Res: [menu-cycles] (11), Act: 104
2026-02-04 20:29:12 - ROUTER: DELETE /pae/api/menu-cycles/104 -> Res: [menu-cycles] (11), Act: 104
2026-02-04 20:29:12 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:29:12 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:29:12 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:29:14 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:29:14 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:29:14 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-04 20:35:03 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-04 20:35:04 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-04 20:35:04 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:35:06 - ROUTER: DELETE /pae/api/menu-cycles/104 -> Res: [menu-cycles] (11), Act: 104
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-04 20:35:06 - DELETE Method Entered for ID: 104
 - Target found. Starting Transaction.
 - Deleted 0 menu_items.
 - Deleted 40 menus.
 - Deleted 1 menu_cycles.
 - Transaction Committed.
2026-02-04 20:35:06 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-04 20:35:06 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:35:06 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-04 20:35:28 - ROUTER: POST /pae/api/menu-cycles/generate -> Res: [menu-cycles] (11), Act: generate
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-04 20:53:12 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-04 20:53:12 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-04 20:53:14 - ROUTER: GET /pae/api/recipes/2 -> Res: [recipes] (7), Act: 2
