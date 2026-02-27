<?php
/**
 * Weapon API - AJAX replacement for Socket.IO
 * PHP 7.4+
 * 
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

require_once ROOT_DIR . '/src/db.php';
require_once ROOT_DIR . '/src/helpers.php';

function handleWeaponApi(string $path, array $config): void
{
    header('Content-Type: application/json');

    $pdo = getDb($config);

    // Extract action from path: /api/weapon/{action}
    $action = str_replace('/api/weapon/', '', $path);
    $action = rtrim($action, '/');

    $data = getJsonBody();

    switch ($action) {
        case 'get-weapon-skins':
            getWeaponSkins($pdo, $data);
            break;
        case 'get-team-knives':
            getTeamKnives($pdo, $data);
            break;
        case 'get-team-gloves':
            getTeamGloves($pdo, $data);
            break;
        case 'change-knife':
            changeKnife($pdo, $data);
            break;
        case 'change-gloves':
            changeGloves($pdo, $data);
            break;
        case 'revert-equipped-gloves':
            revertEquippedGloves($pdo, $data);
            break;
        case 'change-skin':
            changeSkin($pdo, $data);
            break;
        case 'change-agent':
            changeAgent($pdo, $data);
            break;
        case 'change-music':
            changeMusic($pdo, $data);
            break;
        case 'unequip-knife':
            unequipKnife($pdo, $data);
            break;
        case 'unequip-gloves':
            unequipGloves($pdo, $data);
            break;
        case 'reset-skin':
            resetSkin($pdo, $data);
            break;
        case 'change-params':
            changeParams($pdo, $data);
            break;
        default:
            jsonResponse(['error' => 'Unknown action'], 404);
    }
}

function getWeaponSkins(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $weaponid = (int)($data['weaponid'] ?? 0);
    $type = $data['type'] ?? '';

    $stmt = $pdo->prepare("SELECT weapon_team, weapon_paint_id FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :wid");
    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid]);
    $weaponSkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'weaponid'    => $weaponid,
        'type'        => $type,
        'weaponSkins' => $weaponSkins,
    ]);
}

function getTeamKnives(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';

    $stmt = $pdo->prepare("SELECT weapon_team, knife FROM wp_player_knife WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $knives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(['knives' => $knives]);
}

function getTeamGloves(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';

    $stmt = $pdo->prepare("SELECT weapon_team, weapon_defindex FROM wp_player_gloves WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $gloves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(['gloves' => $gloves]);
}

function changeKnife(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $knifename = $data['knifename'] ?? '';
    $knifeid = (int)($data['knifeid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);
    $isunequip = !empty($data['isunequip']);

    $oldKnivesWithTeamId = [];

    if ($teamid === 0) {
        $stmt = $pdo->prepare("SELECT weapon_team, knife FROM wp_player_knife WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $getOldKnives = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($getOldKnives) >= 2) {
            if ($getOldKnives[0]['knife'] === $knifename) {
                $oldKnivesWithTeamId[] = $getOldKnives[1];
            } elseif ($getOldKnives[1]['knife'] === $knifename) {
                $oldKnivesWithTeamId[] = $getOldKnives[0];
            } else {
                $oldKnivesWithTeamId = $getOldKnives;
            }
        }
    } else {
        $stmt = $pdo->prepare("SELECT weapon_team, knife FROM wp_player_knife WHERE steamid = :sid AND weapon_team = :tid");
        $stmt->execute(['sid' => $steamid, 'tid' => $teamid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $oldKnivesWithTeamId[] = $row;
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_knife WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $getKnives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($getKnives) === 2) {
        if ($teamid === 2 || $teamid === 3) {
            $stmt = $pdo->prepare("UPDATE wp_player_knife SET knife = :kn WHERE steamid = :sid AND weapon_team = :tid");
            $stmt->execute(['kn' => $knifename, 'sid' => $steamid, 'tid' => $teamid]);
        } else {
            $stmt = $pdo->prepare("UPDATE wp_player_knife SET knife = :kn WHERE steamid = :sid");
            $stmt->execute(['kn' => $knifename, 'sid' => $steamid]);

            // Sync existing knife skins
            $stmt = $pdo->prepare("SELECT weapon_team, weapon_paint_id, weapon_wear, weapon_seed FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :kid");
            $stmt->execute(['sid' => $steamid, 'kid' => $knifeid]);
            $knifeSkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($knifeSkins) === 1) {
                $oppositeTeamId = ($knifeSkins[0]['weapon_team'] == 2) ? 3 : 2;
                $stmt = $pdo->prepare("INSERT INTO wp_player_skins (steamid, weapon_defindex, weapon_team, weapon_paint_id, weapon_wear, weapon_seed) VALUES (:sid, :kid, :tid, :pid, :wear, :seed)");
                $stmt->execute([
                    'sid'  => $steamid,
                    'kid'  => $knifeid,
                    'tid'  => $oppositeTeamId,
                    'pid'  => $knifeSkins[0]['weapon_paint_id'],
                    'wear' => $knifeSkins[0]['weapon_wear'],
                    'seed' => $knifeSkins[0]['weapon_seed'],
                ]);
            }
        }
    } else {
        if (!$isunequip) {
            if ($teamid === 2 || $teamid === 3) {
                $stmt = $pdo->prepare("INSERT INTO wp_player_knife (steamid, weapon_team, knife) VALUES (:sid, :tid, :kn)");
                $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'kn' => $knifename]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO wp_player_knife (steamid, weapon_team, knife) VALUES (:sid, :tid, :kn)");
                $stmt->execute(['sid' => $steamid, 'tid' => 2, 'kn' => $knifename]);
                $stmt->execute(['sid' => $steamid, 'tid' => 3, 'kn' => $knifename]);
            }
        }
    }

    jsonResponse([
        'oldKnivesWithTeamId' => $oldKnivesWithTeamId,
        'newKnifeName'        => $knifename,
    ]);
}

function changeGloves(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $glovesid = (int)($data['glovesid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);
    $isunequip = !empty($data['isunequip']);

    $oldGlovesWithTeamId = [];

    if ($teamid === 0) {
        $stmt = $pdo->prepare("SELECT weapon_team, weapon_defindex FROM wp_player_gloves WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $getOldGlovesAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($getOldGlovesAll) >= 2) {
            if ((int)$getOldGlovesAll[0]['weapon_defindex'] === $glovesid) {
                $oldGlovesWithTeamId[] = $getOldGlovesAll[1];
            } elseif ((int)$getOldGlovesAll[1]['weapon_defindex'] === $glovesid) {
                $oldGlovesWithTeamId[] = $getOldGlovesAll[0];
            } else {
                $oldGlovesWithTeamId = $getOldGlovesAll;
            }
        }
    } else {
        $stmt = $pdo->prepare("SELECT weapon_team, weapon_defindex FROM wp_player_gloves WHERE steamid = :sid AND weapon_team = :tid");
        $stmt->execute(['sid' => $steamid, 'tid' => $teamid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $oldGlovesWithTeamId[] = $row;
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_gloves WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $getGloves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($getGloves) === 2) {
        if ($teamid === 2 || $teamid === 3) {
            $stmt = $pdo->prepare("UPDATE wp_player_gloves SET weapon_defindex = :gid WHERE steamid = :sid AND weapon_team = :tid");
            $stmt->execute(['gid' => $glovesid, 'sid' => $steamid, 'tid' => $teamid]);
        } else {
            $stmt = $pdo->prepare("UPDATE wp_player_gloves SET weapon_defindex = :gid WHERE steamid = :sid");
            $stmt->execute(['gid' => $glovesid, 'sid' => $steamid]);

            // Sync gloves skins
            $stmt = $pdo->prepare("SELECT weapon_team, weapon_paint_id, weapon_wear, weapon_seed FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :gid");
            $stmt->execute(['sid' => $steamid, 'gid' => $glovesid]);
            $glovesSkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($glovesSkins) === 1) {
                $oppositeTeamId = ($glovesSkins[0]['weapon_team'] == 2) ? 3 : 2;
                $stmt = $pdo->prepare("INSERT INTO wp_player_skins (steamid, weapon_defindex, weapon_team, weapon_paint_id, weapon_wear, weapon_seed) VALUES (:sid, :gid, :tid, :pid, :wear, :seed)");
                $stmt->execute([
                    'sid'  => $steamid,
                    'gid'  => $glovesid,
                    'tid'  => $oppositeTeamId,
                    'pid'  => $glovesSkins[0]['weapon_paint_id'],
                    'wear' => $glovesSkins[0]['weapon_wear'],
                    'seed' => $glovesSkins[0]['weapon_seed'],
                ]);
            }
        }
    } else {
        if (!$isunequip) {
            if ($teamid === 2 || $teamid === 3) {
                $stmt = $pdo->prepare("INSERT INTO wp_player_gloves (steamid, weapon_team, weapon_defindex) VALUES (:sid, :tid, :gid)");
                $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'gid' => $glovesid]);
            } else {
                if (count($getGloves) === 1) {
                    $oppTeam = ($getGloves[0]['weapon_team'] == 2) ? 3 : 2;
                    $stmt = $pdo->prepare("INSERT INTO wp_player_gloves (steamid, weapon_team, weapon_defindex) VALUES (:sid, :tid, :gid)");
                    $stmt->execute(['sid' => $steamid, 'tid' => $oppTeam, 'gid' => $glovesid]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO wp_player_gloves (steamid, weapon_team, weapon_defindex) VALUES (:sid, :tid, :gid)");
                    $stmt->execute(['sid' => $steamid, 'tid' => 2, 'gid' => $glovesid]);
                    $stmt->execute(['sid' => $steamid, 'tid' => 3, 'gid' => $glovesid]);
                }
            }
        }
    }

    jsonResponse([
        'oldGlovesWithTeamId' => $oldGlovesWithTeamId,
        'newGlovesId'         => $glovesid,
    ]);
}

function revertEquippedGloves(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $weaponid = (int)($data['weaponid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);

    if ($teamid === 2 || $teamid === 3) {
        $stmt = $pdo->prepare("DELETE FROM wp_player_gloves WHERE steamid = :sid AND weapon_team = :tid AND weapon_defindex = :wid");
        $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'wid' => $weaponid]);
    } elseif ($teamid === 0) {
        $stmt = $pdo->prepare("DELETE FROM wp_player_gloves WHERE steamid = :sid AND weapon_defindex = :wid");
        $stmt->execute(['sid' => $steamid, 'wid' => $weaponid]);
    }

    $oppositeTeamId = ($teamid === 2) ? 3 : 2;
    $stmt = $pdo->prepare("SELECT weapon_defindex FROM wp_player_gloves WHERE steamid = :sid AND weapon_team = :tid");
    $stmt->execute(['sid' => $steamid, 'tid' => $oppositeTeamId]);
    $oppositeTeamEquippedGloves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($teamid !== 0 && count($oppositeTeamEquippedGloves) === 1) {
        $stmt = $pdo->prepare("INSERT INTO wp_player_gloves (steamid, weapon_team, weapon_defindex) VALUES (:sid, :tid, :gid)");
        $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'gid' => $oppositeTeamEquippedGloves[0]['weapon_defindex']]);
    }

    $oppositeGlovesId = ($teamid !== 0 && count($oppositeTeamEquippedGloves) === 1)
        ? (int)$oppositeTeamEquippedGloves[0]['weapon_defindex']
        : -1;

    jsonResponse([
        'teamid'           => $teamid,
        'oppositeGlovesId' => $oppositeGlovesId,
    ]);
}

function changeSkin(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $weaponid = (int)($data['weaponid'] ?? 0);
    $paintid = (int)($data['paintid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);
    $isreset = !empty($data['isreset']);

    $stmt = $pdo->prepare("SELECT * FROM wp_player_skins WHERE weapon_defindex = :wid AND steamid = :sid");
    $stmt->execute(['wid' => $weaponid, 'sid' => $steamid]);
    $getSkin = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($getSkin) === 2) {
        if ($teamid === 2 || $teamid === 3) {
            $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_paint_id = :pid WHERE steamid = :sid AND weapon_team = :tid AND weapon_defindex = :wid");
            $stmt->execute(['pid' => $paintid, 'sid' => $steamid, 'tid' => $teamid, 'wid' => $weaponid]);
        } else {
            $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_paint_id = :pid WHERE steamid = :sid AND weapon_defindex = :wid");
            $stmt->execute(['pid' => $paintid, 'sid' => $steamid, 'wid' => $weaponid]);
        }
    } else {
        if (!$isreset) {
            if ($teamid === 2 || $teamid === 3) {
                $stmt = $pdo->prepare("SELECT weapon_team, weapon_paint_id, weapon_wear, weapon_seed FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_team = :tid");
                $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'tid' => $teamid]);
                $teamSkin = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($teamSkin) === 1) {
                    $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_paint_id = :pid WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_team = :tid");
                    $stmt->execute(['pid' => $paintid, 'sid' => $steamid, 'wid' => $weaponid, 'tid' => $teamid]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO wp_player_skins (steamid, weapon_defindex, weapon_team, weapon_paint_id) VALUES (:sid, :wid, :tid, :pid)");
                    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'tid' => $teamid, 'pid' => $paintid]);
                }
            } else {
                if (count($getSkin) === 1) {
                    $oppTeam = ($getSkin[0]['weapon_team'] == 2) ? 3 : 2;
                    $stmt = $pdo->prepare("INSERT INTO wp_player_skins (steamid, weapon_defindex, weapon_team, weapon_paint_id) VALUES (:sid, :wid, :tid, :pid)");
                    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'tid' => $oppTeam, 'pid' => $paintid]);

                    $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_paint_id = :pid WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_team = :tid");
                    $stmt->execute(['pid' => $paintid, 'sid' => $steamid, 'wid' => $weaponid, 'tid' => $getSkin[0]['weapon_team']]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO wp_player_skins (steamid, weapon_defindex, weapon_team, weapon_paint_id) VALUES (:sid, :wid, :tid, :pid)");
                    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'tid' => 2, 'pid' => $paintid]);
                    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'tid' => 3, 'pid' => $paintid]);
                }
            }
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_skins WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $playerSkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'weaponid'    => $weaponid,
        'paintid'     => $paintid,
        'playerSkins' => $playerSkins,
    ]);
}

function changeAgent(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $model   = $data['model']   ?? '';
    $team    = $data['team']    ?? '';

    // Strict validation for team to prevent SQL injection
    if ($team !== 'ct' && $team !== 't') {
        jsonResponse(['error' => 'Invalid team'], 400);
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_agents WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $getAgent = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($getAgent) >= 1) {
        $stmt = $pdo->prepare("UPDATE wp_player_agents SET agent_{$team} = :model WHERE steamid = :sid");
        $stmt->execute(['model' => $model, 'sid' => $steamid]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO wp_player_agents (steamid, agent_{$team}) VALUES (:sid, :model)");
        $stmt->execute(['sid' => $steamid, 'model' => $model]);
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_agents WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'agents'       => $agents,
        'currentAgent' => $model,
    ]);
}

function changeMusic(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $id = (int)($data['id'] ?? 0);

    $stmt = $pdo->prepare("SELECT * FROM wp_player_music WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $getMusic = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($getMusic) >= 1) {
        $stmt = $pdo->prepare("UPDATE wp_player_music SET music_id = :mid WHERE steamid = :sid");
        $stmt->execute(['mid' => $id, 'sid' => $steamid]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO wp_player_music (steamid, weapon_team, music_id) VALUES (:sid, 0, :mid)");
        $stmt->execute(['sid' => $steamid, 'mid' => $id]);
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_music WHERE steamid = :sid");
    $stmt->execute(['sid' => $steamid]);
    $newMusic = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'currentMusic' => $id,
        'music'        => $newMusic,
    ]);
}

function unequipKnife(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $teamid = (int)($data['teamid'] ?? 0);
    $knifeid = $data['knifeid'] ?? '';

    $stmt = $pdo->prepare("DELETE FROM wp_player_knife WHERE steamid = :sid AND weapon_team = :tid AND knife = :kid");
    $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'kid' => $knifeid]);

    $stmt = $pdo->prepare("SELECT * FROM wp_player_knife WHERE steamid = :sid AND knife != 'weapon_knife'");
    $stmt->execute(['sid' => $steamid]);
    $knivesLeft = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("INSERT INTO wp_player_knife (steamid, weapon_team, knife) VALUES (:sid, :tid, 'weapon_knife')");
    $stmt->execute(['sid' => $steamid, 'tid' => $teamid]);

    jsonResponse([
        'knifeid'    => $knifeid,
        'teamid'     => $teamid,
        'knivesLeft' => count($knivesLeft),
    ]);
}

function unequipGloves(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $teamid = (int)($data['teamid'] ?? 0);
    $glovesid = (int)($data['glovesid'] ?? 0);

    $stmt = $pdo->prepare("DELETE FROM wp_player_gloves WHERE steamid = :sid AND weapon_team = :tid AND weapon_defindex = :gid");
    $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'gid' => $glovesid]);

    $stmt = $pdo->prepare("INSERT INTO wp_player_gloves (steamid, weapon_team, weapon_defindex) VALUES (:sid, :tid, 0)");
    $stmt->execute(['sid' => $steamid, 'tid' => $teamid]);

    jsonResponse([
        'glovesid' => $glovesid,
        'teamid'   => $teamid,
    ]);
}

function resetSkin(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $weaponid = (int)($data['weaponid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);

    if ($teamid === 2 || $teamid === 3) {
        $stmt = $pdo->prepare("DELETE FROM wp_player_skins WHERE steamid = :sid AND weapon_team = :tid AND weapon_defindex = :wid");
        $stmt->execute(['sid' => $steamid, 'tid' => $teamid, 'wid' => $weaponid]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :wid");
        $stmt->execute(['sid' => $steamid, 'wid' => $weaponid]);
    }

    $stmt = $pdo->prepare("SELECT * FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :wid");
    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid]);
    $weaponSkinsLeft = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'weaponid'        => $weaponid,
        'teamid'          => $teamid,
        'weaponSkinsLeft' => count($weaponSkinsLeft),
    ]);
}

function changeParams(PDO $pdo, array $data): void
{
    $steamid = $data['steamid'] ?? '';
    $weaponid = (int)($data['weaponid'] ?? 0);
    $paintid = (int)($data['paintid'] ?? 0);
    $teamid = (int)($data['teamid'] ?? 0);
    $float = $data['float'] ?? '0.000001';
    $seed = $data['seed'] ?? '1';

    if ($float === '') {
        $float = '0.000001';
    }
    if ($seed === '') {
        $seed = '1';
    }

    if ($teamid === 2 || $teamid === 3) {
        $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_wear = :f, weapon_seed = :s WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_paint_id = :pid AND weapon_team = :tid");
        $stmt->execute(['f' => $float, 's' => $seed, 'sid' => $steamid, 'wid' => $weaponid, 'pid' => $paintid, 'tid' => $teamid]);
    } else {
        $stmt = $pdo->prepare("UPDATE wp_player_skins SET weapon_wear = :f, weapon_seed = :s WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_paint_id = :pid");
        $stmt->execute(['f' => $float, 's' => $seed, 'sid' => $steamid, 'wid' => $weaponid, 'pid' => $paintid]);
    }

    $stmt = $pdo->prepare("SELECT weapon_team FROM wp_player_skins WHERE steamid = :sid AND weapon_defindex = :wid AND weapon_paint_id = :pid");
    $stmt->execute(['sid' => $steamid, 'wid' => $weaponid, 'pid' => $paintid]);
    $teamidResult = $stmt->fetch(PDO::FETCH_ASSOC);

    jsonResponse([
        'steamid'  => $steamid,
        'weaponid' => $weaponid,
        'paintid'  => $paintid,
        'teamid'   => $teamidResult,
        'float'    => $float,
        'seed'     => $seed,
    ]);
}
