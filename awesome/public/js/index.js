/**
 * CS2 WeaponPaints Website - PHP Version
 * Main JS - Uses AJAX (fetch) instead of Socket.IO
 */

// AJAX helper to replace socket.emit / socket.on
const api = {
    async post(action, data) {
        const response = await fetch('/api/weapon/' + action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });
        return response.json();
    }
};

// Event listeners registry (replaces socket.on)
const eventHandlers = {};

function onEvent(eventName, handler) {
    if (!eventHandlers[eventName]) {
        eventHandlers[eventName] = [];
    }
    eventHandlers[eventName].push(handler);
}

function emitEvent(eventName, data) {
    if (eventHandlers[eventName]) {
        eventHandlers[eventName].forEach(fn => fn(data));
    }
}

// Socket-compatible emit wrapper
const socket = {
    async emit(action, data) {
        try {
            const result = await api.post(action, data);
            // Map action to response event name
            const eventMap = {
                'get-weapon-skins': 'weapon-skins-retrieved',
                'get-team-knives': 'team-knives-retrieved',
                'get-team-gloves': 'team-gloves-retrieved',
                'change-knife': 'knife-changed',
                'change-gloves': 'gloves-changed',
                'revert-equipped-gloves': 'reverted-equipped-gloves',
                'change-skin': 'skin-changed',
                'change-agent': 'agent-changed',
                'change-music': 'music-changed',
                'unequip-knife': 'knife-unequipped',
                'unequip-gloves': 'gloves-unequipped',
                'reset-skin': 'skin-reset',
                'change-params': 'params-changed',
            };
            const responseEvent = eventMap[action];
            if (responseEvent) {
                emitEvent(responseEvent, result);
            }
        } catch (err) {
            console.error('API error:', action, err);
        }
    },
    on(eventName, handler) {
        onEvent(eventName, handler);
    }
};

let currentWeaponId = "";
let currentPaintId = "";
let currentWeaponName = "";

function getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

const weaponIds = {
    "weapon_deagle": 1,
    "weapon_elite": 2,
    "weapon_fiveseven": 3,
    "weapon_glock": 4,
    "weapon_ak47": 7,
    "weapon_aug": 8,
    "weapon_awp": 9,
    "weapon_famas": 10,
    "weapon_g3sg1": 11,
    "weapon_galilar": 13,
    "weapon_m249": 14,
    "weapon_m4a1": 16,
    "weapon_mac10": 17,
    "weapon_p90": 19,
    "weapon_mp5sd": 23,
    "weapon_ump45": 24,
    "weapon_xm1014": 25,
    "weapon_bizon": 26,
    "weapon_mag7": 27,
    "weapon_negev": 28,
    "weapon_sawedoff": 29,
    "weapon_tec9": 30,
    "weapon_taser": 31,
    "weapon_hkp2000": 32,
    "weapon_mp7": 33,
    "weapon_mp9": 34,
    "weapon_nova": 35,
    "weapon_p250": 36,
    "weapon_shield": 37,
    "weapon_scar20": 38,
    "weapon_sg556": 39,
    "weapon_ssg08": 40,
    "weapon_knifegg": 41,
    "weapon_knife": 42,
    "weapon_flashbang": 43,
    "weapon_hegrenade": 44,
    "weapon_smokegrenade": 45,
    "weapon_molotov": 46,
    "weapon_decoy": 47,
    "weapon_incgrenade": 48,
    "weapon_c4": 49,
    "weapon_healthshot": 57,
    "weapon_knife_t": 59,
    "weapon_m4a1_silencer": 60,
    "weapon_usp_silencer": 61,
    "weapon_cz75a": 63,
    "weapon_revolver": 64,
    "weapon_tagrenade": 68,
    "weapon_fists": 69,
    "weapon_breachcharge": 70,
    "weapon_tablet": 72,
    "weapon_melee": 74,
    "weapon_axe": 75,
    "weapon_hammer": 76,
    "weapon_spanner": 78,
    "weapon_knife_ghost": 80,
    "weapon_firebomb": 81,
    "weapon_diversion": 82,
    "weapon_frag_grenade": 83,
    "weapon_snowball": 84,
    "weapon_bumpmine": 85,
    "weapon_bayonet": 500,
    "weapon_knife_css": 503,
    "weapon_knife_flip": 505,
    "weapon_knife_gut": 506,
    "weapon_knife_karambit": 507,
    "weapon_knife_m9_bayonet": 508,
    "weapon_knife_tactical": 509,
    "weapon_knife_falchion": 512,
    "weapon_knife_survival_bowie": 514,
    "weapon_knife_butterfly": 515,
    "weapon_knife_push": 516,
    "weapon_knife_cord": 517,
    "weapon_knife_canis": 518,
    "weapon_knife_ursus": 519,
    "weapon_knife_gypsy_jackknife": 520,
    "weapon_knife_outdoor": 521,
    "weapon_knife_stiletto": 522,
    "weapon_knife_widowmaker": 523,
    "weapon_knife_skeleton": 525,
    "weapon_knife_kukri": 526,
    "studded_brokenfang_gloves": 4725,
    "studded_bloodhound_gloves": 5027,
    "t_gloves": 5028,
    "ct_gloves": 5029,
    "sporty_gloves": 5030,
    "slick_gloves": 5031,
    "leather_handwraps": 5032,
    "motorcycle_gloves": 5033,
    "specialist_gloves": 5034,
    "studded_hydra_gloves": 5035
};

const editModal = (img, weaponName, paintName, float, seed, weaponId, paintId) => {
    document.getElementById("modalImg").src = img;
    document.getElementById("modalWeapon").innerText = weaponName;
    document.getElementById("modalPaint").innerText = paintName;

    const floatValue = parseFloat(Number(float).toFixed(6));
    document.getElementById("floatSlider").value = floatValue;
    document.getElementById("float").value = floatValue;
    updateFloatText(floatValue);

    document.getElementById("pattern").value = seed;

    currentWeaponName = weaponId;
    currentWeaponId = weaponIds[weaponId];
    currentPaintId = paintId;
};

window.openPatternModal = (weaponName) => {
    const weaponId = weaponIds[weaponName];
    const equippedSkin = (window.selectedSkins || []).find(s => s.weapon_defindex == weaponId || s.model_idx == weaponId);

    if (equippedSkin) {
        const skinData = skinsObject.find(s => s.paint_index == equippedSkin.weapon_paint_id && (weaponIds[s.weapon.id] == weaponId));

        if (skinData) {
            const phase = skinData.phase ? ` (${skinData.phase})` : "";
            editModal(
                skinData.image,
                skinData.weapon.name,
                `${skinData.pattern.name}${phase}`,
                equippedSkin.weapon_wear,
                equippedSkin.weapon_seed,
                skinData.weapon.id,
                skinData.paint_index
            );

            // Trigger bootstrap modal manually if not triggered via data attributes
            const modalEl = document.getElementById('patternFloat');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        }
    }
};

const changeParams = () => {
    let steamid = (window.user ? window.user.id : null);
    let weaponid = currentWeaponId;
    let paintid = currentPaintId;
    let float = document.getElementById("float").value;
    let seed = document.getElementById("pattern").value;

    let teamid = 0;

    // Check for both paint-id based and weapon-name based checkboxes
    const tCheckbox = document.getElementById(`equip-t-${paintid}`) || document.getElementById(`equip-t-${currentWeaponName}`);
    const ctCheckbox = document.getElementById(`equip-ct-${paintid}`) || document.getElementById(`equip-ct-${currentWeaponName}`);

    if (!steamid) {
        console.error("User not found");
        return;
    }

    if (tCheckbox && tCheckbox.checked) {
        teamid = 2;
    } else if (ctCheckbox && ctCheckbox.checked) {
        teamid = 3;
    }

    if (tCheckbox && ctCheckbox && tCheckbox.checked && ctCheckbox.checked) {
        teamid = 0;
    }

    document.getElementById("modalButton").innerHTML =
        `<div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`;

    socket.emit("change-params", { steamid: steamid, weaponid: weaponid, paintid: paintid, teamid: teamid, float: float, seed: seed });
};

socket.on("params-changed", data => {
    document.getElementById("modalButton").innerHTML = langObject.change;

    document.getElementById("floatSlider").value = data.float;
    document.getElementById("float").value = data.float;
    updateFloatText(data.float);

    document.getElementById("pattern").value = data.seed;

    // Update local storage and global state
    window.selectedSkins = data.playerSkins || [];
    sessionStorage.setItem("selected_skins", JSON.stringify(window.selectedSkins));

    const weaponName = getKeyByValue(weaponIds, data.weaponid);
    if (weaponName) {
        changeSkinCard({ weapon_name: weaponName, weapon_defindex: data.weaponid }, { weapon_paint_id: data.paintid });
    }
});
