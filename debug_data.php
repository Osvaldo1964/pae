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
2026-02-06 14:19:23 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 14:19:26 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:19:27 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 14:19:27 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 14:19:35 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:19:36 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 14:19:36 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 14:20:46 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:20:47 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 14:20:47 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 14:20:55 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 14:20:55 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 14:20:55 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-06 14:21:01 - ROUTER: GET /pae/api/reports/needs/104 -> Res: [reports] (7), Act: needs
2026-02-06 14:23:04 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:23:04 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:23:30 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:28:13 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:31:49 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:32:51 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:32:51 - ROUTER: GET /pae/api/beneficiaries -> Res: [beneficiaries] (13), Act: 
2026-02-06 14:33:45 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:33:45 - ROUTER: GET /pae/api/beneficiaries -> Res: [beneficiaries] (13), Act: 
2026-02-06 14:34:15 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:34:15 - ROUTER: GET /pae/api/beneficiaries -> Res: [beneficiaries] (13), Act: 
2026-02-06 14:34:45 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:34:45 - ROUTER: GET /pae/api/beneficiarios -> Res: [beneficiarios] (13), Act: 
2026-02-06 14:34:45 - ROUTER: POST /pae/api/consumptions -> Res: [consumptions] (12), Act: 
2026-02-06 14:34:45 - ROUTER: POST /pae/api/consumptions -> Res: [consumptions] (12), Act: 
2026-02-06 14:34:45 - ROUTER: GET /pae/api/consumptions/stats -> Res: [consumptions] (12), Act: stats
2026-02-06 14:37:11 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:37:11 - ROUTER: GET /pae/api/school-branches -> Res: [school-branches] (15), Act: 
2026-02-06 14:51:27 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:51:27 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:52:40 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:52:40 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:55:53 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:57:12 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:57:13 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:57:37 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 14:57:37 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 14:59:57 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 15:00:05 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 15:00:05 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:00:11 - ROUTER: GET /pae/api/consumptions/stats -> Res: [consumptions] (12), Act: stats
2026-02-06 15:00:43 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 15:00:44 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:00:48 - ROUTER: GET /pae/api/consumptions/stats -> Res: [consumptions] (12), Act: stats
2026-02-06 15:00:59 - ROUTER: GET /pae/api/beneficiarios -> Res: [beneficiarios] (13), Act: 
2026-02-06 15:00:59 - ROUTER: GET /pae/api/schools -> Res: [schools] (7), Act: 
2026-02-06 15:00:59 - ROUTER: GET /pae/api/beneficiarios/document_types -> Res: [beneficiarios] (13), Act: document_types
2026-02-06 15:00:59 - ROUTER: GET /pae/api/beneficiarios/ethnic_groups -> Res: [beneficiarios] (13), Act: ethnic_groups
2026-02-06 15:48:50 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 15:48:52 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 15:48:53 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 15:48:53 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 15:48:55 - ROUTER: GET /pae/api/beneficiarios -> Res: [beneficiarios] (13), Act: 
2026-02-06 15:48:55 - ROUTER: GET /pae/api/schools -> Res: [schools] (7), Act: 
2026-02-06 15:48:55 - ROUTER: GET /pae/api/beneficiarios/document_types -> Res: [beneficiarios] (13), Act: document_types
2026-02-06 15:48:55 - ROUTER: GET /pae/api/beneficiarios/ethnic_groups -> Res: [beneficiarios] (13), Act: ethnic_groups
2026-02-06 15:48:58 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:48:58 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:48:58 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:49:13 - ROUTER: GET /pae/api/beneficiarios/print-list -> Res: [beneficiarios] (13), Act: print-list
2026-02-06 15:49:23 - ROUTER: GET /pae/api/beneficiarios/print-list -> Res: [beneficiarios] (13), Act: print-list
2026-02-06 15:49:36 - ROUTER: GET /pae/api/beneficiarios/print-list -> Res: [beneficiarios] (13), Act: print-list
2026-02-06 15:49:51 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:49:51 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:49:51 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 15:50:10 - ROUTER: GET /pae/api/beneficiarios/print-list -> Res: [beneficiarios] (13), Act: print-list
2026-02-06 15:50:35 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:17:03 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:17:03 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:17:03 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:17:16 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:17:16 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:17:16 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:17:16 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:17:23 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:17:23 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:17:23 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:18:28 - ROUTER: POST /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:18:28 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:18:28 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:18:28 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:18:32 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 16:18:53 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 16:25:15 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:25:15 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:25:15 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:25:20 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:25:20 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:25:20 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:25:22 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 16:26:51 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 16:26:51 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:26:51 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:26:51 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 16:27:03 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 16:28:03 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 16:28:03 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:28:03 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:28:30 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:28:30 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:28:30 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:28:38 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 16:28:44 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 16:36:07 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 16:36:07 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 16:36:13 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 16:42:05 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:42:06 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 16:42:06 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:42:06 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 16:42:06 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 16:42:12 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 16:42:25 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 16:42:25 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-06 16:42:25 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 16:42:37 - ROUTER: POST /pae/api/menu-cycles/approve/104 -> Res: [menu-cycles] (11), Act: approve
DEBUG: Entering menu-cycles block (MATCHED TOP)
2026-02-06 16:45:49 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:45:49 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 16:45:49 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-06 16:45:53 - ROUTER: POST /pae/api/menu-cycles/approve/104 -> Res: [menu-cycles] (11), Act: approve
2026-02-06 16:47:42 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:47:42 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 16:47:42 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 16:47:42 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-06 16:47:45 - ROUTER: POST /pae/api/menu-cycles/approve/104 -> Res: [menu-cycles] (11), Act: approve
2026-02-06 16:56:54 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:56:56 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 16:56:57 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 16:56:57 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 16:57:00 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
), Act: 
2026-02-06 16:57:00 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 16:57:02 - ROUTER: POST /pae/api/menu-cycles/approve/104 -> Res: [menu-cycles] (11), Act: approve
2026-02-06 16:57:02 - ROUTER: GET /pae/api/cycle-templates -> Res: [cycle-templates] (15), Act: 
2026-02-06 16:57:02 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 16:57:02 - ROUTER: GET /pae/api/recipes -> Res: [recipes] (7), Act: 
2026-02-06 16:57:08 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 16:57:08 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 16:57:08 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 16:57:14 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:00:02 - ROUTER: POST /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:00:02 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:00:02 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:00:02 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:00:02 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:00:16 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:00:32 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:00:50 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:03:06 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:03:06 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:03:06 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:03:06 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:03:06 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:03:09 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:03:09 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:03:09 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:03:09 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:03:09 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:14:50 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:14:50 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:14:50 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:14:50 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:14:50 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:14:55 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:14:55 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:14:55 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:14:55 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:14:55 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:18:00 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:18:00 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
, Act: 
2026-02-06 17:18:00 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:18:00 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:18:02 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:18:02 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:18:02 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:18:02 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:18:02 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:20:51 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:20:51 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:20:51 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:20:51 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:20:51 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:20:53 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:20:53 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:20:53 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:20:53 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:20:55 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:21:23 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:23:51 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:23:51 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:23:51 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:23:51 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:23:51 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:23:53 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:23:53 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:24:04 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:24:34 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 17:24:34 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:24:34 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:24:40 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:25:25 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:25:25 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:25:25 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:25:25 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:25:26 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:25:26 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:25:30 - ROUTER: PUT /pae/api/purchase-orders/1 -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:28:46 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:28:46 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:28:46 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:28:46 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:28:46 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:28:49 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:28:49 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:28:52 - ROUTER: PUT /pae/api/purchase-orders/1 -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:28:52 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:28:52 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:28:52 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:28:52 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:28:54 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:28:54 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:32:09 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:32:09 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:32:09 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:32:09 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:32:09 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:32:11 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:32:11 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:32:24 - ROUTER: PUT /pae/api/purchase-orders/1 -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:32:24 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:32:24 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:32:24 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:32:24 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:32:26 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:32:26 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:32:55 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:32:55 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:32:55 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:32:57 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 17:33:08 - ROUTER: PUT /pae/api/quotes/1 -> Res: [quotes] (6), Act: 1
2026-02-06 17:33:08 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:33:08 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:33:08 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:33:13 - ROUTER: GET /pae/api/quotes/1/details -> Res: [quotes] (6), Act: 1
2026-02-06 17:33:16 - ROUTER: GET /pae/api/quotes/2/details -> Res: [quotes] (6), Act: 2
2026-02-06 17:36:26 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:36:27 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:36:27 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:36:27 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:39:56 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:39:56 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:39:56 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:39:56 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:40:01 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:40:01 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:40:01 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:40:01 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:40:06 - ROUTER: GET /pae/api/quotes/2/details -> Res: [quotes] (6), Act: 2
2026-02-06 17:40:12 - ROUTER: PUT /pae/api/quotes/2 -> Res: [quotes] (6), Act: 2
2026-02-06 17:43:32 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:43:32 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
2026-02-06 17:43:32 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:43:32 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:43:36 - ROUTER: DELETE /pae/api/quotes/1 -> Res: [quotes] (6), Act: 1
2026-02-06 17:43:36 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
11), Act: 
2026-02-06 17:43:36 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:43:38 - ROUTER: GET /pae/api/quotes/2/details -> Res: [quotes] (6), Act: 2
2026-02-06 17:43:44 - ROUTER: PUT /pae/api/quotes/2 -> Res: [quotes] (6), Act: 2
2026-02-06 17:43:44 - ROUTER: GET /pae/api/quotes -> Res: [quotes] (6), Act: 
11), Act: 
2026-02-06 17:43:44 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:43:47 - ROUTER: GET /pae/api/quotes/2/details -> Res: [quotes] (6), Act: 2
2026-02-06 17:44:34 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:44:34 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:44:34 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
, Act: 
2026-02-06 17:44:38 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 17:44:38 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:44:49 - ROUTER: GET /pae/api/inventory/cycle-projections/104 -> Res: [inventory] (9), Act: cycle-projections
2026-02-06 17:45:20 - ROUTER: POST /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:45:20 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:45:20 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:45:20 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 17:45:20 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 17:45:28 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 17:45:28 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 17:45:28 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 17:45:28 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
 (11), Act: 
2026-02-06 18:36:09 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 18:36:11 - ROUTER: POST /pae/api/auth/login -> Res: [auth] (4), Act: login
2026-02-06 18:36:12 - ROUTER: POST /pae/api/auth/select_tenant -> Res: [auth] (4), Act: select_tenant
2026-02-06 18:36:12 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 18:36:16 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 18:36:16 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:36:16 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:36:16 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 18:36:23 - ROUTER: DELETE /pae/api/purchase-orders/3 -> Res: [purchase-orders] (15), Act: 3
2026-02-06 18:36:23 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 18:36:23 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 18:36:23 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:41:54 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
 
, Act: 
2026-02-06 18:41:54 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:41:59 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 18:42:20 - ROUTER: POST /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 18:42:21 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 18:42:21 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:42:21 - ROUTER: GET /pae/api/purchase-orders -> Res: [purchase-orders] (15), Act: 
2026-02-06 18:42:21 - ROUTER: GET /pae/api/proveedores -> Res: [proveedores] (11), Act: 
2026-02-06 18:42:24 - ROUTER: GET /pae/api/remissions/1/details -> Res: [remissions] (10), Act: 1
2026-02-06 18:42:42 - ROUTER: GET /pae/api/purchase-orders/1/details -> Res: [purchase-orders] (15), Act: 1
2026-02-06 18:43:07 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
ct: 
2026-02-06 18:43:07 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:43:07 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:43:17 - ROUTER: GET /pae/api/inventory/branch-projections/104/17 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 18:48:32 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 18:48:32 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 18:48:32 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:48:32 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:48:32 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 18:48:39 - ROUTER: GET /pae/api/inventory/branch-projections/104/17 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 18:52:57 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 18:52:57 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 18:52:57 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:52:57 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 18:52:57 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:53:03 - ROUTER: GET /pae/api/inventory/branch-projections/104/1 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 18:56:04 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 18:56:05 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 18:56:05 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 18:56:05 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 18:56:05 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 18:56:11 - ROUTER: GET /pae/api/inventory/branch-projections/104/17 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 19:00:16 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 19:00:16 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 19:00:16 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 19:00:16 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 19:00:16 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 19:00:21 - ROUTER: GET /pae/api/inventory/branch-projections/104/17 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 19:00:34 - ROUTER: GET /pae/api/auth/menu -> Res: [auth] (4), Act: menu
2026-02-06 19:00:34 - ROUTER: GET /pae/api/remissions -> Res: [remissions] (10), Act: 
2026-02-06 19:00:34 - ROUTER: GET /pae/api/branches -> Res: [branches] (8), Act: 
2026-02-06 19:00:34 - ROUTER: GET /pae/api/items -> Res: [items] (5), Act: 
2026-02-06 19:00:34 - ROUTER: GET /pae/api/menu-cycles -> Res: [menu-cycles] (11), Act: 
2026-02-06 19:00:41 - ROUTER: GET /pae/api/inventory/branch-projections/104/17 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 19:02:16 - ROUTER: GET /pae/api/inventory/branch-projections/104/9 -> Res: [inventory] (9), Act: branch-projections
2026-02-06 19:16:56 - ROUTER: GET /pae/api/beneficiarios -> Res: [beneficiarios] (13), Act: 
2026-02-06 19:16:56 - ROUTER: GET /pae/api/beneficiarios/document_types -> Res: [beneficiarios] (13), Act: document_types
2026-02-06 19:16:56 - ROUTER: GET /pae/api/schools -> Res: [schools] (7), Act: 
2026-02-06 19:16:56 - ROUTER: GET /pae/api/beneficiarios/ethnic_groups -> Res: [beneficiarios] (13), Act: ethnic_groups
